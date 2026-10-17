const axios = require('axios');
const pool = require('../config/database');
const { agregarACola, asignarSiguienteDeCola } = require('./advisorService');
const { enviarEventoATodos } = require('../utils/sseEmitter');

class WhatsAppService {
  constructor() {
    this.baseUrl = 'https://graph.facebook.com/v18.0';
  }

  // Obtener configuración de una campaña por número de WhatsApp
  async getCampaignByNumber(numero) {
    try {
      const [rows] = await pool.query(
        'SELECT * FROM campanas_whatsapp WHERE numero_whatsapp = ? AND activo = TRUE',
        [numero]
      );
      return rows[0] || null;
    } catch (error) {
      console.error('Error obteniendo campaña:', error);
      return null;
    }
  }

  // Obtener configuración de una campaña por Phone ID
  async getCampaignByPhoneId(phoneId) {
    try {
      const [rows] = await pool.query(
        'SELECT * FROM campanas_whatsapp WHERE phone_id = ? AND activo = TRUE',
        [phoneId]
      );
      return rows[0] || null;
    } catch (error) {
      console.error('Error obteniendo campaña por Phone ID:', error);
      return null;
    }
  }

  // Obtener configuración de una campaña por ID
  async getCampaignById(id) {
    try {
      const [rows] = await pool.query(
        'SELECT * FROM campanas_whatsapp WHERE id = ?',
        [id]
      );
      return rows[0] || null;
    } catch (error) {
      console.error('Error obteniendo campaña:', error);
      return null;
    }
  }

  // Validar webhook de WhatsApp (verificación de challenge)
  verifyWebhook(mode, token, challenge) {
    // Usar el token de verificación configurado en .env
    const VERIFY_TOKEN = process.env.WHATSAPP_WEBHOOK_VERIFY_TOKEN || 'loyaltel_webhook_2026';

    if (mode === 'subscribe' && token === VERIFY_TOKEN) {
      console.log('✅ Webhook verification successful');
      return challenge;
    } else {
      console.log('❌ Webhook verification failed');
      console.log('Expected token:', VERIFY_TOKEN);
      console.log('Received token:', token);
      return null;
    }
  }

  // Procesar mensaje entrante de WhatsApp
  async processWebhook(data) {
    try {
      const entry = data.entry?.[0];
      if (!entry) return { ok: false, error: 'No entry in webhook' };

      const changes = entry.changes?.[0];
      if (!changes) return { ok: false, error: 'No changes in webhook' };

      const value = changes.value;
      if (!value) return { ok: false, error: 'No value in changes' };

      // Mensaje entrante
      const message = value.messages?.[0];
      if (message) {
        return await this.processMessage(message, value.metadata, value.contacts);
      }

      // Estado de mensaje (delivery, read, etc.)
      const status = value.statuses?.[0];
      if (status) {
        return await this.processStatus(status);
      }

      return { ok: true, message: 'Webhook procesado sin mensajes' };
    } catch (error) {
      console.error('Error procesando webhook:', error);
      return { ok: false, error: error.message };
    }
  }

  // Procesar mensaje de texto
  async processMessage(message, metadata, contacts) {
    try {
      const from = message.from || message.from_user_id; // Número del cliente (puede ser from o from_user_id)
      const to = message.to; // Número de WhatsApp Business (puede no estar en pruebas)
      const timestamp = message.timestamp;
      const type = message.type;

      // Buscar la campaña por el número de destino primero
      let campaign = to ? await this.getCampaignByNumber(to) : null;

      // Si no se encuentra por número, intentar por Phone ID del metadata
      if (!campaign && metadata?.phone_number_id) {
        campaign = await this.getCampaignByPhoneId(metadata.phone_number_id);
      }

      if (!campaign) {
        return { ok: false, error: 'Campaña no encontrada para este número o Phone ID' };
      }

      // Extraer el número de teléfono real del contacto si está disponible
      let realPhoneNumber = from;
      if (contacts && contacts.length > 0) {
        const contact = contacts[0];
        if (contact.wa_id) {
          realPhoneNumber = contact.wa_id;
        } else if (contact.user_id) {
          // Fallback: usar user_id si no hay wa_id
          realPhoneNumber = contact.user_id;
          console.log('⚠️ Usando user_id como número de teléfono (no ideal):', realPhoneNumber);
        }
      }

      // Procesar según tipo de mensaje
      if (type === 'text') {
        const text = message.text.body;
        return await this.handleTextMessage(realPhoneNumber, to, text, campaign, timestamp, contacts);
      }

      // Otros tipos: image, audio, video, document, etc.
      // Por ahora solo soportamos texto
      return { ok: true, message: 'Tipo de mensaje no soportado aún' };
    } catch (error) {
      console.error('Error procesando mensaje:', error);
      return { ok: false, error: error.message };
    }
  }

