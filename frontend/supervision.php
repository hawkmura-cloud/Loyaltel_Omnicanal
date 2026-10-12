
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel | Panel de atención omnicanal</title>
<style>
:root {
  --loyaltel-blue: #0066CC;
  --loyaltel-light-blue: #3399FF;
  --loyaltel-dark: #000000;
  --loyaltel-gray: #1a1a1a;
  --bg: #f8f9fa;
  --white: #fff;
  --text: #000000;
  --muted: #666666;
  --border: #e0e0e0;
  --green: #28a745;
  --red: #dc3545;
}
* { box-sizing: border-box; }
body {
  margin: 0;
  font-family: "Segoe UI", Arial, sans-serif;
  color: var(--text);
  background: var(--bg);
}
header {
  background: var(--loyaltel-dark);
  color: white;
  padding: 18px 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
}
.brand { display: flex; align-items: center; gap: 12px; font-weight: 800; }
.logo {
  width: 120px; height: auto;
  display: block;
}
.brand small { display: block; font-size: 10px; margin-top: 4px; color: #b3b3b3; }
.header-controls {
  display: flex;
  align-items: center;
  gap: 20px;
}
.client-selector-header {
  display: flex;
  align-items: center;
  gap: 8px;
}
.client-selector-header label {
  font-size: 12px;
  font-weight: 700;
  color: #b3b3b3;
}
.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}
.refresh-header-btn {
  background: var(--loyaltel-blue);
  color: white;
  border: 0;
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
}
.refresh-header-btn:hover {
  background: var(--loyaltel-light-blue);
}
.refresh-header-btn:disabled {
  opacity: 0.6;
  cursor: wait;
}
.connection { font-size: 12px; color: #e1e7ef; }
.layout { display: grid; grid-template-columns: 210px minmax(0,1fr); min-height: calc(100vh - 78px); }
aside { background: white; padding: 24px 14px; border-right: 1px solid var(--border); }
.nav-label { font-size: 10px; color: var(--muted); font-weight: 800; letter-spacing: 1px; padding: 8px 10px 15px; }
.nav {
  display: block; width: 100%; padding: 12px; margin-bottom: 5px;
  text-align: left; border: 0; border-radius: 9px;
  background: transparent; color: var(--muted); cursor: pointer; font-size: 13px;
}
.nav.active { background: #e8f0fe; color: var(--loyaltel-blue); font-weight: 800; }
main { padding: 28px; min-width: 0; }
.pagehead { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 22px; }
h1 { margin: 0; font-size: 25px; }
.subtitle { margin-top: 7px; color: var(--muted); font-size: 13px; }
button { font-family: inherit; }
.primary {
  background: var(--loyaltel-blue); color: white; border: 0; border-radius: 9px;
  padding: 11px 15px; font-weight: 700; cursor: pointer;
}
.primary:hover { background: var(--loyaltel-light-blue); }
.primary:disabled { opacity: .6; cursor: wait; }
.cards { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 14px; margin-bottom: 22px; }
.metric { background: white; border: 1px solid var(--border); border-radius: 13px; padding: 17px; }
.metric-top { color: var(--muted); font-size: 12px; }
.metric-value { font-size: 30px; font-weight: 800; margin-top: 13px; }
.metric-note { color: var(--muted); font-size: 11px; margin-top: 4px; }
.columns { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(300px, 1fr); gap: 20px; align-items: start; }
.panel { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
.panel-head { padding: 18px 20px; border-bottom: 1px solid var(--border); background: #fafafa; }
.panel-title { font-size: 15px; font-weight: 800; color: var(--loyaltel-dark); }
.panel-sub { color: var(--muted); font-size: 12px; margin-top: 4px; }
.table-wrap { overflow-x: auto; }
table { border-collapse: collapse; width: 100%; font-size: 12px; }
th { background: #f8f9fb; color: var(--muted); font-size: 10px; text-transform: uppercase; text-align: left; padding: 12px; }
td { padding: 13px 12px; border-top: 1px solid var(--border); vertical-align: middle; }
.pill { display: inline-block; border-radius: 99px; padding: 5px 8px; font-size: 10px; font-weight: 800; white-space: nowrap; background: #edf0f4; color: #596579; }
.available { background: #e5f6ee; color: var(--green); }
.busy { background: #fff2df; color: #9b661f; }
.offline { background: #edf0f4; color: #667085; }
.waiting { background: #fdeaea; color: var(--red); }

/* Indicador de actualización */
.updating {
  animation: pulse 1s ease-in-out;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}
.queue-list { padding: 0 18px; }
.queue-item { padding: 14px 0; border-bottom: 1px solid var(--border); display: flex; gap: 10px; align-items: center; }
.queue-item:last-child { border-bottom: 0; }
.queue-num { width: 30px; height: 30px; display: grid; place-items: center; background: #f1f3f7; border-radius: 8px; font-weight: 800; font-size: 11px; }
.queue-info { flex: 1; min-width: 0; }
.queue-info strong { display: block; font-size: 12px; overflow-wrap: anywhere; }
.queue-info small { display: block; color: var(--muted); font-size: 10px; margin-top: 4px; }
.empty { padding: 22px 14px; color: var(--muted); text-align: center; font-size: 12px; }
.notice { padding: 12px 14px; border: 1px solid var(--border); border-radius: 10px; color: var(--muted); font-size: 11px; line-height: 1.6; background: white; }

/* Selector de cliente omnicanal */
.client-selector {
  margin-top: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.client-selector label {
  font-size: 12px;
  font-weight: 700;
  color: var(--muted);
}
.client-select {
  padding: 8px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 13px;
  background: white;
  color: var(--text);
  min-width: 200px;
}
.client-select:focus {
  outline: none;
  border-color: var(--loyaltel-blue);
}

/* Mejoras en el dashboard */
.pagehead {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 15px;
  margin-bottom: 24px;
}
.pagehead h1 {
  margin: 0;
  font-size: 28px;
  color: var(--loyaltel-dark);
}
.pagehead .subtitle {
  margin-top: 6px;
  color: var(--muted);
  font-size: 14px;
}
.cards {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}
.metric {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s, box-shadow 0.2s;
}
.metric:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.metric-top {
  color: var(--muted);
  font-size: 13px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.metric-value {
  font-size: 36px;
  font-weight: 800;
  margin-top: 12px;
  color: var(--loyaltel-blue);
}
.metric-note {
  color: var(--muted);
  font-size: 12px;
  margin-top: 6px;
}

/* VENTANA DEL HISTORIAL */
[hidden] {
  display: none !important;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 18px;
  background: rgba(10, 18, 32, 0.65);
}

.modal {
  display: flex;
  flex-direction: column;
  width: min(680px, 100%);
  max-height: 85vh;
  overflow: hidden;
  background: white;
  border-radius: 14px;
  box-shadow: 0 20px 60px #0003;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 17px 20px;
  background: var(--navy);
  color: white;
}

.modal-header h2 {
  margin: 0;
  font-size: 16px;
}

.modal-header small {
  display: block;
  margin-top: 5px;
  color: #d8deea;
}

.close-modal {
  border: 0;
  border-radius: 8px;
  padding: 8px 12px;
  background: #ffffff20;
  color: white;
  cursor: pointer;
  font-size: 18px;
}

.chat-messages {
  padding: 20px;
  overflow-y: auto;
  background: #f3f5f8;
  min-height: 180px;
}

.chat-message {
  max-width: 85%;
  margin-bottom: 15px;
  padding: 11px 13px;
  border-radius: 12px;
  background: white;
  border: 1px solid var(--border);
  overflow-wrap: anywhere;
}

.chat-message.cliente {
  margin-left: auto;
  background: #e5f6ee;
  border-color: #c7ead8;
}

.chat-message.bot,
.chat-message.asesor {
  margin-right: auto;
}

.chat-message strong {
  display: block;
  margin-bottom: 6px;
  font-size: 11px;
  color: var(--navy);
}

.chat-message p {
  margin: 0;
  font-size: 13px;
  line-height: 1.55;
  white-space: pre-wrap;
}

.chat-message time {
  display: block;
  margin-top: 8px;
  color: var(--muted);
  font-size: 10px;
}

.history-status {
  padding: 20px;
  color: var(--muted);
  text-align: center;
  font-size: 13px;
}

.history-button {
  border: 0;
  border-radius: 7px;
  padding: 8px 10px;
  background: var(--navy);
  color: white;
  cursor: pointer;
  font-size: 11px;
  font-weight: 700;
  white-space: nowrap;
}

.small-btn {
  border: 0;
  border-radius: 7px;
  padding: 8px 10px;
  background: var(--red);
  color: white;
  cursor: pointer;
  font-size: 11px;
  font-weight: 700;
  white-space: nowrap;
}

/* Login screen */
.login-screen {
  position: fixed; inset: 0; z-index: 1000;
  display: flex; align-items: center; justify-content: center;
  background: var(--bg);
}
.login-card {
  background: white; border: 1px solid var(--border);
  border-radius: 14px; padding: 32px; width: min(400px, 90%);
  box-shadow: 0 20px 60px #0003;
  text-align: center;
}
.login-card .logo {
  width: 120px;
  height: auto;
  margin-bottom: 20px;
}
.login-card h1 { margin: 0 0 8px; font-size: 20px; color: var(--loyaltel-dark); }
.login-card p { margin: 0 0 24px; color: var(--muted); font-size: 13px; }
.login-input {
  width: 100%; padding: 12px; border: 1px solid var(--border);
  border-radius: 9px; font-size: 14px; margin-bottom: 12px;
}
.login-btn {
  width: 100%; background: var(--loyaltel-blue); color: white;
  border: 0; border-radius: 9px; padding: 12px;
  font-weight: 700; cursor: pointer;
}
.login-btn:hover { background: var(--loyaltel-light-blue); }
.login-btn:disabled { opacity: 0.55; cursor: not-allowed; }
.login-error {
  color: var(--red); font-size: 12px; margin-top: 12px;
  display: none;
}
.logout-btn {
  border: 0; background: transparent; cursor: pointer;
  color: #d8deea; font-size: 12px; margin-left: 15px;
}
.logout-btn:hover { color: white; }
[hidden] { display: none !important; }
#feedback { margin: 0 0 16px; font-size: 12px; color: var(--muted); }
#feedback.error { color: var(--red); }
footer { color: var(--muted); font-size: 10px; padding: 4px 0 15px; }
@media(max-width:1000px) {
  .cards { grid-template-columns: repeat(2,minmax(0,1fr)); }
  .columns { grid-template-columns: 1fr; }
}
@media(max-width:650px) {
  header { padding: 14px; }
  .connection { max-width: 130px; text-align: right; }
  .layout { grid-template-columns: 1fr; }
  aside { display: flex; gap: 5px; overflow-x: auto; padding: 9px; }
  .nav-label { display: none; }
  .nav { width: auto; white-space: nowrap; margin: 0; }
  main { padding: 18px 12px; }
  .pagehead { align-items: flex-start; flex-direction: column; }
  h1 { font-size: 22px; }
  .metric { padding: 12px; }
  .metric-value { font-size: 25px; }
  .cards { gap: 9px; }
}


/* Respuesta del asesor */
.reply-box {
  padding: 14px 18px;
  border-top: 1px solid var(--border);
  background: var(--surface);
}

.reply-box textarea {
  display: block;
  width: 100%;
  resize: vertical;
  min-height: 75px;
  padding: 12px;
  border: 1px solid var(--border);
  border-radius: 9px;
  background: var(--surface2);
  color: var(--text);
  font: inherit;
  font-size: 13px;
  outline-offset: 2px;
}

.reply-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: 10px;
}

.reply-footer small {
  color: var(--muted);
  font-size: 11px;
}

#sendReply:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.chat-message.asesor {
  margin-left: auto;
  max-width: 85%;
}

/* Vista de reportes */
.reports-section {
  margin-top: 22px;
}
.report-cards {
  display: grid; grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 14px; margin-bottom: 22px;
}
.report-table {
  margin-bottom: 22px;
}
.activity-timeline {
  margin-top: 12px;
}
.activity-item {
  padding: 12px; border-left: 3px solid var(--border);
  margin-bottom: 12px; background: #f8f9fb;
}
.activity-item.asignacion { border-left-color: var(--green); }
.activity-item.reasignacion { border-left-color: var(--sand); }
.activity-item.finalizacion { border-left-color: var(--red); }
.activity-item.respuesta { border-left-color: var(--navy); }
.activity-time {
  font-size: 10px; color: var(--muted); margin-bottom: 4px;
}
.activity-desc {
  font-size: 12px; color: var(--text);
}

</style>
</head>
<body>

<!-- Pantalla de login -->
<div class="login-screen" id="loginScreen">
  <div class="login-card">
    <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo">
    <h1>👨‍💼 Panel de Supervisor</h1>
    <p>Ingresa tus credenciales para acceder</p>
    <input type="text" class="login-input" id="loginUsername" placeholder="Usuario" autocomplete="username">
    <input type="password" class="login-input" id="loginPassword" placeholder="Contraseña" autocomplete="current-password">
    <button class="login-btn" id="loginBtn">Iniciar sesión</button>
    <div class="login-error" id="loginError"></div>
  </div>
</div>

<header id="mainHeader" hidden>
  <div class="brand">
    <img src="logo-loyaltel.png" alt="Loyaltel Logo" class="logo">
    <div>LOYALTEL<small>PLATAFORMA OMNICANAL</small></div>
  </div>
  <div class="header-controls">
    <div class="client-selector-header" id="clientSelectorHeader" hidden>
      <label for="clientSelect">Cliente:</label>
      <select id="clientSelect" class="client-select">
        <option value="">Todos los clientes</option>
      </select>
    </div>
    <div class="header-actions">
      <div class="connection" id="connection">● Conectando con el servidor...</div>
      <button class="refresh-header-btn" id="refresh" title="Actualizar datos">↻</button>
      <button class="logout-btn" id="logoutBtn">Cerrar sesión</button>
    </div>
  </div>
</header>

<div class="layout">
  <aside>
    <div class="nav-label">PANEL COMERCIAL</div>
    <button class="nav active" data-view="Resumen">▦ &nbsp; Resumen</button>
    <button class="nav" data-view="Conversaciones">☷ &nbsp; Conversaciones</button>
    <button class="nav" data-view="Cola de atención">◷ &nbsp; Cola de atención</button>
    <button class="nav" data-view="Asesores">♙ &nbsp; Asesores</button>
    <button class="nav" data-view="Reportes">📊 &nbsp; Reportes</button>
  </aside>

  <main>
    <div class="pagehead">
      <div>
        <h1 id="heading">Resumen de atención</h1>
        <div class="subtitle" id="subtitle">Indicadores actualizados desde MySQL.</div>
      </div>
    </div>

    <div id="feedback" role="status">Consultando la API...</div>

    <section class="cards">
      <div class="metric">
        <div class="metric-top">Conversaciones activas</div>
        <div class="metric-value" id="activeCount">—</div>
        <div class="metric-note">En curso o atendidas</div>
      </div>
      <div class="metric">
        <div class="metric-top">En cola</div>
        <div class="metric-value" id="queueCount">—</div>
        <div class="metric-note">Esperando asesor</div>
      </div>
      <div class="metric">
        <div class="metric-top">Asesores disponibles</div>
        <div class="metric-value" id="availableCount">—</div>
        <div class="metric-note">Listos para atender</div>
      </div>
      <div class="metric">
        <div class="metric-top">🏆 Mejor Vendedor</div>
        <div class="metric-value" id="topSeller">—</div>
        <div class="metric-note" id="topSellerSales">— ventas</div>
      </div>
    </section>

    <div class="columns">
      <div>
        <section class="panel" id="advisorPanel">
          <div class="panel-head">
            <div class="panel-title">Estado de asesores</div>
            <div class="panel-sub">Disponibilidad registrada en el sistema</div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Asesor</th><th>Estado</th><th>Conversaciones</th></tr></thead>
              <tbody id="advisorRows">
                <tr><td colspan="3" class="empty">Cargando asesores...</td></tr>
              </tbody>
            </table>
          </div>
        </section>

        <section class="panel" id="conversationPanel">
          <div class="panel-head">
            <div class="panel-title">Conversaciones recientes</div>
            <div class="panel-sub">Últimas conversaciones registradas</div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
  <tr>
    <th>Conversación / cliente</th>
    <th>Estado</th>
    <th>Asesor asignado</th>
    <th>Historial</th>
  </tr>
</thead>
              <tbody id="conversationRows">
                <tr><td colspan="3" class="empty">Cargando conversaciones...</td></tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <div>
        <section class="panel" id="queuePanel">
          <div class="panel-head">
            <div class="panel-title">Cola de atención</div>
            <div class="panel-sub">Clientes que esperan asignación</div>
          </div>
          <div class="queue-list" id="queueRows">
            <div class="empty">Consultando cola...</div>
          </div>
        </section>

        <section class="panel reports-section" id="reportsPanel" hidden>
          <div class="panel-head">
            <div class="panel-title">Reportes comerciales</div>
            <div class="panel-sub">Métricas de desempeño y análisis comercial</div>
          </div>
          <div class="report-cards">
            <div class="metric">
              <div class="metric-top">Conversaciones finalizadas</div>
              <div class="metric-value" id="reportTotalConv">—</div>
              <div class="metric-note">Total de conversaciones completadas</div>
            </div>
            <div class="metric">
              <div class="metric-top">Clientes únicos</div>
              <div class="metric-value" id="reportTotalClients">—</div>
              <div class="metric-note">Clientes diferentes atendidos</div>
            </div>
            <div class="metric">
              <div class="metric-top">Tiempo promedio respuesta</div>
              <div class="metric-value" id="reportAvgResponse">—</div>
              <div class="metric-note">Segundos hasta primera respuesta</div>
            </div>
          </div>
          <div class="panel report-table">
            <div class="panel-head">
              <div class="panel-title">Por campaña</div>
            </div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Campaña</th><th>Conversaciones</th><th>Clientes</th></tr></thead>
                <tbody id="reportByCampaign">
                  <tr><td colspan="3" class="empty">Cargando...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="panel report-table">
            <div class="panel-head">
              <div class="panel-title">Por asesor</div>
            </div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Asesor</th><th>Conversaciones</th><th>Prom. respuesta</th></tr></thead>
                <tbody id="reportByAdvisor">
                  <tr><td colspan="3" class="empty">Cargando...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <div class="notice">
          <strong>Datos en vivo.</strong> La información visible proviene de la API local. Los cambios se actualizan al pulsar “Actualizar datos” y automáticamente cada 15 segundos.
        </div>
      </div>
    </div>
    
    <!-- VENTANA DEL HISTORIAL DE CONVERSACIÓN -->
    <div class="modal-backdrop" id="historyModal" hidden>
      <section
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="historyTitle">

        <div class="modal-header">
          <div>
            <h2 id="historyTitle">Historial de conversación</h2>
            <small id="historySubtitle">Cargando mensajes...</small>
          </div>
          <button
            class="close-modal"
            id="closeHistory"
            type="button"
            aria-label="Cerrar historial">×</button>
        </div>

        <div class="chat-messages" id="historyMessages" aria-live="polite">
          <div class="history-status">Cargando mensajes...</div>
        </div>

        <div class="activity-timeline" id="activityTimeline" aria-live="polite">
          <div class="history-status">Cargando historial de actividad...</div>
        </div>

<div class="reply-box">
  <textarea
    id="advisorReply"
    placeholder="Escribe tu respuesta al cliente..."
    rows="3"
    maxlength="2000"
    aria-label="Respuesta del asesor"></textarea>


  <div class="reply-footer">
    <small id="replyStatus">Responde como asesor</small>
    <button class="primary" id="sendReply" type="button">
      Enviar respuesta
    </button>
    <button class="small-btn" id="finishConversation" type="button">
      Finalizar atención
    </button>
  </div>

</div>

      </section>
    </div>
    <footer>Loyaltel · Panel omnicanal de administración</footer>
  </main>
</div>

<script>
const API_BASE = "http://localhost:3000/api";
const API_URL = `${API_BASE}/panel/resumen`;
const SSE_URL = `${API_BASE}/panel/sse`;

// Timeout de inactividad: 15 minutos
const INACTIVITY_TIMEOUT = 15 * 60 * 1000; // 15 minutos en milisegundos
let inactivityTimer;

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(() => {
    // Limpiar localStorage y redirigir a index.php
    localStorage.removeItem('authToken');
    localStorage.removeItem('currentUser');
    window.location.href = 'index.php';
  }, INACTIVITY_TIMEOUT);
}

// Resetear timer en cualquier actividad del usuario
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keypress', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);
document.addEventListener('scroll', resetInactivityTimer);

// Iniciar timer al cargar la página
resetInactivityTimer();

const $ = id => document.getElementById(id);
let authToken = localStorage.getItem('authToken');
let currentUser = JSON.parse(localStorage.getItem('currentUser') || 'null');

// ======================================================
// AUTHENTICATION
// ======================================================

function checkAuth() {
  if (authToken && currentUser) {
    showMainInterface();
  } else {
    showLoginScreen();
  }
}

function showLoginScreen() {
  $("loginScreen").hidden = false;
  $("mainHeader").hidden = true;
  document.querySelector(".layout").hidden = true;
}

function showMainInterface() {
  $("loginScreen").hidden = true;
  $("mainHeader").hidden = false;
  document.querySelector(".layout").hidden = false;
}

async function login() {
  const username = $("loginUsername").value.trim();
  const password = $("loginPassword").value;

  if (!username || !password) {
    showLoginError("Usuario y contraseña son requeridos");
    return;
  }

  $("loginBtn").disabled = true;
  $("loginBtn").textContent = "Iniciando sesión...";
  $("loginError").style.display = "none";

  try {
    const response = await fetch(`${API_BASE}/auth/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ usuario: username, password })
    });

    const data = await response.json();

    if (data.ok) {
      authToken = data.token;
      currentUser = data.usuario;

      localStorage.setItem('authToken', authToken);
      localStorage.setItem('currentUser', JSON.stringify(currentUser));

      showMainInterface();
      cargarResumen();
      conectarSSE();
    } else {
      showLoginError(data.error || "Error al iniciar sesión");
    }
  } catch (error) {
    console.error("Error en login:", error);
    showLoginError("Error de conexión con el servidor");
  } finally {
    $("loginBtn").disabled = false;
    $("loginBtn").textContent = "Iniciar sesión";
  }
}

function showLoginError(message) {
  $("loginError").textContent = message;
  $("loginError").style.display = "block";
}

function logout() {
  localStorage.removeItem('authToken');
  localStorage.removeItem('currentUser');
  authToken = null;
  currentUser = null;

  if (eventSource) {
    eventSource.close();
    eventSource = null;
  }

  showLoginScreen();
}

// Event listeners de login
$("loginBtn").addEventListener("click", login);
$("loginPassword").addEventListener("keydown", (e) => {
  if (e.key === "Enter") login();
});
$("logoutBtn").addEventListener("click", logout);

// Helper para requests autenticados
async function authenticatedFetch(url, options = {}) {
  const headers = {
    ...options.headers,
    'Authorization': `Bearer ${authToken}`
  };

  return fetch(url, { ...options, headers });
}

function escapeHTML(value) {
  return String(value ?? "").replace(/[&<>"']/g, char => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;"
  })[char]);
}

function estadoAsesor(estado) {
  const map = {
    disponible: ["Disponible", "available"],
    ocupado: ["Ocupado", "busy"],
    desconectado: ["Desconectado", "offline"]
  };
  return map[estado] || [estado || "Sin estado", "offline"];
}

function estadoConversacion(estado) {
  const map = {
    activa: ["Activa (bot)", "available"],
    esperando_asesor: ["En cola", "waiting"],
    atendida: ["Atendida", "busy"],
    finalizada: ["Finalizada", "offline"]
  };
  return map[estado] || [estado || "Desconocido", "offline"];
}

function mostrarAsesores(asesores) {
  $("advisorRows").innerHTML = asesores.length
    ? asesores.map(a => {
        const [label, css] = estadoAsesor(a.estado);
        return `<tr>
          <td><strong>${escapeHTML(a.nombre || "Asesor " + a.id)}</strong>
              <br><small>${escapeHTML(a.telefono || "Sin teléfono")}</small></td>
          <td><span class="pill ${css}">${escapeHTML(label)}</span></td>
          <td>${Number(a.conversaciones_activas) || 0}</td>
        </tr>`;
      }).join("")
    : '<tr><td colspan="3" class="empty">No hay asesores registrados.</td></tr>';
}


function mostrarConversaciones(conversaciones) {
  $("conversationRows").innerHTML = conversaciones.length
    ? conversaciones.map(c => {
        const [label, css] = estadoConversacion(c.estado_conversacion);
        const cliente = c.cliente || c.telefono || "Cliente sin identificar";

        return `<tr>
          <td>
            <strong>#${escapeHTML(c.conversacion_id)}</strong><br>
            ${escapeHTML(cliente)}<br>
            <small>${escapeHTML(c.telefono || "")}</small>
          </td>
          <td>
            <span class="pill ${css}">${escapeHTML(label)}</span>
          </td>
          <td>${escapeHTML(c.asesor || "Sin asignar")}</td>
          <td>
            <button
              class="history-button"
              data-history-id="${Number(c.conversacion_id)}"
              data-history-phone="${escapeHTML(c.telefono || "")}">
              Ver mensajes
            </button>
            ${c.asesor ? `
            <button
              class="history-button"
              data-transfer-id="${Number(c.conversacion_id)}"
              data-transfer-asesor="${escapeHTML(c.asesor)}"
              style="margin-left: 5px; background: var(--sand);">
              Transferir
            </button>
            ` : ''}
          </td>
        </tr>`;
      }).join("")
    : '<tr><td colspan="4" class="empty">Todavía no hay conversaciones.</td></tr>';
}

function mostrarCola(cola) {
  $("queueRows").innerHTML = cola.length
    ? cola.map((c, index) => `
      <div class="queue-item">
        <div class="queue-num">${String(index + 1).padStart(2, "0")}</div>
        <div class="queue-info">
          <strong>${escapeHTML(c.cliente || c.telefono || "Cliente sin nombre")}</strong>
          <small>Conversación #${escapeHTML(c.conversacion_id)} · Prioridad ${escapeHTML(c.prioridad)}</small>
          <small>Teléfono: ${escapeHTML(c.telefono || "No registrado")}</small>
        </div>
        <span class="pill waiting">Espera</span>
      </div>`).join("")
    : '<div class="empty">La cola está vacía.</div>';
}

async function cargarReportes() {
  try {
    const response = await authenticatedFetch(`${API_BASE}/reportes/comerciales`);
    const data = await response.json();

    if (data.ok) {
      const resumen = data.resumen;

      $("reportTotalConv").textContent = resumen.total_conversaciones || 0;
      $("reportTotalClients").textContent = resumen.total_clientes || 0;
      $("reportAvgResponse").textContent = Math.round(resumen.promedio_primera_respuesta || 0) + 's';

      // Por campaña
      $("reportByCampaign").innerHTML = data.por_campania.length
        ? data.por_campania.map(c => `
          <tr>
            <td>${escapeHTML(c.campania || 'Sin campaña')}</td>
            <td>${c.conversaciones}</td>
            <td>${c.clientes}</td>
          </tr>
        `).join("")
        : '<tr><td colspan="3" class="empty">No hay datos por campaña</td></tr>';

      // Por asesor
      $("reportByAdvisor").innerHTML = data.por_asesor.length
        ? data.por_asesor.map(a => `
          <tr>
            <td>${escapeHTML(a.nombre)}</td>
            <td>${a.conversaciones_atendidas}</td>
            <td>${Math.round(a.promedio_respuesta || 0)}s</td>
          </tr>
        `).join("")
        : '<tr><td colspan="3" class="empty">No hay datos por asesor</td></tr>';
    }
  } catch (error) {
    console.error("Error cargando reportes:", error);
  }
}

async function loadTopSeller() {
  try {
    const response = await authenticatedFetch(`${API_URL}/reportes/ventas`);
    const data = await response.json();

    if (data.ok && data.ventas && data.ventas.length > 0) {
      const topSeller = data.ventas[0]; // Already ordered by ventas DESC
      if (topSeller.ventas > 0) {
        $("topSeller").textContent = topSeller.asesor_nombre.split(' ')[0]; // First name only
        $("topSellerSales").textContent = `${topSeller.ventas} ventas`;
      } else {
        $("topSeller").textContent = "—";
        $("topSellerSales").textContent = "Sin ventas";
      }
    } else {
      $("topSeller").textContent = "—";
      $("topSellerSales").textContent = "Sin datos";
    }
  } catch (error) {
    console.error("Error cargando mejor vendedor:", error);
    $("topSeller").textContent = "—";
    $("topSellerSales").textContent = "Error";
  }
}

async function cargarResumen() {
  if (isLoading) {
    console.log("⏸️ Ya hay una actualización en curso, omitiendo...");
    return;
  }

  isLoading = true;
  const button = $("refresh");
  button.disabled = true;
  $("feedback").className = "";
  $("feedback").textContent = "Actualizando información...";

  try {
    const response = await authenticatedFetch(API_URL, { cache: "no-store" });
    if (!response.ok) {
      if (response.status === 401 || response.status === 403) {
        logout();
        return;
      }
      throw new Error("El servidor respondió HTTP " + response.status);
    }

    const data = await response.json();
    if (!data.ok) throw new Error(data.error || "La API devolvió un error.");

    $("activeCount").textContent = data.resumen.conversaciones_activas ?? 0;
    $("queueCount").textContent = data.resumen.en_cola ?? 0;
    $("availableCount").textContent = data.resumen.asesores_disponibles ?? 0;

    // Cargar mejor vendedor
    await loadTopSeller();

    // Poblar selector de clientes omnicanal
    if (data.clientes_panel && data.clientes_panel.length > 0) {
      const select = $("clientSelect");
      select.innerHTML = '<option value="">Todos los clientes</option>';
      data.clientes_panel.forEach(cliente => {
        const option = document.createElement('option');
        option.value = cliente.id;
        option.textContent = cliente.nombre;
        select.appendChild(option);
      });
      $("clientSelectorHeader").hidden = false;
    }

    mostrarAsesores(data.asesores || []);
    mostrarConversaciones(data.conversaciones || []);
    mostrarCola(data.cola || []);

    $("connection").textContent = "● API conectada";
    $("connection").style.color = "#8ce2b8";
    $("feedback").textContent = "Datos actualizados correctamente · " +
      new Date().toLocaleTimeString("es-PE");

    // Indicador visual de actualización
    document.querySelectorAll(".metric").forEach(el => {
      el.classList.add("updating");
      setTimeout(() => el.classList.remove("updating"), 1000);
    });
  } catch (error) {
    console.error("Error cargando el panel:", error);
    $("connection").textContent = "● Sin conexión con la API";
    $("connection").style.color = "#ffaaaa";
    $("feedback").className = "error";
    $("feedback").textContent =
      "No se pudieron cargar los datos. Verifica que Node.js esté ejecutándose en el puerto 3000. Detalle: " +
      error.message;
  } finally {
    button.disabled = false;
    isLoading = false;
  }
}

document.querySelectorAll(".nav").forEach(button => {
  button.addEventListener("click", () => {
    document.querySelectorAll(".nav").forEach(item => item.classList.remove("active"));
    button.classList.add("active");

    const view = button.dataset.view;
    $("heading").textContent = view === "Resumen" ? "Resumen de atención" : view;
    $("subtitle").textContent = {
      "Resumen": "Indicadores actualizados desde MySQL.",
      "Conversaciones": "Últimas conversaciones y su estado actual.",
      "Cola de atención": "Clientes pendientes de asignación.",
      "Asesores": "Disponibilidad y carga del equipo comercial.",
      "Reportes": "Métricas comerciales y análisis de desempeño."
    }[view];

    const target = {
      "Resumen": null,
      "Conversaciones": "conversationPanel",
      "Cola de atención": "queuePanel",
      "Asesores": "advisorPanel",
      "Reportes": "reportsPanel"
    }[view];

    if (target) $(target).scrollIntoView({ behavior: "smooth", block: "start" });
    else window.scrollTo({ top: 0, behavior: "smooth" });

    // Cargar reportes si es la vista de reportes
    if (view === "Reportes") {
      cargarReportes();
    }
  });
});


const historyModal = $("historyModal");

async function abrirHistorial(conversacionId, telefono) {
      window.conversacionHistorialActual = conversacionId;
  historyModal.hidden = false;

  $("historyTitle").textContent =
    `Conversación #${conversacionId}`;

  $("historySubtitle").textContent =
    telefono ? `Cliente: ${telefono}` : "Historial de mensajes";

  $("historyMessages").innerHTML =
    '<div class="history-status">Consultando historial...</div>';

  $("activityTimeline").innerHTML =
    '<div class="history-status">Consultando actividad...</div>';

  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/mensajes`,
      { cache: "no-store" }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo consultar el historial.");
    }

    if (!data.mensajes.length) {
      $("historyMessages").innerHTML =
        '<div class="history-status">Esta conversación todavía no tiene mensajes.</div>';
      return;
    }

    $("historyMessages").innerHTML = data.mensajes.map(m => {
      const remitente = ["cliente", "bot", "asesor"].includes(m.remitente)
        ? m.remitente
        : "bot";

      const nombres = {
        cliente: "Cliente",
        bot: "Bot Claro",
        asesor: "Asesor"
      };

      const fecha = m.fecha
        ? new Date(m.fecha).toLocaleString("es-PE")
        : "";

      return `
        <article class="chat-message ${remitente}">
          <strong>${nombres[remitente]}</strong>
          <p>${escapeHTML(m.mensaje)}</p>
          <time>${escapeHTML(fecha)}</time>
        </article>
      `;
    }).join("");

    $("historySubtitle").textContent =
      `${data.total} mensaje(s) · Cliente: ${telefono || "Sin teléfono"}`;

    $("historyMessages").scrollTop =
      $("historyMessages").scrollHeight;

    // Cargar historial de actividad
    loadActivityTimeline(conversacionId);

  } catch (error) {
    console.error("Error consultando historial:", error);

    $("historyMessages").innerHTML =
      `<div class="history-status">${escapeHTML(error.message)}</div>`;
  }
}

async function loadActivityTimeline(conversacionId) {
  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/historial`,
      { cache: "no-store" }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo consultar el historial de actividad.");
    }

    if (!data.historial.length) {
      $("activityTimeline").innerHTML =
        '<div class="history-status">No hay actividad registrada.</div>';
      return;
    }

    const actionLabels = {
      asignacion: '📋 Asignación',
      reasignacion: '🔄 Reasignación',
      finalizacion: '✕ Finalización',
      respuesta: '💬 Respuesta'
    };

    $("activityTimeline").innerHTML = data.historial.map(h => {
      const fecha = h.fecha
        ? new Date(h.fecha).toLocaleString("es-PE")
        : "";

      return `
        <div class="activity-item ${h.tipo_accion}">
          <div class="activity-time">${escapeHTML(fecha)}</div>
          <div class="activity-desc">
            <strong>${actionLabels[h.tipo_accion] || h.tipo_accion}</strong>
            ${h.asesor_nombre ? ` - ${escapeHTML(h.asesor_nombre)}` : ''}
            <br>
            ${escapeHTML(h.descripcion || '')}
          </div>
        </div>
      `;
    }).join("");

  } catch (error) {
    console.error("Error consultando actividad:", error);
    $("activityTimeline").innerHTML =
      `<div class="history-status">${escapeHTML(error.message)}</div>`;
  }
}

// Abrir el historial al pulsar el botón de una conversación.
$("conversationRows").addEventListener("click", event => {
  const button = event.target.closest("[data-history-id]");

  if (!button) return;

  abrirHistorial(
    Number(button.dataset.historyId),
    button.dataset.historyPhone
  );
});

// Transferir conversación
$("conversationRows").addEventListener("click", async (event) => {
  const button = event.target.closest("[data-transfer-id]");

  if (!button) return;

  const conversacionId = Number(button.dataset.transferId);
  const asesorActual = button.dataset.transferAsesor;

  const asesorDestino = prompt(`Transferir conversación #${conversacionId}\nActualmente atendida por: ${asesorActual}\n\nIngresa el ID del asesor destino:`);

  if (!asesorDestino) return;

  if (!confirm(`¿Transferir conversación #${conversacionId} al asesor ID ${asesorDestino}?`)) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${conversacionId}/transferir`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ asesor_destino_id: Number(asesorDestino) })
    });

    const data = await response.json();

    if (data.ok) {
      alert("Conversación transferida correctamente");
      cargarResumen();
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error transfiriendo conversación:", error);
    alert("Error al transferir conversación");
  }
});

// Event listeners para responder como asesor
const sendReplyButton = document.getElementById("sendReply");
const advisorReplyInput = document.getElementById("advisorReply");
const replyStatus = document.getElementById("replyStatus");

sendReplyButton.addEventListener("click", enviarRespuestaAsesor);

advisorReplyInput.addEventListener("keydown", event => {
  if (event.key === "Enter" && !event.shiftKey) {
    event.preventDefault();
    enviarRespuestaAsesor();
  }
});

async function enviarRespuestaAsesor() {
  const conversacionId = window.conversacionHistorialActual;
  const mensaje = advisorReplyInput.value.trim();

  if (!conversacionId) {
    replyStatus.textContent = "No hay una conversación seleccionada.";
    return;
  }

  if (!mensaje) {
    replyStatus.textContent = "Escribe un mensaje antes de enviarlo.";
    advisorReplyInput.focus();
    return;
  }

  sendReplyButton.disabled = true;
  replyStatus.textContent = "Enviando respuesta...";

  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/responder`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ mensaje })
      }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo enviar la respuesta.");
    }

    advisorReplyInput.value = "";
    replyStatus.textContent = "Respuesta enviada correctamente.";

    // Actualiza el historial con el mensaje recién guardado.
    const responseHistory = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/mensajes`,
      { cache: "no-store" }
    );

    const historyData = await responseHistory.json();

    if (!responseHistory.ok || !historyData.ok) {
      throw new Error("La respuesta se guardó, pero no se pudo actualizar el historial.");
    }

    $("historyMessages").innerHTML = historyData.mensajes.map(m => {
      const remitente = ["cliente", "bot", "asesor"].includes(m.remitente)
        ? m.remitente
        : "bot";

      const nombres = {
        cliente: "Cliente",
        bot: "Bot Claro",
        asesor: "Asesor"
      };

      const fecha = m.fecha
        ? new Date(m.fecha).toLocaleString("es-PE")
        : "";

      return `
        <article class="chat-message ${remitente}">
          <strong>${nombres[remitente]}</strong>
          <p>${escapeHTML(m.mensaje)}</p>
          <time>${escapeHTML(fecha)}</time>
        </article>
      `;
    }).join("");

    $("historySubtitle").textContent =
      `${historyData.total} mensaje(s)`;

    $("historyMessages").scrollTop =
      $("historyMessages").scrollHeight;

    replyStatus.textContent = "Respuesta enviada correctamente.";

  } catch (error) {
    console.error("Error enviando respuesta:", error);
    replyStatus.textContent = error.message;
  } finally {
    sendReplyButton.disabled = false;
  }
}

// Event listener para finalizar conversación
const finishConversationButton = document.getElementById("finishConversation");

finishConversationButton.addEventListener("click", finalizarAtencion);

async function finalizarAtencion() {
  const conversacionId = window.conversacionHistorialActual;

  if (!conversacionId) {
    alert("No hay una conversación seleccionada.");
    return;
  }

  const confirmar = confirm(
    `¿Deseas finalizar la atención de la conversación #${conversacionId}?`
  );

  if (!confirmar) return;

  finishConversationButton.disabled = true;
  finishConversationButton.textContent = "Finalizando...";

  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/finalizar`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        }
      }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo finalizar la atención.");
    }

    alert("La atención se finalizó correctamente.");

    document.getElementById("historyModal").hidden = true;
    window.conversacionHistorialActual = null;

    await cargarResumen();

  } catch (error) {
    console.error("Error al finalizar la atención:", error);
    alert("No se pudo finalizar la atención: " + error.message);
  } finally {
    finishConversationButton.disabled = false;
    finishConversationButton.textContent = "Finalizar atención";
  }
}

// Cerrar la ventana.
$("closeHistory").addEventListener("click", () => {
  historyModal.hidden = true;
});

historyModal.addEventListener("click", event => {
  if (event.target === historyModal) {
    historyModal.hidden = true;
  }
});

document.addEventListener("keydown", event => {
  if (event.key === "Escape" && !historyModal.hidden) {
    historyModal.hidden = true;
  }
});

$("refresh").addEventListener("click", cargarResumen);

// ======================================================
// CONEXIÓN SSE PARA ACTUALIZACIONES EN TIEMPO REAL
// ======================================================

let eventSource = null;
let isLoading = false;

function conectarSSE() {
  if (eventSource) {
    eventSource.close();
  }

  eventSource = new EventSource(SSE_URL);

  eventSource.addEventListener("connected", (event) => {
    console.log("✅ Conectado a SSE:", event.data);
    $("connection").textContent = "● API conectada (tiempo real)";
    $("connection").style.color = "#8ce2b8";
  });

  eventSource.addEventListener("nueva_conversacion", async (event) => {
    console.log("📱 Nueva conversación:", event.data);
    if (!isLoading) {
      await cargarResumen();
    }
  });

  eventSource.addEventListener("nuevo_mensaje", async (event) => {
    console.log("💬 Nuevo mensaje:", event.data);
    if (!isLoading) {
      await cargarResumen();
    }
  });

  eventSource.addEventListener("asesor_asignado", async (event) => {
    console.log("👨‍💼 Asesor asignado:", event.data);
    if (!isLoading) {
      await cargarResumen();
    }
  });

  eventSource.addEventListener("asesor_liberado", async (event) => {
    console.log("🔄 Asesor liberado:", event.data);
    if (!isLoading) {
      await cargarResumen();
    }
  });

  eventSource.addEventListener("cola_actualizada", async (event) => {
    console.log("⏳ Cola actualizada:", event.data);
    if (!isLoading) {
      await cargarResumen();
    }
  });

  eventSource.onerror = (error) => {
    console.error("❌ Error en conexión SSE:", error);
    $("connection").textContent = "● Error en conexión tiempo real";
    $("connection").style.color = "#ffaaaa";
  };
}

function desconectarSSE() {
  if (eventSource) {
    eventSource.close();
    eventSource = null;
  }
}

// ======================================================
// INICIALIZACIÓN
// ======================================================

checkAuth();

// Fallback: mantener polling cada 30 segundos como respaldo
setInterval(() => {
  if (authToken && (!eventSource || eventSource.readyState === EventSource.CLOSED)) {
    cargarResumen();
  }
}, 30000);
</script>
</body>
</html>
