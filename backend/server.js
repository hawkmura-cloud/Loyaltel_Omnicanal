const express = require("express");
const cors = require("cors");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
require("dotenv").config();

const { procesarMensaje } = require("./src/bot/bot");
const pool = require("./src/config/database");
const { agregarCliente, eliminarCliente, enviarEventoATodos } = require("./src/utils/sseEmitter");
const { authenticateToken, requireSupervisor, requireSuperAdmin, requireAsesorOrSelf } = require("./src/middleware/auth");
const whatsappService = require("./src/services/whatsappService");

const JWT_SECRET = process.env.JWT_SECRET || (() => {
    console.warn('⚠️ JWT_SECRET no configurado en .env, usando valor por defecto (NO SEGURO)');
    return 'claro_chatbot_secret_key_2026_CHANGE_IN_PRODUCTION';
})();

const {
    obtenerOCrearCliente,
    obtenerOCrearConversacion,
    actualizarEstadoConversacion,
    actualizarDatosCliente,
    obtenerHistorialMensajes,
    guardarMensaje
} = require("./src/services/conversationService");

const {
    registrarActividad,
    obtenerHistorialActividad
} = require("./src/services/activityService");


const {
    obtenerAsesorDisponible,
    asignarAsesor,
    agregarACola,
    asignarSiguienteDeCola,
    liberarAsesor
} = require("./src/services/advisorService");


const app = express();

app.use(cors());
app.use(express.json());


// ======================================================
// RUTA PRINCIPAL
// ======================================================

app.get("/", (req, res) => {
    res.json({
        sistema: "Claro Hogar Chatbot",
        estado: "online"
    });
});


// ======================================================
// ENDPOINT DE LOGIN
// ======================================================

app.post("/api/auth/login", async (req, res) => {
    try {
        const { usuario, password } = req.body;

        if (!usuario || !password) {
            return res.status(400).json({
                ok: false,
                error: "Usuario y contraseña son requeridos"
            });
        }

        const [asesores] = await pool.query(
            `SELECT id, nombre, usuario, password_hash, rol, estado
             FROM asesores
             WHERE usuario = ?
             LIMIT 1`,
            [usuario]
        );

        if (asesores.length === 0) {
            return res.status(401).json({
                ok: false,
                error: "Credenciales inválidas"
            });
        }

        const asesor = asesores[0];

        const passwordValid = await bcrypt.compare(password, asesor.password_hash);

        if (!passwordValid) {
            return res.status(401).json({
                ok: false,
                error: "Credenciales inválidas"
            });
        }

        const token = jwt.sign(
            {
                asesorId: asesor.id,
                usuario: asesor.usuario,
                rol: asesor.rol
            },
            JWT_SECRET,
            { expiresIn: '8h' }
        );

        res.json({
            ok: true,
            token,
            usuario: {
                id: asesor.id,
                nombre: asesor.nombre,
                usuario: asesor.usuario,
                rol: asesor.rol,
                estado: asesor.estado
            }
        });

    } catch (error) {
        console.error("Error en login:", error);
        res.status(500).json({
            ok: false,
            error: "Error interno del servidor"
        });
    }
});


// ======================================================
// ENDPOINTS PARA GESTIÓN DE CLIENTES OMNICANAL
// ======================================================