  // Manejar mensaje de texto - crear o actualizar conversación
  async handleTextMessage(from, to, text, campaign, timestamp, contacts = []) {
    try {
      const connection = await pool.getConnection();

      try {
        await connection.beginTransaction();

        // Buscar si ya existe una conversación con este número
        const [existing] = await connection.query(
          `SELECT * FROM conversaciones
           WHERE telefono_whatsapp = ?
           AND campanas_whatsapp_id = ?
           AND estado_conversacion != 'finalizada'
           ORDER BY fecha_inicio DESC
           LIMIT 1`,
          [`+${from}`, campaign.id]
        );

        let conversationId;

        if (existing.length > 0) {
          // Actualizar conversación existente
          conversationId = existing[0].id;
          
          // Si ya tiene asesor asignado, mantener estado 'atendida', si no, 'activa'
          const estadoConversacion = existing[0].asesor_id ? 'atendida' : 'activa';
          
          await connection.query(
            `UPDATE conversaciones
             SET estado_conversacion = ?,
                 fecha_actualizacion = NOW()
             WHERE id = ?`,
            [estadoConversacion, conversationId]
          );
        } else {
          // Crear cliente primero si no existe
          const [clienteResult] = await connection.query(
            `INSERT INTO clientes (telefono, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)`,
            [`+${from}`, contacts[0]?.profile?.name || 'Cliente WhatsApp']
          );
          const clienteId = clienteResult.insertId;

          // Crear nueva conversación
          const [result] = await connection.query(
            `INSERT INTO conversaciones
             (cliente_id, cliente_panel_id, campanas_whatsapp_id, telefono_whatsapp, estado_conversacion, fecha_inicio, fecha_actualizacion)
             VALUES (?, ?, ?, ?, 'esperando_asesor', NOW(), NOW())`,
            [clienteId, campaign.cliente_panel_id, campaign.id, from]
          );
          conversationId = result.insertId;

          // Insertar mensaje para la nueva conversación
          await connection.query(
            `INSERT INTO mensajes
             (conversacion_id, remitente, mensaje, fecha, origen_mensaje)
             VALUES (?, 'cliente', ?, FROM_UNIXTIME(?), 'sistema')`,
            [conversationId, text, timestamp]
          );

          // Agregar a la cola de espera
          await connection.commit(); // Commit para usar agregarACola que usa pool independiente
          await agregarACola(conversationId);
          console.log(`✅ Conversación ${conversationId} agregada a la cola de espera`);

          // Intentar asignar automáticamente un asesor disponible
          const asignacion = await asignarSiguienteDeCola();
          if (asignacion.asignado) {
            console.log(`✅ Conversación ${asignacion.conversacion_id} asignada a asesor ${asignacion.asesor.nombre}`);
          } else {
            console.log(`⚠️ Conversación en espera - ${asignacion.motivo}`);
          }
          return { ok: true, conversationId, message: 'Mensaje procesado y agregado a cola' };
        }

        // Insertar mensaje - usando campos correctos del esquema actual
        await connection.query(
          `INSERT INTO mensajes
           (conversacion_id, remitente, mensaje, fecha, origen_mensaje)
           VALUES (?, 'cliente', ?, FROM_UNIXTIME(?), 'sistema')`,
          [conversationId, text, timestamp]
        );

        await connection.commit();

        // Enviar evento SSE para notificar al asesor en tiempo real
        console.log(`📡 Enviando evento SSE para conversación ${conversationId}`);
        enviarEventoATodos("nuevo_mensaje", {
          conversacion_id: conversationId,
          telefono: from,
          mensaje: text
        });
        console.log(`✅ Evento SSE enviado para conversación ${conversationId}`);

        console.log(`✅ Mensaje procesado: Conversación ID ${conversationId}, de ${from}: "${text}"`);
        return { ok: true, conversationId, message: 'Mensaje procesado' };
      } catch (error) {
        await connection.rollback();
        throw error;
      } finally {
        connection.release();
      }
    } catch (error) {
      console.error('Error en handleTextMessage:', error);
      return { ok: false, error: error.message };
    }
  }

  // Procesar estado de mensaje (delivery, read, etc.)
  async processStatus(status) {
    console.log('Status update:', status);
    // Por ahora solo logueamos, luego se puede implementar tracking
    return { ok: true, message: 'Status procesado' };
  }

  // Enviar mensaje a través de WhatsApp API (usando credenciales globales del .env)
  async sendMessage(to, text, campaign = null) {
    try {
      // Usar credenciales del .env si no se proporciona campaña
      const phoneNumberId = campaign?.phone_id || process.env.WHATSAPP_PHONE_NUMBER_ID;
      const accessToken = campaign?.access_token || process.env.WHATSAPP_ACCESS_TOKEN;

      if (!phoneNumberId || !accessToken) {
        return { ok: false, error: 'Falta WHATSAPP_PHONE_NUMBER_ID o WHATSAPP_ACCESS_TOKEN en .env' };
      }

      const url = `${this.baseUrl}/${phoneNumberId}/messages`;

      const response = await axios.post(
        url,
        {
          messaging_product: 'whatsapp',
          to: to,
          type: 'text',
          text: { body: text }
        },
        {
          headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Content-Type': 'application/json'
          }
        }
      );

      return { ok: true, data: response.data };
    } catch (error) {
      console.error('Error enviando mensaje WhatsApp:', error.response?.data || error.message);
      return { ok: false, error: error.message };
    }
  }
}

module.exports = new WhatsAppService();
