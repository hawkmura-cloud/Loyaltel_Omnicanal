// Adaptador para que las consultas funcionen con MySQL y PostgreSQL
// MySQL usa ? para parámetros, PostgreSQL usa $1, $2, etc.

function adaptQuery(sql, params) {
  if (!params || params.length === 0) return sql;

  // Si DATABASE_URL está configurado, es PostgreSQL
  if (process.env.DATABASE_URL) {
    // Reemplazar ? con $1, $2, etc.
    let index = 1;
    return sql.replace(/\?/g, () => `$${index++}`);
  }

  // MySQL, retornar como está
  return sql;
}

function adaptResults(results) {
  // PostgreSQL devuelve { rows, rowCount }, MySQL devuelve array directo
  if (process.env.DATABASE_URL) {
    // PostgreSQL
    return {
      rows: results.rows,
      affectedRows: results.rowCount || 0
    };
  }

  // MySQL
  return results;
}

module.exports = { adaptQuery, adaptResults };