// Obtener todos los clientes (solo supervisor)
app.get("/api/clientes_panel", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const [clientes] = await pool.query(
            'SELECT * FROM clientes_panel ORDER BY nombre'
        );
        res.json({ ok: true, clientes });
    } catch (error) {
        console.error("Error obteniendo clientes:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Crear nuevo cliente (solo supervisor)
app.post("/api/clientes_panel", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const { nombre, codigo, descripcion } = req.body;

        if (!nombre || !codigo) {
            return res.status(400).json({
                ok: false,
                error: "Nombre y código son requeridos"
            });
        }

        const [result] = await pool.query(
            `INSERT INTO clientes_panel (nombre, codigo, descripcion, activo)
             VALUES (?, ?, ?, TRUE)`,
            [nombre, codigo, descripcion]
        );

        res.json({
            ok: true,
            cliente: { id: result.insertId, nombre, codigo, descripcion, activo: true }
        });
    } catch (error) {
        console.error("Error creando cliente:", error);
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({
                ok: false,
                error: "El código ya existe"
            });
        }
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Actualizar cliente (solo supervisor)
app.put("/api/clientes_panel/:id", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const { id } = req.params;
        const { nombre, codigo, descripcion, activo } = req.body;

        const [result] = await pool.query(
            `UPDATE clientes_panel
             SET nombre = ?, codigo = ?, descripcion = ?, activo = ?
             WHERE id = ?`,
            [nombre, codigo, descripcion, activo, id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                ok: false,
                error: "Cliente no encontrado"
            });
        }

        res.json({ ok: true, cliente: { id, nombre, codigo, descripcion, activo } });
    } catch (error) {
        console.error("Error actualizando cliente:", error);
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({
                ok: false,
                error: "El código ya existe"
            });
        }
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Eliminar cliente (solo supervisor)
app.delete("/api/clientes_panel/:id", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const { id } = req.params;

        // Verificar que no tenga conversaciones activas
        const [convs] = await pool.query(
            'SELECT COUNT(*) as total FROM conversaciones WHERE cliente_panel_id = ? AND estado_conversacion != "finalizada"',
            [id]
        );

        if (convs[0].total > 0) {
            return res.status(400).json({
                ok: false,
                error: "No se puede eliminar un cliente con conversaciones activas"
            });
        }

        const [result] = await pool.query(
            'DELETE FROM clientes_panel WHERE id = ?',
            [id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                ok: false,
                error: "Cliente no encontrado"
            });
        }

        res.json({ ok: true });
    } catch (error) {
        console.error("Error eliminando cliente:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});


// Obtener clientes (solo lectura para asesores y supervisor)
app.get("/api/clientes_panel/lista", authenticateToken, async (req, res) => {
    try {
        const [clientes] = await pool.query(
            'SELECT * FROM clientes_panel WHERE activo = TRUE ORDER BY nombre'
        );
        res.json({ ok: true, clientes });
    } catch (error) {
        console.error("Error obteniendo clientes:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});


// ======================================================
// ENDPOINTS PARA GESTIÓN DE USUARIOS (SOLO SUPER ADMIN)
// ======================================================

// Obtener todos los usuarios
app.get("/api/usuarios", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const [usuarios] = await pool.query(
            `SELECT id, nombre, telefono, usuario, rol, estado, fecha_registro
             FROM asesores
             ORDER BY rol, nombre`
        );
        res.json({ ok: true, usuarios });
    } catch (error) {
        console.error("Error obteniendo usuarios:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Crear nuevo usuario
app.post("/api/usuarios", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { nombre, telefono, usuario, password, rol, estado, cliente_panel_id, campaña_whatsapp_id } = req.body;

        if (!nombre || !usuario || !password || !rol) {
            return res.status(400).json({
                ok: false,
                error: "Nombre, usuario, password y rol son requeridos"
            });
        }

        if (!['asesor', 'supervisor'].includes(rol)) {
            return res.status(400).json({
                ok: false,
                error: "Rol inválido. Debe ser 'asesor' o 'supervisor'"
            });
        }

        const passwordHash = await bcrypt.hash(password, 10);

        // Construir query dinámicamente según si es asesor o supervisor
        let query, values;
        
        if (rol === 'asesor') {
            query = `INSERT INTO asesores (nombre, telefono, usuario, password_hash, rol, estado, cliente_panel_id, campaña_whatsapp_id)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)`;
            values = [nombre, telefono || null, usuario, passwordHash, rol, estado || 'disponible', cliente_panel_id || null, campaña_whatsapp_id || null];
        } else {
            query = `INSERT INTO asesores (nombre, telefono, usuario, password_hash, rol, estado)
                      VALUES (?, ?, ?, ?, ?, ?)`;
            values = [nombre, telefono || null, usuario, passwordHash, rol, estado || 'disponible'];
        }

        const [result] = await pool.query(query, values);

        res.json({
            ok: true,
            usuario: {
                id: result.insertId,
                nombre,
                telefono,
                usuario,
                rol,
                estado: estado || 'disponible',
                cliente_panel_id: rol === 'asesor' ? cliente_panel_id : null,
                campaña_whatsapp_id: rol === 'asesor' ? campaña_whatsapp_id : null
            }
        });
    } catch (error) {
        console.error("Error creando usuario:", error);
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({
                ok: false,
                error: "El usuario ya existe"
            });
        }
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Actualizar usuario
app.put("/api/usuarios/:id", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { id } = req.params;
        const { nombre, telefono, usuario, password, rol, estado, cliente_panel_id, campaña_whatsapp_id } = req.body;

        // Verificar que no se intente modificar el propio super admin
        if (usuario === 'superadmin') {
            return res.status(403).json({
                ok: false,
                error: "No se puede modificar la cuenta de super administrador"
            });
        }

        const updates = [];
        const values = [];

        if (nombre) {
            updates.push('nombre = ?');
            values.push(nombre);
        }
        if (telefono !== undefined) {
            updates.push('telefono = ?');
            values.push(telefono || null);
        }
        if (usuario) {
            updates.push('usuario = ?');
            values.push(usuario);
        }
        if (password) {
            updates.push('password_hash = ?');
            values.push(await bcrypt.hash(password, 10));
        }
        if (rol) {
            if (!['asesor', 'supervisor'].includes(rol)) {
                return res.status(400).json({
                    ok: false,
                    error: "Rol inválido"
                });
            }
            updates.push('rol = ?');
            values.push(rol);
        }
        if (estado) {
            updates.push('estado = ?');
            values.push(estado);
        }
        if (cliente_panel_id !== undefined) {
            updates.push('cliente_panel_id = ?');
            values.push(cliente_panel_id || null);
        }
        if (campaña_whatsapp_id !== undefined) {
            updates.push('campaña_whatsapp_id = ?');
            values.push(campaña_whatsapp_id || null);
        }

        if (updates.length === 0) {
            return res.status(400).json({
                ok: false,
                error: "No se proporcionaron campos para actualizar"
            });
        }

        values.push(id);

        const [result] = await pool.query(
            `UPDATE asesores SET ${updates.join(', ')} WHERE id = ?`,
            values
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                ok: false,
                error: "Usuario no encontrado"
            });
        }

        res.json({ ok: true });
    } catch (error) {
        console.error("Error actualizando usuario:", error);
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({
                ok: false,
                error: "El usuario ya existe"
            });
        }
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Eliminar usuario
app.delete("/api/usuarios/:id", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { id } = req.params;

        // Verificar que no se intente eliminar el super admin
        const [check] = await pool.query(
            'SELECT usuario FROM asesores WHERE id = ?',
            [id]
        );

        if (check.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Usuario no encontrado"
            });
        }

        if (check[0].usuario === 'superadmin') {
            return res.status(403).json({
                ok: false,
                error: "No se puede eliminar la cuenta de super administrador"
            });
        }

        // Verificar que no tenga conversaciones activas
        const [convs] = await pool.query(
            'SELECT COUNT(*) as total FROM conversaciones WHERE asesor_id = ? AND estado_conversacion != "finalizada"',
            [id]
        );

        if (convs[0].total > 0) {
            return res.status(400).json({
                ok: false,
                error: "No se puede eliminar un usuario con conversaciones activas"
            });
        }

        const [result] = await pool.query(
            'DELETE FROM asesores WHERE id = ?',
            [id]
        );

        res.json({ ok: true });
    } catch (error) {
        console.error("Error eliminando usuario:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});


// ======================================================
// ENDPOINTS PARA GESTIÓN DE CAMPAÑAS WHATSAPP (SOLO SUPER ADMIN)
// ======================================================

// Obtener todas las campañas de WhatsApp
app.get("/api/campañas_whatsapp", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const [campañas] = await pool.query(`
            SELECT c.*, cp.nombre as cliente_nombre
            FROM campañas_whatsapp c
            INNER JOIN clientes_panel cp ON c.cliente_panel_id = cp.id
            ORDER BY cp.nombre, c.nombre
        `);
        res.json({ ok: true, campañas });
    } catch (error) {
        console.error("Error obteniendo campañas:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Crear nueva campaña de WhatsApp
app.post("/api/campañas_whatsapp", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { cliente_panel_id, nombre, descripcion, numero_whatsapp, api_key, phone_id, access_token, webhook_url, webhook_secret } = req.body;

        if (!cliente_panel_id || !nombre || !numero_whatsapp) {
            return res.status(400).json({
                ok: false,
                error: "Cliente, nombre y número de WhatsApp son requeridos"
            });
        }

        const [result] = await pool.query(
            `INSERT INTO campañas_whatsapp (cliente_panel_id, nombre, descripcion, numero_whatsapp, api_key, phone_id, access_token, webhook_url, webhook_secret, activo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)`,
            [cliente_panel_id, nombre, descripcion, numero_whatsapp, api_key || null, phone_id || null, access_token || null, webhook_url || null, webhook_secret || null]
        );

        res.json({
            ok: true,
            campaña: { id: result.insertId, nombre, numero_whatsapp, activo: true }
        });
    } catch (error) {
        console.error("Error creando campaña:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Actualizar campaña de WhatsApp
app.put("/api/campañas_whatsapp/:id", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { id } = req.params;
        const { nombre, descripcion, numero_whatsapp, api_key, phone_id, access_token, webhook_url, webhook_secret, activo } = req.body;

        const [result] = await pool.query(
            `UPDATE campañas_whatsapp
             SET nombre = ?, descripcion = ?, numero_whatsapp = ?, api_key = ?, phone_id = ?, access_token = ?, webhook_url = ?, webhook_secret = ?, activo = ?
             WHERE id = ?`,
            [nombre, descripcion, numero_whatsapp, api_key, phone_id, access_token, webhook_url, webhook_secret, activo, id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                ok: false,
                error: "Campaña no encontrada"
            });
        }

        res.json({ ok: true });
    } catch (error) {
        console.error("Error actualizando campaña:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});

// Eliminar campaña de WhatsApp
app.delete("/api/campañas_whatsapp/:id", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { id } = req.params;

        // Verificar que no tenga conversaciones activas
        const [convs] = await pool.query(
            'SELECT COUNT(*) as total FROM conversaciones WHERE campaña_whatsapp_id = ? AND estado_conversacion != "finalizada"',
            [id]
        );

        if (convs[0].total > 0) {
            return res.status(400).json({
                ok: false,
                error: "No se puede eliminar una campaña con conversaciones activas"
            });
        }

        const [result] = await pool.query(
            'DELETE FROM campañas_whatsapp WHERE id = ?',
            [id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({
                ok: false,
                error: "Campaña no encontrada"
            });
        }

        res.json({ ok: true });
    } catch (error) {
        console.error("Error eliminando campaña:", error);
        res.status(500).json({ ok: false, error: "Error interno del servidor" });
    }
});


// ======================================================
// ENDPOINTS WEBHOOK WHATSAPP
// ======================================================

// Verificación de webhook (GET) - requerido por Meta para activar el webhook
app.get("/webhook/whatsapp", (req, res) => {
    const mode = req.query['hub.mode'];
    const token = req.query['hub.verify_token'];
    const challenge = req.query['hub.challenge'];

    const verifyToken = whatsappService.verifyWebhook(mode, token, challenge);

    if (verifyToken) {
        res.status(200).send(verifyToken);
    } else {
        res.sendStatus(403);
    }
});

// Recibir mensajes de WhatsApp (POST)
app.post("/webhook/whatsapp", async (req, res) => {
    try {
        console.log('Webhook received:', JSON.stringify(req.body, null, 2));

        const result = await whatsappService.processWebhook(req.body);

        if (result.ok) {
            res.status(200).json({ ok: true });
        } else {
            console.error('Error processing webhook:', result.error);
            res.status(400).json({ ok: false, error: result.error });
        }
    } catch (error) {
        console.error('Error in webhook endpoint:', error);
        res.status(500).json({ ok: false, error: 'Internal server error' });
    }
});


// ======================================================
// ENDPOINT PARA ACTUALIZAR ESTADO DE NEGOCIO
// ======================================================

app.put(
    "/api/conversaciones/:id/estado",
    authenticateToken,
    async (req, res) => {
        try {
            const conversacionId = Number(req.params.id);
            const { estado_negocio } = req.body;

            if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
                return res.status(400).json({
                    ok: false,
                    error: "ID de conversación inválido"
                });
            }

            if (!estado_negocio) {
                return res.status(400).json({
                    ok: false,
                    error: "Estado de negocio es requerido"
                });
            }

            // Validar que el estado sea válido
            const estadosValidos = [
                'sin_cobertura', 'falta_informacion', 'numero_no_existe',
                'venta', 'volver_a_llamar', 'no_aplica', 'atendiendo',
                'deuda', 'no_desea', 'no_contesta', 'cliente_potencial',
                'nuevo_pedido', 'spam', 'cobertura_en_proceso'
            ];

            if (!estadosValidos.includes(estado_negocio)) {
                return res.status(400).json({
                    ok: false,
                    error: "Estado de negocio inválido"
                });
            }

            // Verificar que la conversación existe y pertenece al asesor
            const [conversaciones] = await pool.query(
                `SELECT id, asesor_id, estado_conversacion
                 FROM conversaciones
                 WHERE id = ?
                 LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Si es asesor, verificar que la conversación esté asignada a él
            if (req.user.rol === 'asesor' && conversacion.asesor_id !== req.user.asesorId) {
                return res.status(403).json({
                    ok: false,
                    error: "No tienes permiso para modificar esta conversación"
                });
            }

            // Actualizar estado de negocio
            await pool.query(
                `UPDATE conversaciones
                 SET estado_negocio = ?
                 WHERE id = ?`,
                [estado_negocio, conversacionId]
            );

            // Registrar actividad
            await registrarActividad(
                conversacionId,
                'cambio_estado',
                req.user.rol === 'asesor' ? req.user.asesorId : req.user.supervisorId,
                `Estado cambiado a: ${estado_negocio}`
            );

            res.json({
                ok: true,
                mensaje: "Estado actualizado correctamente",
                estado_negocio
            });

        } catch (error) {
            console.error("Error actualizando estado:", error);
            res.status(500).json({
                ok: false,
                error: "No se pudo actualizar el estado"
            });
        }
    }
);


// ======================================================
// ENDPOINT PARA REPORTES DE VENTAS POR ASESOR
// ======================================================

app.get(
    "/api/reportes/ventas",
    authenticateToken,
    requireSupervisor,
    async (req, res) => {
        try {
            const [ventas] = await pool.query(`
                SELECT 
                    a.id as asesor_id,
                    a.nombre as asesor_nombre,
                    COUNT(CASE WHEN c.estado_negocio = 'venta' THEN 1 END) as ventas,
                    COUNT(CASE WHEN c.estado_negocio = 'volver_a_llamar' THEN 1 END) as pendientes,
                    COUNT(CASE WHEN c.estado_negocio = 'no_desea' THEN 1 END) as no_desean,
                    COUNT(CASE WHEN c.estado_negocio = 'sin_cobertura' THEN 1 END) as sin_cobertura,
                    COUNT(CASE WHEN c.estado_negocio = 'cliente_potencial' THEN 1 END) as potenciales,
                    COUNT(*) as total_conversaciones
                FROM asesores a
                LEFT JOIN conversaciones c ON a.id = c.asesor_id
                GROUP BY a.id, a.nombre
                ORDER BY ventas DESC
            `);

            res.json({ ok: true, ventas });

        } catch (error) {
            console.error("Error en reporte de ventas:", error);
            res.status(500).json({
                ok: false,
                error: "No se pudo generar el reporte"
            });
        }
    }
);


// ======================================================
// ENDPOINT PARA IMPORTAR CONVERSACIONES DE WHATSAPP BUSINESS
// ======================================================

app.post("/api/importar/whatsapp", authenticateToken, requireSuperAdmin, async (req, res) => {
    try {
        const { datos, campaña_id } = req.body;

        if (!datos || !Array.isArray(datos)) {
            return res.status(400).json({
                ok: false,
                error: "Datos inválidos. Se espera un array de mensajes."
            });
        }

        if (!campaña_id) {
            return res.status(400).json({
                ok: false,
                error: "Se requiere especificar una campaña de WhatsApp."
            });
        }

        // Obtener información de la campaña
        const [campañas] = await pool.query(
            'SELECT * FROM campañas_whatsapp WHERE id = ?',
            [campaña_id]
        );

        if (campañas.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Campaña no encontrada."
            });
        }

        const campaña = campañas[0];
        let conversacionesCreadas = 0;
        let mensajesImportados = 0;
        let errores = [];

        // Agrupar mensajes por número de teléfono
        const conversacionesPorTelefono = {};

        for (const mensaje of datos) {
            try {
                // Formato de exportación de WhatsApp Business puede variar
                // Soportamos formatos comunes
                const telefono = mensaje.from || mensaje.phone_number || mensaje.from_phone;
                const contenido = mensaje.text?.body || mensaje.content || mensaje.message || '';
                const timestamp = mensaje.timestamp || mensaje.created_at || mensaje.date;
                const tipo = mensaje.type || 'text';
                const esEntrante = mensaje.direction === 'inbound' || mensaje.from !== campaña.numero_whatsapp;

                if (!telefono) continue;

                if (!conversacionesPorTelefono[telefono]) {
                    conversacionesPorTelefono[telefono] = {
                        telefono,
                        mensajes: []
                    };
                }

                conversacionesPorTelefono[telefono].mensajes.push({
                    contenido,
                    timestamp,
                    tipo,
                    esEntrante
                });

            } catch (error) {
                errores.push(`Error procesando mensaje: ${error.message}`);
            }
        }

        // Crear conversaciones y mensajes
        for (const [telefono, data] of Object.entries(conversacionesPorTelefono)) {
            try {
                const connection = await pool.getConnection();
                
                try {
                    await connection.beginTransaction();

                    // Verificar si ya existe conversación con este número
                    const [existente] = await connection.query(
                        `SELECT id FROM conversaciones
                         WHERE telefono_whatsapp = ?
                         AND campaña_whatsapp_id = ?
                         LIMIT 1`,
                        [telefono, campaña_id]
                    );

                    let conversacionId;

                    if (existente.length > 0) {
                        conversacionId = existente[0].id;
                    } else {
                        // Crear nueva conversación
                        const [result] = await connection.query(
                            `INSERT INTO conversaciones
                             (cliente_panel_id, campaña_whatsapp_id, telefono_whatsapp, estado_conversacion, fecha_inicio, fecha_ultimo_mensaje)
                             VALUES (?, ?, ?, 'finalizada', NOW(), NOW())`,
                            [campaña.cliente_panel_id, campaña_id, telefono]
                        );
                        conversacionId = result.insertId;
                        conversacionesCreadas++;
                    }

                    // Insertar mensajes
                    for (const msg of data.mensajes) {
                        const fecha = msg.timestamp ? new Date(msg.timestamp) : new Date();
                        const remitente = msg.esEntrante ? 'cliente' : 'asesor';

                        await connection.query(
                            `INSERT INTO mensajes
                             (conversacion_id, remitente, contenido, tipo_mensaje, fecha_mensaje)
                             VALUES (?, ?, ?, ?, ?)`,
                            [conversacionId, remitente, msg.contenido, msg.tipo, fecha]
                        );
                        mensajesImportados++;
                    }

                    await connection.commit();

                } catch (error) {
                    await connection.rollback();
                    throw error;
                } finally {
                    connection.release();
                }

            } catch (error) {
                errores.push(`Error en conversación ${telefono}: ${error.message}`);
            }
        }

        res.json({
            ok: true,
            conversaciones_creadas: conversacionesCreadas,
            mensajes_importados: mensajesImportados,
            errores: errores.length > 0 ? errores : undefined
        });

    } catch (error) {
        console.error("Error importando conversaciones:", error);
        res.status(500).json({
            ok: false,
            error: "Error interno del servidor"
        });
    }
});


// ======================================================
// ENDPOINT SSE PARA ACTUALIZACIONES EN TIEMPO REAL
// ======================================================

app.get("/api/panel/sse", authenticateToken, (req, res) => {
    const headers = {
        "Content-Type": "text/event-stream",
        "Cache-Control": "no-cache",
        "Connection": "keep-alive",
        "X-Accel-Buffering": "no"
    };

    res.writeHead(200, headers);

    // Almacenar información del usuario con la conexión
    const clientData = {
        response: res,
        userId: req.user.asesorId,
        role: req.user.rol
    };

    agregarCliente(clientData);

    res.write("event: connected\ndata: {\"message\":\"Conectado a actualizaciones en tiempo real\"}\n\n");

    req.on("close", () => {
        eliminarCliente(clientData);
    });
});


// ======================================================
// CHAT PRINCIPAL
// ======================================================

app.post("/api/chat", async (req, res) => {

    try {

        const { mensaje, telefono } = req.body;

        // --------------------------------------------------
        // VALIDAR DATOS
        // --------------------------------------------------

        if (!mensaje || !telefono) {

            return res.status(400).json({
                error: "Debes enviar mensaje y telefono"
            });

        }


        // --------------------------------------------------
        // OBTENER / CREAR CLIENTE
        // --------------------------------------------------

        const cliente = await obtenerOCrearCliente(telefono);


        // --------------------------------------------------
        // OBTENER / CREAR CONVERSACIÓN
        // --------------------------------------------------

        const conversacion =
            await obtenerOCrearConversacion(cliente.id);


        // --------------------------------------------------
        // GUARDAR MENSAJE DEL CLIENTE
        // --------------------------------------------------

        await guardarMensaje(
            conversacion.id,
            "cliente",
            mensaje
        );


        // --------------------------------------------------
        // SI EL BOT ESTÁ DESACTIVADO
        // --------------------------------------------------

        if (!conversacion.bot_activo) {

            let mensaje = "";

            if (conversacion.estado_conversacion === "esperando_asesor") {
                mensaje = "Tu solicitud está registrada y te atenderemos en cuanto un asesor quede disponible.";
            } else if (conversacion.estado_conversacion === "atendida") {
                mensaje = "Tu conversación está asignada a un asesor. En breve continuará la atención.";
            } else {
                mensaje = "La conversación está siendo atendida por un asesor.";
            }

            return res.json({

                conversacion_id: conversacion.id,

                cliente_id: cliente.id,

                estado: conversacion.estado,
                bot_activo: false,

                mensaje: mensaje

            });

        }


        // --------------------------------------------------
        // GUARDAR DATOS DEL CLIENTE SEGÚN EL ESTADO ACTUAL
        // --------------------------------------------------

        if (conversacion.estado === "distrito") {

            await actualizarDatosCliente(
                cliente.id,
                {
                    distrito: mensaje.trim()
                }
            );

        }


        if (conversacion.estado === "direccion") {

            await actualizarDatosCliente(
                cliente.id,
                {
                    direccion: mensaje.trim()
                }
            );

        }


        if (conversacion.estado === "nombre") {

            await actualizarDatosCliente(
                cliente.id,
                {
                    nombre: mensaje.trim()
                }
            );

        }


        // --------------------------------------------------
        // PROCESAR MENSAJE CON EL MOTOR DEL BOT
        // --------------------------------------------------

        const resultado = procesarMensaje(
            mensaje,
            conversacion.estado
        );


        // ==================================================
        // TRANSFERENCIA A ASESOR
        // ==================================================

     
        // ==================================================
        // TRANSFERENCIA A ASESOR
        // ==================================================

        if (
            conversacion.estado === "menu" &&
            (
                mensaje.trim() === "5" ||
                mensaje.trim().toLowerCase() === "asesor"
            )
        ) {
            const asesor = await obtenerAsesorDisponible();

            let asignacionCorrecta = false;

            if (asesor) {
                asignacionCorrecta = await asignarAsesor(
                    conversacion.id,
                    asesor.id
                );
            }

            if (asignacionCorrecta) {
                resultado.estado = "asesor";

                resultado.respuesta =
                    `👨‍💼 ¡Perfecto!

Te estamos conectando con un asesor comercial.

👤 Tu solicitud ha sido asignada a:
${asesor.nombre}

⏳ En unos momentos continuará la atención contigo.`;
            } else {
                await agregarACola(conversacion.id);

                resultado.estado = "esperando_asesor";

                resultado.respuesta =
                    `👨‍💼 En este momento todos nuestros asesores están atendiendo otras conversaciones.

⏳ Hemos registrado tu solicitud y te atenderemos en cuanto un asesor quede disponible.

Gracias por tu paciencia. ❤️`;
            }
        }


        // --------------------------------------------------
        // ACTUALIZAR ESTADO DE LA CONVERSACIÓN
        // --------------------------------------------------

        await actualizarEstadoConversacion(
            conversacion.id,
            resultado.estado
        );


        // --------------------------------------------------
        // GUARDAR RESPUESTA DEL BOT
        // --------------------------------------------------

        await guardarMensaje(
            conversacion.id,
            "bot",
            resultado.respuesta
        );


        // --------------------------------------------------
        // RESPUESTA AL CLIENTE
        // --------------------------------------------------

       
res.json({
    conversacion_id: conversacion.id,
    cliente_id: cliente.id,
    estado: resultado.estado,
    bot_activo: resultado.estado !== "asesor" &&
                resultado.estado !== "esperando_asesor",
    respuesta: resultado.respuesta
});



    } catch (error) {

        console.error(
            "❌ Error en /api/chat:",
            error
        );


        res.status(500).json({

            error:
                "Error interno del servidor"

        });

    }

});


// ======================================================
// CONSULTAR ASESOR DISPONIBLE
// ======================================================

app.get(
    "/api/asesor-disponible",
    async (req, res) => {

        try {

            const asesor =
                await obtenerAsesorDisponible();


            if (!asesor) {

                return res.json({

                    disponible: false,

                    mensaje:
                        "No hay asesores disponibles"

                });

            }


            res.json({

                disponible: true,

                asesor

            });


        } catch (error) {

            console.error(
                "❌ Error buscando asesor:",
                error
            );


            res.status(500).json({

                error:
                    "Error buscando asesor"

            });

        }

    }
);


// ======================================================
// FINALIZAR CONVERSACIÓN
// ======================================================


 // ======================================================
// CONSULTAR HISTORIAL DE MENSAJES
// ======================================================

app.get(
    "/api/conversaciones/:id/mensajes",
    authenticateToken,
    async (req, res) => {
        try {
            const conversacionId = Number(req.params.id);

            if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
                return res.status(400).json({
                    ok: false,
                    error: "ID de conversación inválido"
                });
            }

            // Verificar que la conversación exista y verificar permisos
            const [conversaciones] = await pool.query(
                `SELECT id, asesor_id
                 FROM conversaciones
                 WHERE id = ?
                 LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Si es asesor, verificar que la conversación esté asignada a él
            if (req.user.rol === 'asesor' && conversacion.asesor_id !== req.user.asesorId) {
                return res.status(403).json({
                    ok: false,
                    error: "No tienes permiso para ver esta conversación"
                });
            }

            // Obtener los mensajes cronológicamente.
            const mensajes = await obtenerHistorialMensajes(
                conversacionId
            );

            return res.json({
                ok: true,
                conversacion_id: conversacionId,
                total: mensajes.length,
                mensajes
            });

        } catch (error) {
            console.error(
                "Error consultando historial:",
                error
            );

            return res.status(500).json({
                ok: false,
                error: "No se pudo consultar el historial"
            });
        }
    }
);


/* ======================================================
   RESPONDER COMO ASESOR
====================================================== */

app.post(
    "/api/conversaciones/:id/responder",
    authenticateToken,
    async (req, res) => {
        try {
            const conversacionId = Number(req.params.id);
            const mensaje = typeof req.body.mensaje === "string"
                ? req.body.mensaje.trim()
                : "";

            // Validar ID.
            if (
                !Number.isInteger(conversacionId) ||
                conversacionId <= 0
            ) {
                return res.status(400).json({
                    ok: false,
                    error: "ID de conversación inválido"
                });
            }

            // Validar contenido.
            if (!mensaje) {
                return res.status(400).json({
                    ok: false,
                    error: "El mensaje no puede estar vacío"
                });
            }

            // Consultar la conversación con datos de WhatsApp.
            const [conversaciones] = await pool.query(
                `SELECT id, estado_conversacion, asesor_id, campaña_whatsapp_id, telefono_whatsapp
                 FROM conversaciones
                 WHERE id = ?
                 LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Si es asesor, verificar que la conversación esté asignada a él
            if (req.user.rol === 'asesor' && conversacion.asesor_id !== req.user.asesorId) {
                return res.status(403).json({
                    ok: false,
                    error: "No tienes permiso para responder esta conversación"
                });
            }

            // Impedir respuestas en conversaciones cerradas.
            if (
                conversacion.estado_conversacion === "finalizada"
            ) {
                return res.status(409).json({
                    ok: false,
                    error: "La conversación ya está finalizada"
                });
            }

            // Solo permitir responder si tiene asesor asignado.
            if (
                conversacion.estado_conversacion !== "atendida" ||
                !conversacion.asesor_id
            ) {
                return res.status(409).json({
                    ok: false,
                    error: "La conversación todavía no tiene un asesor asignado"
                });
            }

            // Guardar la respuesta en el historial.
            const origenMensaje = req.body.origen_mensaje || 'sistema'; // 'sistema' o 'externo'
            await guardarMensaje(
                conversacionId,
                "asesor",
                mensaje,
                origenMensaje
            );

            // Enviar mensaje por WhatsApp si está configurado
            if (conversacion.campaña_whatsapp_id && conversacion.telefono_whatsapp) {
                try {
                    const campaign = await whatsappService.getCampaignById(conversacion.campaña_whatsapp_id);
                    if (campaign && campaign.activo) {
                        const whatsappResult = await whatsappService.sendMessage(
                            conversacion.telefono_whatsapp,
                            mensaje,
                            campaign
                        );
                        if (!whatsappResult.ok) {
                            console.error('Error enviando mensaje WhatsApp:', whatsappResult.error);
                        }
                    }
                } catch (error) {
                    console.error('Error en integración WhatsApp:', error);
                    // No fallar el endpoint si WhatsApp falla
                }
            }

            // Calcular tiempo de primera respuesta si es la primera respuesta del asesor
            if (conversacion.fecha_asignacion && !conversacion.tiempo_primera_respuesta) {
                const tiempoRespuesta = Math.floor(
                    (Date.now() - new Date(conversacion.fecha_asignacion).getTime()) / 1000
                );

                await pool.query(
                    `UPDATE conversaciones
                     SET tiempo_primera_respuesta = ?
                     WHERE id = ?`,
                    [tiempoRespuesta, conversacionId]
                );

                // Registrar actividad de primera respuesta
                await registrarActividad(
                    conversacionId,
                    'respuesta',
                    req.user.asesorId,
                    `Primera respuesta en ${tiempoRespuesta} segundos`
                );
            } else {
                // Registrar actividad de respuesta normal
                await registrarActividad(
                    conversacionId,
                    'respuesta',
                    req.user.asesorId,
                    'Respuesta enviada al cliente'
                );
            }

            return res.json({
                ok: true,
                mensaje: "Respuesta guardada correctamente",
                conversacion_id: conversacionId,
                remitente: "asesor",
                respuesta: mensaje
            });

        } catch (error) {
            console.error(
                "Error enviando respuesta del asesor:",
                error
            );

            return res.status(500).json({
                ok: false,
                error: "No se pudo guardar la respuesta"
            });
        }
    }
);


/* ======================================================
   REGISTRAR MENSAJE EXTERNO (Asesor respondió desde WhatsApp Web/Celular)
====================================================== */

app.post(
    "/api/conversaciones/:id/mensaje-externo",
    authenticateToken,
    async (req, res) => {
        try {
            const conversacionId = Number(req.params.id);
            const { mensaje } = req.body;

            // Validar ID
            if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
                return res.status(400).json({
                    ok: false,
                    error: "ID de conversación inválido"
                });
            }

            // Validar contenido
            if (!mensaje || typeof mensaje !== "string" || !mensaje.trim()) {
                return res.status(400).json({
                    ok: false,
                    error: "El mensaje no puede estar vacío"
                });
            }

            // Consultar la conversación
            const [conversaciones] = await pool.query(
                `SELECT id, asesor_id, estado_conversacion
                 FROM conversaciones
                 WHERE id = ?
                 LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Verificar permisos (asesor solo puede registrar sus propias conversaciones)
            if (req.user.rol === 'asesor' && conversacion.asesor_id !== req.user.asesorId) {
                return res.status(403).json({
                    ok: false,
                    error: "No tienes permiso para registrar mensajes en esta conversación"
                });
            }

            // Guardar el mensaje como externo
            await guardarMensaje(
                conversacionId,
                "asesor",
                mensaje.trim(),
                "externo" // Origen externo (WhatsApp Web/Celular)
            );

            // Registrar actividad
            await registrarActividad(
                conversacionId,
                'mensaje_externo',
                req.user.asesorId,
                'Asesor registró mensaje enviado desde fuera del sistema'
            );

            return res.json({
                ok: true,
                mensaje: "Mensaje externo registrado correctamente",
                conversacion_id: conversacionId,
                advertencia: "Este mensaje no fue enviado por el sistema. El asesor respondió desde WhatsApp Web/Celular."
            });

        } catch (error) {
            console.error("Error registrando mensaje externo:", error);
            return res.status(500).json({
                ok: false,
                error: "No se pudo registrar el mensaje externo"
            });
        }
    }
);


app.post(
    "/api/conversaciones/:id/finalizar",
    authenticateToken,
    async (req, res) => {
        try {
            const conversacionId = Number(req.params.id);

            if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
                return res.status(400).json({
                    error: "ID de conversación inválido"
                });
            }

            const [conversaciones] = await pool.query(
                `SELECT *
                 FROM conversaciones
                 WHERE id = ?`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                return res.status(404).json({
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Si es asesor, verificar que la conversación esté asignada a él
            if (req.user.rol === 'asesor' && conversacion.asesor_id !== req.user.asesorId) {
                return res.status(403).json({
                    ok: false,
                    error: "No tienes permiso para finalizar esta conversación"
                });
            }

            if (conversacion.estado_conversacion === "finalizada") {
                return res.json({
                    ok: true,
                    mensaje: "La conversación ya estaba finalizada",
                    asesor_liberado: conversacion.asesor_id,
                    siguiente_asignacion: null
                });
            }

            // Finalizar la conversación actual.
            await pool.query(
                `UPDATE conversaciones
                 SET estado_conversacion = 'finalizada',
                     bot_activo = FALSE,
                     fecha_finalizacion = CURRENT_TIMESTAMP
                 WHERE id = ?`,
                [conversacionId]
            );

            // Registrar actividad
            await registrarActividad(
                conversacionId,
                'finalizacion',
                conversacion.asesor_id,
                `Conversación finalizada por asesor ${conversacion.asesor_id}`
            );

            // Retirar de la cola si todavía estaba esperando.
            await pool.query(
                `UPDATE cola_atencion
                 SET estado = 'cancelada'
                 WHERE conversacion_id = ?
                 AND estado = 'esperando'`,
                [conversacionId]
            );

            // Liberar al asesor que atendía esta conversación.
            if (conversacion.asesor_id) {
                await liberarAsesor(conversacion.asesor_id);
            }

            // Si hay clientes esperando, intentar asignar el siguiente.
            const siguienteAsignacion = await asignarSiguienteDeCola();

            return res.json({
                ok: true,
                mensaje: "Conversación finalizada",
                asesor_liberado: conversacion.asesor_id,
                siguiente_asignacion: siguienteAsignacion
            });

        } catch (error) {
            console.error("❌ Error finalizando conversación:", error);

            return res.status(500).json({
                error: "Error interno del servidor"
            });
        }
    }
);


// ======================================================
// ENDPOINT PARA QUE SUPERVISOR TOME UN CHAT DE LA COLA
// ======================================================

app.post("/api/conversaciones/:id/tomar", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const conversacionId = Number(req.params.id);
        const supervisorId = req.user.asesorId || req.user.id;

        if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de conversación inválido"
            });
        }

        const connection = await pool.getConnection();
        try {
            await connection.beginTransaction();

            // Verificar que la conversación existe y está en cola
            const [conversaciones] = await connection.query(
                `SELECT * FROM conversaciones WHERE id = ? LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                await connection.rollback();
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            // Asignar al supervisor
            await connection.query(
                `UPDATE conversaciones
                 SET asesor_id = ?, fecha_asignacion = NOW(), estado_conversacion = 'atendida'
                 WHERE id = ?`,
                [supervisorId, conversacionId]
            );

            // Actualizar estado en cola
            await connection.query(
                `UPDATE cola_atencion
                 SET estado = 'atendida', fecha_atencion = NOW()
                 WHERE conversacion_id = ? AND estado = 'esperando'`,
                [conversacionId]
            );

            // Registrar actividad
            await connection.query(
                `INSERT INTO historial_actividad
                 (conversacion_id, asesor_id, tipo_accion, descripcion)
                 VALUES (?, ?, 'asignacion', ?)`,
                [conversacionId, supervisorId, `Supervisor tomó conversación ${conversacionId}`]
            );

            await connection.commit();

            res.json({
                ok: true,
                mensaje: "Conversación tomada por supervisor",
                conversacion_id: conversacionId,
                asesor_id: supervisorId
            });
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    } catch (error) {
        console.error("Error tomando conversación:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudo tomar la conversación"
        });
    }
});


// ======================================================
// ENDPOINT PARA REASIGNAR CONVERSACIÓN A OTRO ASESOR
// ======================================================

app.put("/api/conversaciones/:id/reasignar", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const conversacionId = Number(req.params.id);
        const { nuevo_asesor_id } = req.body;

        if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de conversación inválido"
            });
        }

        if (!nuevo_asesor_id || !Number.isInteger(nuevo_asesor_id)) {
            return res.status(400).json({
                ok: false,
                error: "ID de asesor inválido"
            });
        }

        const connection = await pool.getConnection();
        try {
            await connection.beginTransaction();

            // Verificar que la conversación existe
            const [conversaciones] = await connection.query(
                `SELECT * FROM conversaciones WHERE id = ? LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                await connection.rollback();
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];
            const asesorAnterior = conversacion.asesor_id;

            // Verificar que el nuevo asesor existe
            const [asesores] = await connection.query(
                `SELECT * FROM asesores WHERE id = ? LIMIT 1`,
                [nuevo_asesor_id]
            );

            if (asesores.length === 0) {
                await connection.rollback();
                return res.status(404).json({
                    ok: false,
                    error: "Asesor no encontrado"
                });
            }

            // Reasignar conversación
            await connection.query(
                `UPDATE conversaciones
                 SET asesor_id = ?, fecha_asignacion = NOW()
                 WHERE id = ?`,
                [nuevo_asesor_id, conversacionId]
            );

            // Registrar actividad
            await connection.query(
                `INSERT INTO historial_actividad
                 (conversacion_id, asesor_id, tipo_accion, descripcion)
                 VALUES (?, ?, 'reasignacion', ?)`,
                [conversacionId, nuevo_asesor_id, `Reasignada de asesor ${asesorAnterior} a asesor ${nuevo_asesor_id}`]
            );

            await connection.commit();

            res.json({
                ok: true,
                mensaje: "Conversación reasignada",
                conversacion_id: conversacionId,
                asesor_anterior: asesorAnterior,
                asesor_nuevo: nuevo_asesor_id
            });
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    } catch (error) {
        console.error("Error reasignando conversación:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudo reasignar la conversación"
        });
    }
});



// ======================================================
// CONEXIÓN A MYSQL
// ======================================================


 // ======================================================
// API DEL PANEL: RESUMEN GENERAL (SOLO SUPERVISOR)
// ======================================================

app.get("/api/panel/resumen", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const [
            [conversaciones],
            [cola],
            [clientes],
            [asesores],
            [recientes],
            [espera],
            [clientesPanel]
        ] = await Promise.all([
            pool.query(`
                SELECT COUNT(*) AS total
                FROM conversaciones
                WHERE estado_conversacion IN ('activa', 'atendida')
            `),

            pool.query(`
                SELECT COUNT(*) AS total
                FROM cola_atencion
                WHERE estado = 'esperando'
            `),

            pool.query(`
                SELECT COUNT(*) AS total
                FROM clientes
            `),

            pool.query(`
                SELECT
                    a.id,
                    a.nombre,
                    a.telefono,
                    a.estado,
                    COUNT(
                        CASE
                            WHEN c.estado_conversacion = 'atendida'
                            THEN 1
                        END
                    ) AS conversaciones_activas
                FROM asesores a
                LEFT JOIN conversaciones c
                    ON c.asesor_id = a.id
                GROUP BY
                    a.id,
                    a.nombre,
                    a.telefono,
                    a.estado
                ORDER BY a.id
            `),

            pool.query(`
                SELECT
                    c.id AS conversacion_id,
               
COALESCE(
    NULLIF(TRIM(cl.nombre), ''),
    CONCAT('Cliente ', c.telefono_whatsapp)
) AS cliente,

                    c.telefono_whatsapp AS telefono,
                    c.estado,
                    c.estado_conversacion,
                    c.asesor_id,
                    a.nombre AS asesor,
                    c.fecha_actualizacion
                FROM conversaciones c
                INNER JOIN clientes cl
                    ON cl.id = c.cliente_id
                LEFT JOIN asesores a
                    ON a.id = c.asesor_id
                ORDER BY c.id DESC
                LIMIT 10
            `),

            pool.query(`
                SELECT
                    ca.id AS cola_id,
                    ca.conversacion_id,
                    ca.prioridad,
                    ca.fecha_ingreso,
                    cl.nombre AS cliente,
                    cl.telefono
                FROM cola_atencion ca
                INNER JOIN conversaciones c
                    ON c.id = ca.conversacion_id
                INNER JOIN clientes cl
                    ON cl.id = c.cliente_id
                WHERE ca.estado = 'esperando'
                ORDER BY
                    ca.prioridad DESC,
                    ca.fecha_ingreso ASC,
                    ca.id ASC
            `),

            pool.query(`
                SELECT * FROM clientes_panel WHERE activo = TRUE ORDER BY nombre
            `)
        ]);

        res.json({
            ok: true,
            resumen: {
                conversaciones_activas: conversaciones[0].total,
                en_cola: cola[0].total,
                clientes_registrados: clientes[0].total,
                asesores_disponibles: asesores.filter(
                    asesor => asesor.estado === "disponible"
                ).length
            },
            asesores,
            conversaciones: recientes,
            cola: espera,
            clientes_panel: clientesPanel
        });

    } catch (error) {
        console.error("Error obteniendo resumen del panel:", error);

        res.status(500).json({
            ok: false,
            error: "No se pudo obtener el resumen del panel"
        });
    }
});


// ======================================================
// API DEL ASESOR: OBTENER SUS CONVERSACIONES ASIGNADAS
// ======================================================

app.get("/api/asesor/:id/conversaciones", authenticateToken, requireAsesorOrSelf, async (req, res) => {
    try {
        const asesorId = Number(req.params.id);

        if (!Number.isInteger(asesorId) || asesorId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de asesor inválido"
            });
        }

        // Verificar que el asesor existe
        const [asesores] = await pool.query(
            `SELECT id, nombre, estado
             FROM asesores
             WHERE id = ?
             LIMIT 1`,
            [asesorId]
        );

        if (asesores.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Asesor no encontrado"
            });
        }

        // Obtener conversaciones asignadas al asesor (activas y atendidas)
        const [conversaciones] = await pool.query(`
            SELECT
                c.id AS conversacion_id,
                COALESCE(
                    NULLIF(TRIM(cl.nombre), ''),
                    CONCAT('Cliente ', c.telefono_whatsapp)
                ) AS cliente,
                c.telefono_whatsapp AS telefono,
                cl.nombre AS nombre_completo,
                cl.distrito,
                c.estado,
                c.estado_conversacion,
                c.fecha_inicio,
                c.fecha_actualizacion,
                (
                    SELECT mensaje
                    FROM mensajes m
                    WHERE m.conversacion_id = c.id
                    ORDER BY m.fecha DESC
                    LIMIT 1
                ) AS ultimo_mensaje,
                (
                    SELECT fecha
                    FROM mensajes m
                    WHERE m.conversacion_id = c.id
                    ORDER BY m.fecha DESC
                    LIMIT 1
                ) AS fecha_ultimo_mensaje,
                (
                    SELECT COUNT(*)
                    FROM mensajes m
                    WHERE m.conversacion_id = c.id
                    AND m.remitente = 'cliente'
                    AND m.fecha > (
                        SELECT MAX(fecha)
                        FROM mensajes
                        WHERE conversacion_id = c.id
                        AND remitente = 'asesor'
                    )
                ) AS mensajes_pendientes
            FROM conversaciones c
            INNER JOIN clientes cl
                ON cl.id = c.cliente_id
            WHERE c.asesor_id = ?
            AND c.estado_conversacion IN ('atendida', 'activa')
            ORDER BY c.fecha_actualizacion DESC
        `, [asesorId]);

        res.json({
            ok: true,
            asesor: asesores[0],
            total: conversaciones.length,
            conversaciones
        });

    } catch (error) {
        console.error("Error obteniendo conversaciones del asesor:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudieron obtener las conversaciones"
        });
    }
});


// ======================================================
// API DEL ASESOR: CAMBIAR ESTADO DE DISPONIBILIDAD
// ======================================================

app.put("/api/asesor/:id/estado", authenticateToken, requireAsesorOrSelf, async (req, res) => {
    try {
        const asesorId = Number(req.params.id);
        const { estado } = req.body;

        if (!Number.isInteger(asesorId) || asesorId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de asesor inválido"
            });
        }

        if (!['disponible', 'ocupado', 'desconectado'].includes(estado)) {
            return res.status(400).json({
                ok: false,
                error: "Estado inválido. Debe ser: disponible, ocupado o desconectado"
            });
        }

        // Verificar que el asesor existe
        const [asesores] = await pool.query(
            `SELECT id, estado AS estado_actual
             FROM asesores
             WHERE id = ?
             LIMIT 1`,
            [asesorId]
        );

        if (asesores.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Asesor no encontrado"
            });
        }

        // Validación: si el asesor tiene conversaciones activas, no puede ponerse en desconectado
        if (estado === 'desconectado') {
            const [activas] = await pool.query(
                `SELECT COUNT(*) AS total
                 FROM conversaciones
                 WHERE asesor_id = ?
                 AND estado_conversacion = 'atendida'`,
                [asesorId]
            );

            if (activas[0].total > 0) {
                return res.status(409).json({
                    ok: false,
                    error: "No puedes desconectarte mientras tienes conversaciones activas. Finaliza las conversaciones primero."
                });
            }
        }

        // Actualizar estado
        await pool.query(
            `UPDATE asesores
             SET estado = ?
             WHERE id = ?`,
            [estado, asesorId]
        );

        // Enviar evento SSE
        enviarEventoATodos("asesor_estado_cambiado", {
            asesor_id: asesorId,
            estado_anterior: asesores[0].estado_actual,
            estado_nuevo: estado
        });

        res.json({
            ok: true,
            mensaje: "Estado actualizado correctamente",
            asesor_id: asesorId,
            estado: estado
        });

    } catch (error) {
        console.error("Error cambiando estado del asesor:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudo actualizar el estado"
        });
    }
});


