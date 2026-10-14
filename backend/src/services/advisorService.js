
const pool = require("../config/database");
const { enviarEventoATodos } = require("../utils/sseEmitter");
const { registrarActividad } = require("./activityService");


// ======================================================
// BUSCAR ASESOR DISPONIBLE
// ======================================================

async function obtenerAsesorDisponible() {
    const [asesores] = await pool.query(
        `SELECT *
         FROM asesores
         WHERE estado = 'disponible'
         AND rol = 'asesor'
         ORDER BY id ASC
         LIMIT 1`
    );

    return asesores.length > 0 ? asesores[0] : null;
}


// ======================================================
// ASIGNAR ASESOR A UNA CONVERSACIÓN
// ======================================================

async function asignarAsesor(conversacionId, asesorId) {
    const connection = await pool.getConnection();

    try {
        await connection.beginTransaction();

        // Asignar la conversación al asesor (sin cambiar estado del asesor)
        const [conversaciones] = await connection.query(
           `UPDATE conversaciones
 SET asesor_id = ?,
     bot_activo = FALSE,
     estado_conversacion = 'atendida',
     fecha_asignacion = CURRENT_TIMESTAMP
 WHERE id = ?
 AND estado_conversacion IN ('activa', 'esperando_asesor')`,
            [asesorId, conversacionId]
        );

        if (conversaciones.affectedRows === 0) {
            await connection.rollback();
            return false;
        }

        // Marcar la solicitud en cola como atendida, si existe.
        await connection.query(
            `UPDATE cola_atencion
             SET estado = 'atendida',
                 fecha_atencion = CURRENT_TIMESTAMP
             WHERE conversacion_id = ?
             AND estado = 'esperando'`,
            [conversacionId]
        );

        // Registrar actividad dentro de la transacción
        await connection.query(
            `INSERT INTO historial_actividad
             (conversacion_id, asesor_id, tipo_accion, descripcion)
             VALUES (?, ?, 'asignacion', ?)`,
            [conversacionId, asesorId, `Conversación asignada a asesor ${asesorId}`]
        );

        await connection.commit();

        // Enviar evento SSE de asignación
        enviarEventoATodos("asesor_asignado", {
            conversacion_id: conversacionId,
            asesor_id: asesorId
        });

        return true;

    } catch (error) {
        await connection.rollback();
        throw error;

    } finally {
        connection.release();
    }
}


// ======================================================
// AGREGAR CONVERSACIÓN A LA COLA
// ======================================================

async function agregarACola(conversacionId) {
    const [existentes] = await pool.query(
        `SELECT id
         FROM cola_atencion
         WHERE conversacion_id = ?
         AND estado = 'esperando'
         LIMIT 1`,
        [conversacionId]
    );

    let colaId;

    if (existentes.length > 0) {
        colaId = existentes[0].id;

    } else {
        const [resultado] = await pool.query(
            `INSERT INTO cola_atencion
             (conversacion_id, prioridad)
             VALUES (?, 1)`,
            [conversacionId]
        );

        colaId = resultado.insertId;
    }

    await pool.query(
        `UPDATE conversaciones
         SET estado_conversacion = 'esperando_asesor',
             bot_activo = FALSE
         WHERE id = ?`,
        [conversacionId]
    );

    // Enviar evento SSE de agregado a cola
    enviarEventoATodos("cola_actualizada", {
        conversacion_id: conversacionId,
        accion: "agregado"
    });

    return colaId;
}


// ======================================================
// OBTENER SIGUIENTE CONVERSACIÓN EN ESPERA
// ======================================================

async function obtenerSiguienteEnCola() {
    const [filas] = await pool.query(
        `SELECT ca.id AS cola_id,
                ca.conversacion_id,
                ca.prioridad,
                ca.fecha_ingreso
         FROM cola_atencion ca
         INNER JOIN conversaciones c
             ON c.id = ca.conversacion_id
         WHERE ca.estado = 'esperando'
         AND c.estado_conversacion = 'esperando_asesor'
         ORDER BY ca.prioridad DESC,
                  ca.fecha_ingreso ASC,
                  ca.id ASC
         LIMIT 1`
    );

    return filas.length > 0 ? filas[0] : null;
}


// ======================================================
// ASIGNAR AUTOMÁTICAMENTE AL SIGUIENTE DE LA COLA
// ======================================================

async function asignarSiguienteDeCola() {
    while (true) {
        const asesor = await obtenerAsesorDisponible();

        if (!asesor) {
            return {
                asignado: false,
                motivo: "No hay asesores disponibles"
            };
        }

        const siguiente = await obtenerSiguienteEnCola();

        if (!siguiente) {
            return {
                asignado: false,
                motivo: "La cola está vacía"
            };
        }

        const asignacionCorrecta = await asignarAsesor(
            siguiente.conversacion_id,
            asesor.id
        );

        if (!asignacionCorrecta) {
            // Puede que la conversación haya cambiado de estado.
            // La retiramos de la cola si ya no debe esperar.
            await pool.query(
                `UPDATE cola_atencion ca
                 INNER JOIN conversaciones c
                     ON c.id = ca.conversacion_id
                 SET ca.estado = 'cancelada'
                 WHERE ca.id = ?
                 AND c.estado_conversacion NOT IN (
                     'esperando_asesor'
                 )`,
                [siguiente.cola_id]
            );

            // Evitar repetir indefinidamente sobre una fila inválida.
            const [pendiente] = await pool.query(
                `SELECT id
                 FROM cola_atencion
                 WHERE id = ?
                 AND estado = 'esperando'`,
                [siguiente.cola_id]
            );

            if (pendiente.length > 0) {
                return {
                    asignado: false,
                    motivo: "No se pudo completar la asignación"
                };
            }

            continue;
        }

        const [datos] = await pool.query(
            `SELECT nombre, telefono
             FROM asesores
             WHERE id = ?`,
            [asesor.id]
        );

        return {
            asignado: true,
            conversacion_id: siguiente.conversacion_id,
            asesor: datos[0]
        };
    }
}


// ======================================================
// LIBERAR ASESOR
// ======================================================

async function liberarAsesor(asesorId) {
    await pool.query(
        `UPDATE asesores
         SET estado = 'disponible'
         WHERE id = ?`,
        [asesorId]
    );

    // Enviar evento SSE de asesor liberado
    enviarEventoATodos("asesor_liberado", {
        asesor_id: asesorId
    });
}


// ======================================================
// EXPORTAR FUNCIONES
// ======================================================

module.exports = {
    obtenerAsesorDisponible,
    asignarAsesor,
    agregarACola,
    obtenerSiguienteEnCola,
    asignarSiguienteDeCola,
    liberarAsesor
};
