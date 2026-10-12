const mysql = require("mysql2/promise");
const { Pool } = require('pg');

let pool;

// Detectar si debe usar Supabase (Docker/Producción) o MySQL (Local)
if (process.env.DATABASE_URL) {
  // Usar PostgreSQL (Supabase)
  console.log('📊 Usando PostgreSQL (Supabase)');
  pool = new Pool({
    connectionString: process.env.DATABASE_URL,
    ssl: {
      rejectUnauthorized: false
    }
  });
} else {
  // Usar MySQL (Local)
  console.log('📊 Usando MySQL (Local)');
  pool = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'claro_chatbot',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
  });
}

module.exports = pool;