// ======================================================
// API DEL ASESOR: OBTENER DATOS COMPLETOS DEL CLIENTE
// ======================================================

app.get("/api/clientes/:telefono", authenticateToken, async (req, res) => {
    try {
        const telefono = req.params.telefono;

        if (!telefono) {
            return res.status(400).json({
                ok: false,
                error: "El teléfono es requerido"
            });
        }

        const [clientes] = await pool.query(
            `SELECT
                id,
                nombre,
                telefono,
                distrito,
                direccion,
                origen,
                campania,
                fecha_registro
             FROM clientes
             WHERE telefono = ?
             LIMIT 1`,
            [telefono]
        );

        if (clientes.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Cliente no encontrado"
            });
        }

        res.json({
            ok: true,
            cliente: clientes[0]
        });

    } catch (error) {
        console.error("Error obteniendo datos del cliente:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudieron obtener los datos del cliente"
        });
    }
});


// ======================================================
// API DEL ASESOR: ACTUALIZAR DATOS DEL CLIENTE
// ======================================================

app.put("/api/clientes/:telefono", async (req, res) => {
    try {
        const telefono = req.params.telefono;
        const datos = req.body;

        if (!telefono) {
            return res.status(400).json({
                ok: false,
                error: "El teléfono es requerido"
            });
        }

        // Verificar que el cliente existe
        const [clientes] = await pool.query(
            `SELECT id FROM clientes WHERE telefono = ? LIMIT 1`,
            [telefono]
        );

        if (clientes.length === 0) {
            return res.status(404).json({
                ok: false,
                error: "Cliente no encontrado"
            });
        }

        // Actualizar datos usando el servicio existente
        await actualizarDatosCliente(clientes[0].id, datos);

        res.json({
            ok: true,
            mensaje: "Datos del cliente actualizados correctamente"
        });

    } catch (error) {
        console.error("Error actualizando datos del cliente:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudieron actualizar los datos del cliente"
        });
    }
});


