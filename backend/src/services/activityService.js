const pool = require("../config/database");

// ======================================================
// REGISTRAR ACTIVIDAD
// ======================================================

async function registrarActividad(conversacionId, tipoAccion, asesorId = null, descripcion = null) {
    await pool.query(
        `INSERT INTO historial_actividad
         (conversacion_id, asesor_id, tipo_accion, descripcion)
         VALUES (?, ?, ?, ?)`,
        [conversacionId, asesorId, tipoAccion, descripcion]
    );
}

// ======================================================
// OBTENER HISTORIAL DE ACTIVIDAD DE UNA CONVERSACIÓN
// ======================================================

async function obtenerHistorialActividad(conversacionId) {
    const [actividad] = await pool.query(`
        SELECT
            ha.id,
            ha.tipo_accion,
            ha.descripcion,
            ha.fecha,
            a.nombre AS asesor_nombre
        FROM historial_actividad ha
        LEFT JOIN asesores a
            ON a.id = ha.asesor_id
        WHERE ha.conversacion_id = ?
        ORDER BY ha.fecha ASC
    `, [conversacionId]);

    return actividad;
}

// ======================================================
// OBTENER HISTORIAL DE ACTIVIDAD DE UN ASESOR
// ======================================================

async function obtenerActividadAsesor(asesorId, limite = 50) {
    const [actividad] = await pool.query(`
        SELECT
            ha.id,
            ha.conversacion_id,
            ha.tipo_accion,
            ha.descripcion,
            ha.fecha,
            COALESCE(
                NULLIF(TRIM(cl.nombre), ''),
                CONCAT('Cliente ', cl.telefono)
            ) AS cliente,
            cl.telefono
        FROM historial_actividad ha
        INNER JOIN conversaciones c
            ON c.id = ha.conversacion_id
        INNER JOIN clientes cl
            ON cl.id = c.cliente_id
        WHERE ha.asesor_id = ?
        ORDER BY ha.fecha DESC
        LIMIT ?
    `, [asesorId, limite]);

    return actividad;
}

module.exports = {
    registrarActividad,
    obtenerHistorialActividad,
    obtenerActividadAsesor
};
