require('dotenv').config();
const pool = require('./src/config/database');

async function migrateExternalMessages() {
  try {
    console.log('🔄 Migrando mensajes externos...');

    // Agregar campo origen_mensaje a mensajes (sin verificar primero para evitar errores de permisos)
    try {
      await pool.query(`
        ALTER TABLE mensajes
        ADD COLUMN origen_mensaje ENUM('sistema', 'externo') NOT NULL DEFAULT 'sistema'
      `);
      console.log('✅ Campo origen_mensaje agregado a mensajes');
    } catch (error) {
      if (error.code === 'ER_DUP_FIELDNAME') {
        console.log('⏭️ Campo origen_mensaje ya existe');
      } else {
        throw error;
      }
    }

    console.log('\n🎉 Migración completada.');
    console.log('\n📝 origen_mensaje puede ser:');
    console.log('  - sistema: Mensaje enviado desde el panel');
    console.log('  - externo: Mensaje enviado desde WhatsApp Web/Celular fuera del sistema');

  } catch (error) {
    console.error('❌ Error en migración:', error);
  } finally {
    await pool.end();
  }
}

migrateExternalMessages();
