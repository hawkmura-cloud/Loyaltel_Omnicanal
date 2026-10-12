const axios = require('axios');
const pool = require('../config/database');

class WhatsAppService {
  constructor() {
    this.baseUrl = 'https://graph.facebook.com/v18.0';
  }

  // Obtener configuración de una campaña por número de WhatsApp
  async getCampaignByNumber(numero) {
    try {
      const [rows] = await pool.query(
        'SELECT * FROM campañas_whatsapp WHERE numero_whatsapp = ? AND activo = TRUE',
        [numero]
      );
      return rows[0] || null;
    } catch (error) {
      console.error('Error obteniendo campaña:', error);
      return null;
    }
  }

  // Obtener configuración de una campaña por ID
  async getCampaignById(id) {
    try {
      const [rows] = await pool.query(
        'SELECT * FROM campañas_whatsapp WHERE id = ?',
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
    // token debe coincidir con el webhook_secret configurado
    // En producción, verificar contra el secreto de la campaña
    // Por ahora aceptamos cualquier token para desarrollo
    const VERIFY_TOKEN = process.env.WHATSAPP_VERIFY_TOKEN || 'loyaltel_webhook_2026';
    
    if (mode === 'subscribe' && token === VERIFY_TOKEN) {
      console.log('Webhook verification successful');
      return challenge;
    } else {
      console.log('Webhook verification failed');
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
        return await this.processMessage(message, value.metadata);
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
  async processMessage(message, metadata) {
    try {
      const from = message.from; // Número del cliente
      const to = message.to; // Número de WhatsApp Business
      const timestamp = message.timestamp;
      const type = message.type;

      // Buscar la campaña por el número de destino
      const campaign = await this.getCampaignByNumber(to);
      if (!campaign) {
        return { ok: false, error: 'Campaña no encontrada para este número' };
      }

      // Procesar según tipo de mensaje
      if (type === 'text') {
        const text = message.text.body;
        return await this.handleTextMessage(from, to, text, campaign, timestamp);
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
  async handleTextMessage(from, to, text, campaign, timestamp) {
    try {
      const connection = await pool.getConnection();

      try {
        await connection.beginTransaction();

        // Buscar si ya existe una conversación con este número
        const [existing] = await connection.query(
          `SELECT * FROM conversaciones
           WHERE telefono_whatsapp = ?
           AND campaña_whatsapp_id = ?
           AND estado_conversacion != 'finalizada'
           ORDER BY fecha_inicio DESC
           LIMIT 1`,
          [from, campaign.id]
        );

        let conversationId;

        if (existing.length > 0) {
          // Actualizar conversación existente
          conversationId = existing[0].id;
          await connection.query(
            `UPDATE conversaciones
             SET estado_conversacion = 'en_curso',
                 fecha_ultimo_mensaje = NOW()
             WHERE id = ?`,
            [conversationId]
          );
        } else {
          // Crear nueva conversación
          const [result] = await connection.query(
            `INSERT INTO conversaciones
             (cliente_id, cliente_panel_id, campaña_whatsapp_id, telefono_whatsapp, estado_conversacion, fecha_inicio, fecha_ultimo_mensaje)
             VALUES (?, ?, ?, ?, 'en_curso', NOW(), NOW())`,
            [null, campaign.cliente_panel_id, campaign.id, from]
          );
          conversationId = result.insertId;
        }

        // Insertar mensaje
        await connection.query(
          `INSERT INTO mensajes
           (conversacion_id, remitente, contenido, tipo_mensaje, fecha_mensaje)
           VALUES (?, 'cliente', ?, 'texto', FROM_UNIXTIME(?))`,
          [conversationId, text, timestamp]
        );

        await connection.commit();

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

  // Enviar mensaje a través de WhatsApp API
  async sendMessage(to, text, campaign) {
    try {
      if (!campaign.access_token || !campaign.phone_id) {
        return { ok: false, error: 'Falta access_token o phone_id en la campaña' };
      }

      const url = `${this.baseUrl}/${campaign.phone_id}/messages`;

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
            'Authorization': `Bearer ${campaign.access_token}`,
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
