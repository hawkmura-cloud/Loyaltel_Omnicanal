// ======================================================
// SISTEMA DE LOGGING PARA OPERACIONES ADMINISTRATIVAS
// ======================================================

function logOperacion(usuario, tipo, detalles) {
    const timestamp = new Date().toISOString();
    const logEntry = {
        timestamp,
        usuario: `${usuario.rol}:${usuario.usuario} (ID: ${usuario.asesorId})`,
        tipo,
        detalles
    };

    // En producción, esto debería ir a un sistema de logging profesional
    // (Winston, Papertrail, Loggly, etc.)
    console.log(`[AUDIT] ${timestamp} | ${logEntry.usuario} | ${tipo} | ${JSON.stringify(detallos)}`);

    return logEntry;
}

function logErrorOperacion(usuario, tipo, error, detalles) {
    const timestamp = new Date().toISOString();
    const logEntry = {
        timestamp,
        usuario: `${usuario.rol}:${usuario.usuario} (ID: ${usuario.asesorId})`,
        tipo,
        error: error.message,
        detalles
    };

    console.error(`[AUDIT ERROR] ${timestamp} | ${logEntry.usuario} | ${tipo} | ${error.message} | ${JSON.stringify(detallos)}`);

    return logEntry;
}

module.exports = {
    logOperacion,
    logErrorOperacion
};