// ======================================================
// API DEL SUPERVISOR: REPORTES COMERCIALES
// ======================================================

app.get("/api/reportes/comerciales", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const { fecha_inicio, fecha_fin, campania } = req.query;

        let whereClause = "WHERE c.estado_conversacion = 'finalizada'";
        const params = [];

        if (fecha_inicio) {
            whereClause += " AND c.fecha_inicio >= ?";
            params.push(fecha_inicio);
        }

        if (fecha_fin) {
            whereClause += " AND c.fecha_inicio <= ?";
            params.push(fecha_fin);
        }

        if (campania) {
            whereClause += " AND cl.campania = ?";
            params.push(campania);
        }

        const [reporte] = await pool.query(`
            SELECT
                COUNT(*) AS total_conversaciones,
                COUNT(DISTINCT cl.id) AS total_clientes,
                AVG(c.tiempo_primera_respuesta) AS promedio_primera_respuesta,
                SUM(CASE WHEN c.tiempo_primera_respuesta <= 60 THEN 1 ELSE 0 END) AS respuestas_rapidas,
                COUNT(*) AS total_respuestas
            FROM conversaciones c
            INNER JOIN clientes cl
                ON cl.id = c.cliente_id
            ${whereClause}
        `, params);

        const [por_campania] = await pool.query(`
            SELECT
                cl.campania,
                COUNT(*) AS conversaciones,
                COUNT(DISTINCT cl.id) AS clientes
            FROM conversaciones c
            INNER JOIN clientes cl
                ON cl.id = c.cliente_id
            ${whereClause}
            GROUP BY cl.campania
            ORDER BY conversaciones DESC
        `, params);

        const [por_asesor] = await pool.query(`
            SELECT
                a.id,
                a.nombre,
                COUNT(c.id) AS conversaciones_atendidas,
                AVG(c.tiempo_primera_respuesta) AS promedio_respuesta
            FROM asesores a
            LEFT JOIN conversaciones c
                ON c.asesor_id = a.id
                AND c.estado_conversacion = 'finalizada'
            ${whereClause.replace('WHERE c.', 'AND c.')}
            GROUP BY a.id, a.nombre
            ORDER BY conversaciones_atendidas DESC
        `, params);

        res.json({
            ok: true,
            resumen: reporte[0],
            por_campania,
            por_asesor
        });

    } catch (error) {
        console.error("Error generando reportes:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudieron generar los reportes"
        });
    }
});


