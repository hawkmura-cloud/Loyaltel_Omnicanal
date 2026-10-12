const { Pool } = require('pg');
const fs = require('fs');
require('dotenv').config();

async function migrateToSupabase() {
  const pool = new Pool({
    connectionString: process.env.DATABASE_URL,
    ssl: {
      rejectUnauthorized: false
    }
  });

  try {
    console.log('🔄 Conectando a Supabase...');
    await pool.connect();
    console.log('✅ Conectado a Supabase');

    console.log('🔄 Leyendo script de migración...');
    const sql = fs.readFileSync('./migrate_supabase.sql', 'utf8');

    console.log('🔄 Ejecutando migración...');
    await pool.query(sql);
    console.log('✅ Migración completada exitosamente');

    console.log('\n🎉 Tablas creadas en Supabase:');
    console.log('  - asesores');
    console.log('  - clientes_panel');
    console.log('  - campañas_whatsapp');
    console.log('  - clientes');
    console.log('  - conversaciones');
    console.log('  - mensajes');
    console.log('  - actividad');
    console.log('  - cola_conversaciones');
    console.log('\n👤 Superadmin creado:');
    console.log('  - Usuario: superadmin');
    console.log('  - Contraseña: superadmin123');

  } catch (error) {
    console.error('❌ Error en migración:', error);
  } finally {
    await pool.end();
  }
}

migrateToSupabase();
