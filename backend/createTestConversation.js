const pool = require('./src/config/database');
require('dotenv').config();

async function createTestConversation() {
  try {
    console.log('🔄 Creando conversación de prueba...');

    // Crear cliente de prueba
    const [clienteResult] = await pool.query(
      `INSERT INTO clientes (telefono, nombre, distrito)
       VALUES ('+51987654321', 'Cliente Prueba', 'Lima')
       ON DUPLICATE KEY UPDATE nombre = 'Cliente Prueba'`
    );

    const clienteId = clienteResult.insertId || 1;

    // Crear conversación
    const [convResult] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, asesor_id, estado_conversacion, estado)
       VALUES (?, 2, 'atendida', 'inicio')`,
      [clienteId]
    );

    const conversacionId = convResult.insertId;

    // Agregar mensaje de prueba
    await pool.query(
      `INSERT INTO mensajes (conversacion_id, remitente, mensaje)
       VALUES (?, 'cliente', 'Hola, necesito información sobre los servicios')`,
      [conversacionId]
    );

    console.log('✅ Conversación de prueba creada:');
    console.log(`   - Cliente ID: ${clienteId}`);
    console.log(`   - Conversación ID: ${conversacionId}`);
    console.log(`   - Asesor ID: 2 (asesor1)`);
    console.log('\n📱 Ahora puedes iniciar sesión como asesor1 y ver esta conversación.');

  } catch (error) {
    console.error('❌ Error:', error);
  } finally {
    await pool.end();
  }
}

createTestConversation();
