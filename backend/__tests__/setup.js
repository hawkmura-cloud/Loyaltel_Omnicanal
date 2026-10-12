require('dotenv').config({ path: '.env.test' });

const pool = require('../src/config/database');

beforeAll(async () => {
  console.log('🧪 Configurando base de datos de pruebas...');

  try {
    // Usar la base de datos existente en lugar de crear una nueva
    console.log('✅ Usando base de datos existente');

    // Limpiar datos anteriores
    await pool.query('DELETE FROM historial_actividad');
    await pool.query('DELETE FROM cola_atencion');
    await pool.query('DELETE FROM mensajes');
    await pool.query('DELETE FROM conversaciones');
    await pool.query('DELETE FROM clientes');
    await pool.query('DELETE FROM asesores');

    console.log('✅ Datos anteriores limpiados');

  } catch (error) {
    console.error('❌ Error configurando base de datos de pruebas:', error);
    throw error;
  }
});

afterAll(async () => {
  console.log('🧪 Limpiando base de datos de pruebas...');
  await pool.end();
});
