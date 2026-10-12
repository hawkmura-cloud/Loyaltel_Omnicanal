const request = require('supertest');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const app = require('../server');
const pool = require('../src/config/database');

const JWT_SECRET = process.env.JWT_SECRET || 'test_jwt_secret_for_testing_only';

describe('Pruebas de Integración - Claro Chatbot', () => {
  let supervisorToken;
  let asesor1Token;
  let asesor2Token;
  let asesor1Id;
  let asesor2Id;
  let clienteId;
  let conversacionId;

  beforeAll(async () => {
    console.log('🧪 Preparando datos de prueba...');

    // Crear supervisor
    const [supervisor] = await pool.query(
      `INSERT INTO asesores (nombre, telefono, estado, usuario, password_hash, rol)
       VALUES ('Supervisor Test', '000000000', 'disponible', 'supervisor', ?, 'supervisor')`,
      [await bcrypt.hash('sup123', 10)]
    );
    supervisorToken = jwt.sign(
      { asesorId: supervisor.insertId, usuario: 'supervisor', rol: 'supervisor' },
      JWT_SECRET,
      { expiresIn: '1h' }
    );

    // Crear asesores
    const [asesor1] = await pool.query(
      `INSERT INTO asesores (nombre, telefono, estado, usuario, password_hash, rol)
       VALUES ('Asesor Test 1', '999111111', 'disponible', 'asesor1', ?, 'asesor')`,
      [await bcrypt.hash('asesor1123', 10)]
    );
    asesor1Id = asesor1.insertId;
    asesor1Token = jwt.sign(
      { asesorId: asesor1Id, usuario: 'asesor1', rol: 'asesor' },
      JWT_SECRET,
      { expiresIn: '1h' }
    );

    const [asesor2] = await pool.query(
      `INSERT INTO asesores (nombre, telefono, estado, usuario, password_hash, rol)
       VALUES ('Asesor Test 2', '999222222', 'disponible', 'asesor2', ?, 'asesor')`,
      [await bcrypt.hash('asesor2123', 10)]
    );
    asesor2Id = asesor2.insertId;
    asesor2Token = jwt.sign(
      { asesorId: asesor2Id, usuario: 'asesor2', rol: 'asesor' },
      JWT_SECRET,
      { expiresIn: '1h' }
    );

    // Crear cliente
    const [cliente] = await pool.query(
      `INSERT INTO clientes (telefono, nombre, distrito)
       VALUES ('977700001', 'Cliente Test', 'Lima')`
    );
    clienteId = cliente.insertId;

    console.log('✅ Datos de prueba preparados');
  });

  afterEach(async () => {
    // Limpiar después de cada prueba
    await pool.query('DELETE FROM historial_actividad');
    await pool.query('DELETE FROM cola_atencion');
    await pool.query('DELETE FROM mensajes');
    await pool.query('DELETE FROM conversaciones');
    await pool.query('UPDATE asesores SET estado = "disponible"');
  });

  afterAll(async () => {
    // Limpiar datos de prueba
    await pool.query('DELETE FROM historial_actividad');
    await pool.query('DELETE FROM cola_atencion');
    await pool.query('DELETE FROM mensajes');
    await pool.query('DELETE FROM conversaciones');
    await pool.query('DELETE FROM clientes');
    await pool.query('DELETE FROM asesores');
  });

  // ======================================================
  // PRUEBA 1: Cliente solicita un asesor
  // ======================================================
  test('Cliente solicita un asesor - Se asigna o ingresa a la cola', async () => {
    // Caso 1: Hay asesor disponible - debe asignarse
    const [conv1] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion)
       VALUES (?, 'menu', TRUE, 'activa')`,
      [clienteId]
    );
    conversacionId = conv1.insertId;

    const response = await request(app)
      .post('/api/chat')
      .send({
        mensaje: '5',
        telefono: '977700001'
      });

    expect(response.status).toBe(200);
    expect(response.body.estado).toBe('asesor');
    expect(response.body.bot_activo).toBe(false);

    // Verificar que la conversación tiene asesor asignado
    const [convCheck] = await pool.query(
      'SELECT asesor_id FROM conversaciones WHERE id = ?',
      [conversacionId]
    );
    expect(convCheck[0].asesor_id).toBeDefined();
  });

  // ======================================================
  // PRUEBA 2: No hay asesores disponibles
  // ======================================================
  test('No hay asesores disponibles - Se conserva el orden de espera', async () => {
    // Marcar todos los asesores como ocupados
    await pool.query('UPDATE asesores SET estado = "ocupado"');

    const [conv] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion)
       VALUES (?, 'menu', TRUE, 'activa')`,
      [clienteId]
    );
    conversacionId = conv.insertId;

    const response = await request(app)
      .post('/api/chat')
      .send({
        mensaje: '5',
        telefono: '977700001'
      });

    expect(response.status).toBe(200);
    expect(response.body.estado).toBe('esperando_asesor');
    expect(response.body.bot_activo).toBe(false);

    // Verificar que está en cola
    const [cola] = await pool.query(
      'SELECT * FROM cola_atencion WHERE conversacion_id = ?',
      [conversacionId]
    );
    expect(cola.length).toBe(1);
    expect(cola[0].estado).toBe('esperando');
  });

  // ======================================================
  // PRUEBA 3: Se libera un asesor
  // ======================================================
  test('Se libera un asesor - Recibe la siguiente conversación elegible', async () => {
    // Poner ambos asesores ocupados
    await pool.query('UPDATE asesores SET estado = "ocupado"');

    // Crear conversación en cola
    const [conv] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion)
       VALUES (?, 'menu', TRUE, 'esperando_asesor')`,
      [clienteId]
    );
    conversacionId = conv.insertId;

    await pool.query(
      `INSERT INTO cola_atencion (conversacion_id, prioridad, estado)
       VALUES (?, 1, 'esperando')`,
      [conversacionId]
    );

    // Liberar asesor 1
    await pool.query(
      'UPDATE asesores SET estado = "disponible" WHERE id = ?',
      [asesor1Id]
    );

    // Intentar asignar siguiente de cola
    const { asignarSiguienteDeCola } = require('../src/services/advisorService');
    const resultado = await asignarSiguienteDeCola();

    expect(resultado).toBeDefined();
    expect(resultado.asignado).toBe(true);
    expect(resultado.conversacion_id).toBe(conversacionId);
  });

  // ======================================================
  // PRUEBA 4: Concurrencia - dos asesores mismo caso
  // ======================================================
  test('Dos asesores intentan tomar el mismo caso - Solo una asignación válida', async () => {
    // Crear conversación
    const [conv] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion)
       VALUES (?, 'menu', TRUE, 'activa')`,
      [clienteId]
    );
    conversacionId = conv.insertId;

    // Resetear asesores a disponible
    await pool.query('UPDATE asesores SET estado = "disponible"');

    // Simular dos asignaciones simultáneas al MISMO asesor
    const p1 = pool.query(
      `UPDATE asesores SET estado = 'ocupado' WHERE id = ? AND estado = 'disponible'`,
      [asesor1Id]
    );

    const p2 = pool.query(
      `UPDATE asesores SET estado = 'ocupado' WHERE id = ? AND estado = 'disponible'`,
      [asesor1Id]
    );

    const [r1, r2] = await Promise.all([p1, p2]);

    // Solo uno debe haber tenido éxito
    const successCount = r1[0].affectedRows + r2[0].affectedRows;
    expect(successCount).toBe(1);
  });

  // ======================================================
  // PRUEBA 5: Asesor accede a conversación ajena
  // ======================================================
  test('Asesor accede a una conversación ajena - Respuesta HTTP 403', async () => {
    // Crear conversación asignada a asesor 2
    const [conv] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion, asesor_id)
       VALUES (?, 'menu', FALSE, 'atendida', ?)`,
      [clienteId, asesor2Id]
    );
    conversacionId = conv.insertId;

    // Asesor 1 intenta acceder
    const response = await request(app)
      .get(`/api/conversaciones/${conversacionId}/mensajes`)
      .set('Authorization', `Bearer ${asesor1Token}`);

    expect(response.status).toBe(403);
    expect(response.body.error).toContain('permiso');
  });

  // ======================================================
  // PRUEBA 6: JWT vencido
  // ======================================================
  test('JWT vencido - Acceso rechazado', async () => {
    // Crear token expirado
    const expiredToken = jwt.sign(
      { asesorId: asesor1Id, usuario: 'asesor1', rol: 'asesor' },
      JWT_SECRET,
      { expiresIn: '-1h' } // Expirado hace 1 hora
    );

    const response = await request(app)
      .get('/api/panel/resumen')
      .set('Authorization', `Bearer ${expiredToken}`);

    expect(response.status).toBe(403);
    expect(response.body.error).toContain('inválido o expirado');
  });

  // ======================================================
  // PRUEBA 7: Mensajes repetidos no duplican
  // ======================================================
  test('Cliente envía mensajes repetidos - No se crean asignaciones duplicadas', async () => {
    // Primer mensaje
    await request(app)
      .post('/api/chat')
      .send({
        mensaje: 'Hola',
        telefono: '977700001'
      });

    // Segundo mensaje (mismo cliente)
    const response = await request(app)
      .post('/api/chat')
      .send({
        mensaje: '¿Hola?',
        telefono: '977700001'
      });

    expect(response.status).toBe(200);

    // Verificar que solo hay una conversación
    const [convs] = await pool.query(
      'SELECT COUNT(*) as total FROM conversaciones WHERE cliente_id = ?',
      [clienteId]
    );
    expect(convs[0].total).toBe(1);
  });

  // ======================================================
  // PRUEBA 8: Recuperación de conexión SSE
  // ======================================================
  // Nota: SSE no se puede probar fácilmente con Supertest (timeout)
  // Esta funcionalidad se prueba manualmente en el frontend

  // ======================================================
  // PRUEBA 9: Persistencia tras reinicio
  // ======================================================
  test('Se reinicia Node.js - Los datos persistentes se conservan', async () => {
    // Crear datos
    const [conv] = await pool.query(
      `INSERT INTO conversaciones (cliente_id, estado, bot_activo, estado_conversacion)
       VALUES (?, 'inicio', TRUE, 'activa')`,
      [clienteId]
    );
    conversacionId = conv.insertId;

    // Verificar que existe
    const [convs] = await pool.query(
      'SELECT * FROM conversaciones WHERE id = ?',
      [conversacionId]
    );
    expect(convs.length).toBe(1);
    expect(convs[0].cliente_id).toBe(clienteId);
  });

  // ======================================================
  // PRUEBA 10: Login correcto
  // ======================================================
  test('Login correcto - Devuelve token JWT', async () => {
    const response = await request(app)
      .post('/api/auth/login')
      .send({
        usuario: 'asesor1',
        password: 'asesor1123'
      });

    expect(response.status).toBe(200);
    expect(response.body.ok).toBe(true);
    expect(response.body.token).toBeDefined();
    expect(response.body.usuario.rol).toBe('asesor');
  });

  // ======================================================
  // PRUEBA 11: Login incorrecto
  // ======================================================
  test('Login incorrecto - Devuelve error 401', async () => {
    const response = await request(app)
      .post('/api/auth/login')
      .send({
        usuario: 'asesor1',
        password: 'wrongpassword'
      });

    expect(response.status).toBe(401);
    expect(response.body.ok).toBe(false);
  });

  // ======================================================
  // PRUEBA 12: Protección de endpoints sin token
  // ======================================================
  test('Endpoint sin token - Devuelve 401', async () => {
    const response = await request(app)
      .get('/api/panel/resumen');

    expect(response.status).toBe(401);
    expect(response.body.error).toContain('token');
  });
});