// ======================================================
// API DEL SUPERVISOR: HISTORIAL DE ACTIVIDAD
// ======================================================

app.get("/api/conversaciones/:id/historial", authenticateToken, async (req, res) => {
    try {
        const conversacionId = Number(req.params.id);

        if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de conversación inválido"
            });
        }

        const historial = await obtenerHistorialActividad(conversacionId);

        res.json({
            ok: true,
            conversacion_id: conversacionId,
            total: historial.length,
            historial
        });

    } catch (error) {
        console.error("Error obteniendo historial:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudo obtener el historial"
        });
    }
});


// ======================================================
// API DEL SUPERVISOR: TRANSFERIR CONVERSACIÓN
// ======================================================

app.post("/api/conversaciones/:id/transferir", authenticateToken, requireSupervisor, async (req, res) => {
    try {
        const conversacionId = Number(req.params.id);
        const { asesor_destino_id } = req.body;

        if (!Number.isInteger(conversacionId) || conversacionId <= 0) {
            return res.status(400).json({
                ok: false,
                error: "ID de conversación inválido"
            });
        }

        if (!asesor_destino_id) {
            return res.status(400).json({
                ok: false,
                error: "ID del asesor destino es requerido"
            });
        }

        const connection = await pool.getConnection();

        try {
            await connection.beginTransaction();

            // Obtener conversación actual
            const [conversaciones] = await connection.query(
                `SELECT id, asesor_id, estado_conversacion
                 FROM conversaciones
                 WHERE id = ?
                 LIMIT 1`,
                [conversacionId]
            );

            if (conversaciones.length === 0) {
                await connection.rollback();
                return res.status(404).json({
                    ok: false,
                    error: "Conversación no encontrada"
                });
            }

            const conversacion = conversaciones[0];

            if (conversacion.estado_conversacion === 'finalizada') {
                await connection.rollback();
                return res.status(409).json({
                    ok: false,
                    error: "No se puede transferir una conversación finalizada"
                });
            }

            // Verificar que el asesor destino existe y está disponible
            const [asesores] = await connection.query(
                `SELECT id, nombre, estado
                 FROM asesores
                 WHERE id = ?
                 LIMIT 1`,
                [asesor_destino_id]
            );

            if (asesores.length === 0) {
                await connection.rollback();
                return res.status(404).json({
                    ok: false,
                    error: "Asesor destino no encontrado"
                });
            }

            const asesorDestino = asesores[0];

            if (asesorDestino.estado !== 'disponible') {
                await connection.rollback();
                return res.status(409).json({
                    ok: false,
                    error: "El asesor destino no está disponible"
                });
            }

            // Liberar al asesor actual si existe
            if (conversacion.asesor_id) {
                await connection.query(
                    `UPDATE asesores
                     SET estado = 'disponible'
                     WHERE id = ?`,
                    [conversacion.asesor_id]
                );
            }

            // Asignar al nuevo asesor
            await connection.query(
                `UPDATE conversaciones
                 SET asesor_id = ?,
                     estado_conversacion = 'atendida',
                     fecha_asignacion = CURRENT_TIMESTAMP
                 WHERE id = ?`,
                [asesor_destino_id, conversacionId]
            );

            // Marcar al nuevo asesor como ocupado
            await connection.query(
                `UPDATE asesores
                 SET estado = 'ocupado'
                 WHERE id = ?`,
                [asesor_destino_id]
            );

            // Registrar reasignación
            await connection.query(
                `INSERT INTO historial_actividad
                 (conversacion_id, asesor_id, tipo_accion, descripcion)
                 VALUES (?, ?, 'reasignacion', ?)`,
                [conversacionId, asesor_destino_id, `Transferida por supervisor de asesor ${conversacion.asesor_id || 'ninguno'} a asesor ${asesor_destino_id}`]
            );

            await connection.commit();

            // Enviar eventos SSE
            enviarEventoATodos("conversacion_transferida", {
                conversacion_id: conversacionId,
                asesor_origen: conversacion.asesor_id,
                asesor_destino: asesor_destino_id
            });

            res.json({
                ok: true,
                mensaje: "Conversación transferida correctamente",
                conversacion_id: conversacionId,
                asesor_destino: asesorDestino.nombre
            });

        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }

    } catch (error) {
        console.error("Error transfiriendo conversación:", error);
        res.status(500).json({
            ok: false,
            error: "No se pudo transferir la conversación"
        });
    }
});


pool.getConnection()

    .then(connection => {

        console.log(
            "✅ Conectado a MySQL"
        );

        connection.release();

    })

    .catch(error => {

        console.error(
            "❌ Error conectando a MySQL:",
            error.message
        );

    });


// ======================================================
// INICIAR SERVIDOR
// ======================================================

const PORT =
    process.env.PORT || 3000;


// Solo iniciar el servidor si no estamos en modo de prueba
if (process.env.NODE_ENV !== 'test') {
    app.listen(
        PORT,
        () => {

            console.log(
                `Servidor ejecutándose en http://localhost:${PORT}`
            );

        }
    );
}

module.exports = app;