// ======================================================
// EMISOR DE EVENTOS SSE PARA ACTUALIZACIONES EN TIEMPO REAL
// ======================================================

const sseClients = new Set();

function agregarCliente(cliente) {
    sseClients.add(cliente);
}

function eliminarCliente(cliente) {
    sseClients.delete(cliente);
}

function enviarEventoATodos(evento, datos) {
    const mensaje = `event: ${evento}\ndata: ${JSON.stringify(datos)}\n\n`;

    sseClients.forEach(client => {
        try {
            // client puede ser un objeto con {response, userId, role} o solo response (compatibilidad)
            const response = client.response || client;
            response.write(mensaje);
        } catch (error) {
            console.error("Error enviando evento a cliente SSE:", error);
            sseClients.delete(client);
        }
    });
}

module.exports = {
    agregarCliente,
    eliminarCliente,
    enviarEventoATodos
};
