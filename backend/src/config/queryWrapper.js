// Wrapper automático para adaptar consultas entre MySQL y PostgreSQL
const { adaptQuery, adaptResults } = require('./databaseAdapter');

function createQueryWrapper(pool) {
  const isPostgres = process.env.DATABASE_URL ? true : false;

  return {
    async query(sql, params) {
      if (isPostgres) {
        // PostgreSQL
        const adaptedSql = adaptQuery(sql, params);
        const result = await pool.query(adaptedSql, params);
        return result;
      } else {
        // MySQL
        const result = await pool.query(sql, params);
        return result;
      }
    },

    async execute(sql, params) {
      return this.query(sql, params);
    },

    // Método para obtener filas (compatible con ambos)
    async rows(sql, params) {
      const result = await this.query(sql, params);
      if (isPostgres) {
        return result.rows;
      }
      return result[0]; // MySQL
    },

    end() {
      if (isPostgres) {
        return pool.end();
      }
      return pool.end();
    }
  };
}

module.exports = createQueryWrapper;
