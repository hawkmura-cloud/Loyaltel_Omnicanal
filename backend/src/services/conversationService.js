const pool = require("../config/database");
const { enviarEventoATodos } = require("../utils/sseEmitter");


// ======================================================
// OBTENER O CREAR CLIENTE
// ======================================================

async function obtenerOCrearCliente(telefono) {

    const [clientes] = await pool.query(

        `SELECT *
         FROM clientes
         WHERE telefono = ?
         LIMIT 1`,

        [telefono]

    );


    if (clientes.length > 0) {

        return clientes[0];

    }


    const [resultado] = await pool.query(

        `INSERT INTO clientes
        (telefono)
        VALUES (?)`,

        [telefono]

    );


    return {

        id: resultado.insertId,

        telefono

    };

}


// ======================================================
// OBTENER O CREAR CONVERSACIÓN
// ======================================================

async function obtenerOCrearConversacion(clienteId) {

    // Buscamos la última conversación que todavía
    // no haya sido finalizada.

    const [conversaciones] = await pool.query(

        `SELECT *
         FROM conversaciones
         WHERE cliente_id = ?
         AND estado_conversacion IN (
             'activa',
             'atendida',
             'esperando_asesor'
         )
         ORDER BY id DESC
         LIMIT 1`,

        [clienteId]

    );


    // --------------------------------------------------
    // SI YA EXISTE UNA CONVERSACIÓN
    // --------------------------------------------------

    if (conversaciones.length > 0) {

        return conversaciones[0];

    }


    // --------------------------------------------------
    // SI NO EXISTE, CREAR UNA NUEVA
    // --------------------------------------------------

    const [resultado] = await pool.query(

        `INSERT INTO conversaciones
        (
            cliente_id,
            estado,
            bot_activo,
            estado_conversacion
        )
        VALUES (
            ?,
            'inicio',
            TRUE,
            'activa'
        )`,

        [clienteId]

    );

    const nuevaConversacion = {
        id: resultado.insertId,
        cliente_id: clienteId,
        estado: "inicio",
        bot_activo: true,
        estado_conversacion: "activa"
    };

    // Enviar evento SSE de nueva conversación
    enviarEventoATodos("nueva_conversacion", {
        conversacion_id: nuevaConversacion.id,
        cliente_id: clienteId
    });

    return nuevaConversacion;

}


// ======================================================
// ACTUALIZAR ESTADO DEL BOT
// ======================================================

async function actualizarEstadoConversacion(
    conversacionId,
    estado
) {

    await pool.query(

        `UPDATE conversaciones
         SET estado = ?
         WHERE id = ?`,

        [
            estado,
            conversacionId
        ]

    );

}


// ======================================================
// GUARDAR MENSAJE
// ======================================================

async function guardarMensaje(
    conversacionId,
    remitente,
    mensaje,
    origenMensaje = 'sistema'
) {

    await pool.query(

        `INSERT INTO mensajes
        (
            conversacion_id,
            remitente,
            mensaje,
            origen_mensaje
        )
        VALUES (?, ?, ?, ?)`,

        [
            conversacionId,
            remitente,
            mensaje,
            origenMensaje
        ]

    );

    // Enviar evento SSE de nuevo mensaje
    enviarEventoATodos("nuevo_mensaje", {
        conversacion_id: conversacionId,
        remitente: remitente,
        origen_mensaje: origenMensaje
    });

}


// ======================================================
// ACTUALIZAR DATOS DEL CLIENTE
// ======================================================

async function actualizarDatosCliente(
    clienteId,
    datos
) {

    const campos = [];

    const valores = [];


    // --------------------------------------------------
    // NOMBRE
    // --------------------------------------------------

    if (datos.nombre !== undefined) {

        campos.push(
            "nombre = ?"
        );

        valores.push(
            datos.nombre
        );

    }


    // --------------------------------------------------
    // DISTRITO
    // --------------------------------------------------

    if (datos.distrito !== undefined) {

        campos.push(
            "distrito = ?"
        );

        valores.push(
            datos.distrito
        );

    }


    // --------------------------------------------------
    // DIRECCIÓN
    // --------------------------------------------------

    if (datos.direccion !== undefined) {

        campos.push(
            "direccion = ?"
        );

        valores.push(
            datos.direccion
        );

    }


    // --------------------------------------------------
    // ORIGEN
    // --------------------------------------------------

    if (datos.origen !== undefined) {

        campos.push(
            "origen = ?"
        );

        valores.push(
            datos.origen
        );

    }


    // --------------------------------------------------
    // CAMPAÑA
    // --------------------------------------------------

    if (datos.campania !== undefined) {

        campos.push(
            "campania = ?"
        );

        valores.push(
            datos.campania
        );

    }


    // --------------------------------------------------
    // PRODUCTO DE INTERÉS
    // --------------------------------------------------

    if (datos.producto_interes !== undefined) {

        campos.push(
            "producto_interes = ?"
        );

        valores.push(
            datos.producto_interes
        );

    }


    // --------------------------------------------------
    // OBSERVACIONES
    // --------------------------------------------------

    if (datos.observaciones !== undefined) {

        campos.push(
            "observaciones = ?"
        );

        valores.push(
            datos.observaciones
        );

    }


    // --------------------------------------------------
    // SI NO HAY DATOS PARA ACTUALIZAR
    // --------------------------------------------------

    if (campos.length === 0) {

        return;

    }


    // --------------------------------------------------
    // AGREGAR ID DEL CLIENTE
    // --------------------------------------------------

    valores.push(
        clienteId
    );


    // --------------------------------------------------
    // EJECUTAR UPDATE
    // --------------------------------------------------

    await pool.query(

        `UPDATE clientes
         SET ${campos.join(", ")}
         WHERE id = ?`,

        valores

    );

}


// ======================================================
// EXPORTAR FUNCIONES
// ======================================================

 // ======================================================
// OBTENER HISTORIAL DE MENSAJES
// ======================================================

async function obtenerHistorialMensajes(conversacionId) {

    const [mensajes] = await pool.query(

        `SELECT
            id,
            conversacion_id,
            remitente,
            mensaje,
            fecha
         FROM mensajes
         WHERE conversacion_id = ?
         ORDER BY fecha ASC, id ASC`,

        [conversacionId]

    );

    return mensajes;

}
module.exports = {

    obtenerOCrearCliente,

    obtenerOCrearConversacion,

    actualizarEstadoConversacion,

    actualizarDatosCliente,

    obtenerHistorialMensajes,

    guardarMensaje

};