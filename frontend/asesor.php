<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel | Espacio de trabajo del asesor</title>
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
  --orange: #d97706;
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
.asesor-info { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
.client-selector-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
}
.client-selector-header label {
  font-size: 11px;
  font-weight: 700;
  color: #b3b3b3;
}
.client-select {
  padding: 6px 10px;
  border: 1px solid #444;
  border-radius: 6px;
  font-size: 12px;
  background: #1a1a1a;
  color: white;
  min-width: 150px;
}
.client-select:focus {
  outline: none;
  border-color: var(--loyaltel-blue);
}
.asesor-name { font-weight: 700; }
.asesor-status { font-size: 12px; }
.status-indicator {
  display: inline-block; width: 10px; height: 10px;
  border-radius: 50%; margin-right: 6px;
}
.status-disponible { background: var(--green); }
.status-ocupado { background: var(--orange); }
.status-desconectado { background: var(--muted); }

.layout { display: grid; grid-template-columns: 320px minmax(0,1fr); min-height: calc(100vh - 78px); }
.sidebar { background: white; border-right: 1px solid var(--border); display: flex; flex-direction: column; }
.sidebar-header {
  padding: 18px 20px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.sidebar-title { font-size: 14px; font-weight: 800; color: var(--navy); }
.refresh-btn {
  border: 0; background: transparent; cursor: pointer;
  color: var(--muted); font-size: 18px; padding: 4px;
  border-radius: 6px;
}
.refresh-btn:hover { background: var(--bg); }

.conversation-list { flex: 1; overflow-y: auto; }
.conversation-item {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: background 0.2s;
}
.conversation-item:hover { background: #f8f9fb; }
.conversation-item.active { background: #edf1f8; border-left: 3px solid var(--navy); }
.conversation-header {
  display: flex; justify-content: space-between;
  align-items: flex-start; gap: 10px; margin-bottom: 8px;
}
.conversation-client { font-weight: 700; font-size: 13px; }
.conversation-phone { font-size: 11px; color: var(--muted); }
.conversation-time { font-size: 10px; color: var(--muted); }
.conversation-preview {
  font-size: 12px; color: var(--muted);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  margin-bottom: 6px;
}
.conversation-meta {
  display: flex; gap: 8px; font-size: 10px;
}
.pending-badge {
  background: var(--red); color: white;
  padding: 2px 6px; border-radius: 99px; font-weight: 700;
}
main { padding: 28px; min-width: 0; display: flex; flex-direction: column; }

.chat-container {
  flex: 1; display: flex; flex-direction: column;
  background: white; border: 1px solid var(--border);
  border-radius: 13px; overflow: hidden;
}
.chat-header {
  padding: 18px 20px; border-bottom: 1px solid var(--border);
  background: var(--navy); color: white;
  display: flex; justify-content: space-between; align-items: center;
}
.chat-header-info h2 { margin: 0; font-size: 16px; }
.chat-header-info small { display: block; margin-top: 4px; opacity: 0.8; font-size: 11px; }
.chat-actions { display: flex; gap: 10px; }
.chat-btn {
  border: 0; border-radius: 7px; padding: 8px 12px;
  cursor: pointer; font-size: 11px; font-weight: 700;
}
.btn-client-card {
  background: white; color: var(--navy);
  border: 2px solid var(--loyaltel-blue);
  padding: 10px 18px;
  font-weight: 700;
  border-radius: 8px;
}
.btn-client-card:hover {
  background: var(--loyaltel-blue);
  color: white;
}
.btn-finish { background: var(--red); color: white; }

.status-selector {
  display: flex; align-items: center; gap: 8px;
}
.status-select {
  padding: 6px 10px; border-radius: 6px;
  border: 1px solid rgba(255,255,255,0.3);
  background: rgba(255,255,255,0.1);
  color: white; font-size: 11px; cursor: pointer;
}
.status-select:focus { outline: none; border-color: white; }

.chat-messages {
  flex: 1; padding: 20px; overflow-y: auto;
  background: var(--bg); min-height: 300px;
}
.chat-message {
  max-width: 75%; margin-bottom: 15px; padding: 12px 14px;
  border-radius: 12px; background: white;
  border: 1px solid var(--border); overflow-wrap: anywhere;
}
.chat-message.cliente {
  margin-left: auto; background: #e5f6ee; border-color: #c7ead8;
}
.chat-message.bot, .chat-message.asesor {
  margin-right: auto;
}
.chat-message strong {
  display: block; margin-bottom: 6px; font-size: 11px; color: var(--navy);
}
.chat-message p { margin: 0; font-size: 13px; line-height: 1.55; white-space: pre-wrap; }
.chat-message time {
  display: block; margin-top: 8px; color: var(--muted); font-size: 10px;
}
.chat-message.external {
  border-color: #FF9500;
  background: #fff5e6;
}
.external-indicator {
  font-size: 14px; margin-left: 5px;
}

.chat-input {
  padding: 18px 20px; border-top: 1px solid var(--border);
  background: white;
}
.chat-input textarea {
  display: block; width: 100%; resize: vertical;
  min-height: 75px; padding: 12px;
  border: 1px solid var(--border); border-radius: 9px;
  background: var(--bg); color: var(--text);
  font: inherit; font-size: 13px; outline-offset: 2px;
}
.chat-input-footer {
  display: flex; justify-content: space-between;
  align-items: center; gap: 12px; margin-top: 10px;
}
.send-btn {
  background: var(--navy); color: white; border: 0;
  border-radius: 9px; padding: 11px 20px; font-weight: 700; cursor: pointer;
}
.send-btn:disabled { opacity: 0.55; cursor: not-allowed; }
.external-msg-btn {
  background: #FF9500; color: white; border: 0;
  border-radius: 9px; padding: 11px 20px; font-weight: 700; cursor: pointer;
  font-size: 12px;
}
.external-msg-btn:hover { background: #e68600; }
.input-status { font-size: 11px; color: var(--muted); }

.client-card {
  padding: 20px; background: white; border: 1px solid var(--border);
  border-radius: 13px; margin-bottom: 18px;
}
.client-card-header {
  display: flex; justify-content: space-between;
  align-items: center; margin-bottom: 16px;
}
.client-card-title { font-size: 14px; font-weight: 800; color: var(--navy); }
.close-card { border: 0; background: transparent; cursor: pointer; font-size: 18px; }
.client-field {
  display: grid; grid-template-columns: 120px 1fr;
  gap: 8px; margin-bottom: 12px; font-size: 13px;
}
.client-field label { color: var(--muted); font-weight: 600; }
.client-field span { color: var(--text); }
.client-field.editing { grid-template-columns: 1fr; }
.client-field.editing input {
  width: 100%; padding: 8px; border: 1px solid var(--border);
  border-radius: 6px; font-size: 13px;
}
.edit-client-btn {
  border: 2px solid var(--loyaltel-blue);
  background: white;
  color: var(--loyaltel-blue);
  cursor: pointer;
  font-size: 12px;
  font-weight: 700;
  padding: 8px 16px;
  border-radius: 6px;
  margin-top: 12px;
}
.edit-client-btn:hover {
  background: var(--loyaltel-blue);
  color: white;
}
.edit-input {
  width: 100%; padding: 8px; border: 1px solid var(--border);
  border-radius: 6px; font-size: 13px;
}

.status-controls {
  display: flex; gap: 8px;
}
/* Modal Styles */
.modal {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.5); display: flex;
  align-items: center; justify-content: center; z-index: 1000;
}
.modal-content {
  background: white; border-radius: 13px;
  max-width: 500px; width: 90%; max-height: 90vh;
  overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.modal-header {
  padding: 20px; border-bottom: 1px solid var(--border);
  display: flex; justify-content: space-between; align-items: center;
}
.modal-header h3 { margin: 0; font-size: 16px; color: var(--navy); }
.modal-close {
  border: 0; background: transparent; cursor: pointer;
  font-size: 24px; color: var(--muted);
}
.modal-close:hover { color: var(--navy); }
.modal-body { padding: 20px; }
.modal-body p { margin: 0 0 15px 0; font-size: 13px; color: var(--text); }
.modal-warning {
  background: #fff3cd; color: #856404; padding: 10px;
  border-radius: 7px; font-size: 12px; margin-bottom: 15px;
}
.modal-body textarea {
  width: 100%; padding: 10px; border: 1px solid var(--border);
  border-radius: 7px; font-size: 13px; resize: vertical;
  font-family: inherit;
}
.modal-footer {
  padding: 15px 20px; border-top: 1px solid var(--border);
  display: flex; justify-content: flex-end; gap: 10px;
}
.modal-btn {
  border: 0; border-radius: 7px; padding: 10px 20px;
  font-weight: 700; cursor: pointer; font-size: 13px;
}
.modal-btn-cancel {
  background: #edf0f4; color: var(--muted);
}
.modal-btn-cancel:hover { background: #dfe4e8; }
.modal-btn-confirm {
  background: var(--loyaltel-blue); color: white;
}
.modal-btn-confirm:hover { background: var(--loyaltel-light-blue); }

.status-btn {
  border: 0; border-radius: 7px; padding: 8px 12px;
  cursor: pointer; font-size: 11px; font-weight: 700;
  background: #edf0f4; color: var(--muted);
}
.status-btn.active { background: var(--navy); color: white; }
.status-btn.disponible.active { background: var(--green); }
.status-btn.ocupado.active { background: var(--orange); }
.status-btn.desconectado.active { background: var(--muted); }

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
.empty-state {
  padding: 40px 20px; text-align: center; color: var(--muted);
  font-size: 13px;
}
[hidden] { display: none !important; }
</style>
</head>
<body>

<!-- Pantalla de login -->
<div class="login-screen" id="loginScreen">
  <div class="login-card">
    <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo">
    <h1>👨‍💼 Espacio de trabajo del asesor</h1>
    <p>Ingresa tus credenciales para acceder</p>
    <input type="text" class="login-input" id="loginUsername" placeholder="Usuario" autocomplete="username">
    <input type="password" class="login-input" id="loginPassword" placeholder="Contraseña" autocomplete="current-password">
    <button class="login-btn" id="loginBtn">Iniciar sesión</button>
    <div class="login-error" id="loginError"></div>
  </div>
</div>

<!-- Interfaz principal -->
<header id="mainHeader" hidden>
  <div class="brand">
    <img src="logo-loyaltel.png" alt="Loyaltel Logo" class="logo">
    <div>LOYALTEL<small>ESPACIO DE TRABAJO DEL ASESOR</small></div>
  </div>
  <div class="asesor-info">
    <div>
      <span class="asesor-name" id="asesorName">—</span>
      <span class="asesor-status">
        <span class="status-indicator" id="statusIndicator"></span>
        <span id="statusText">—</span>
      </span>
    </div>
    <div class="client-selector-header" id="clientSelectorHeader" hidden>
      <label for="clientSelectAsesor">Cliente:</label>
      <select id="clientSelectAsesor" class="client-select">
        <option value="">Todos los clientes</option>
      </select>
    </div>
    <div class="status-controls">
      <button class="status-btn disponible" data-estado="disponible">Disponible</button>
      <button class="status-btn ocupado" data-estado="ocupado">Ocupado</button>
      <button class="status-btn desconectado" data-estado="desconectado">Desconectado</button>
    </div>
    <button class="logout-btn" id="logoutBtn">Cerrar sesión</button>
  </div>
</header>

<div class="layout" id="mainLayout" hidden>
  <aside class="sidebar">
    <div class="sidebar-header">
      <span class="sidebar-title">Mis conversaciones</span>
      <button class="refresh-btn" id="refreshBtn" title="Actualizar">↻</button>
    </div>
    <div class="conversation-list" id="conversationList">
      <div class="empty-state">Cargando conversaciones...</div>
    </div>
  </aside>

  <main>
    <!-- Ficha del cliente -->
    <div class="client-card" id="clientCard" hidden>
      <div class="client-card-header">
        <span class="client-card-title">📋 Ficha del cliente</span>
        <button class="close-card" id="closeCardBtn">×</button>
      </div>
      <div id="clientCardContent"></div>
    </div>

    <!-- Chat -->
    <div class="chat-container">
      <div class="chat-header" id="chatHeader" hidden>
        <div class="chat-header-info">
          <h2 id="chatClientName">—</h2>
          <small id="chatClientPhone">—</small>
        </div>
        <div class="chat-actions">
          <div class="status-selector">
            <select class="status-select" id="statusSelect" onchange="updateStatus()">
              <option value="">Estado</option>
              <option value="atendiendo">ATENDIENDO</option>
              <option value="venta">VENTA</option>
              <option value="volver_a_llamar">VOLVER A LLAMAR</option>
              <option value="cliente_potencial">Cliente potencial</option>
              <option value="nuevo_pedido">Nuevo pedido</option>
              <option value="sin_cobertura">Sin cobertura</option>
              <option value="falta_informacion">Falta información</option>
              <option value="no_desea">No desea</option>
              <option value="no_contesta">NO CONTESTA</option>
              <option value="numero_no_existe">Número no existe</option>
              <option value="no_aplica">NO APLICA</option>
              <option value="deuda">DEUDA</option>
              <option value="spam">SPAM</option>
              <option value="cobertura_en_proceso">COBERTURA EN PROCESO</option>
            </select>
          </div>
          <button class="chat-btn btn-client-card" id="showClientCardBtn">📋 Ficha</button>
          <button class="chat-btn btn-finish" id="finishConversationBtn">✕ Finalizar</button>
        </div>
      </div>

      <div class="chat-messages" id="chatMessages">
        <div class="empty-state">Selecciona una conversación para comenzar</div>
      </div>

      <div class="chat-input" id="chatInput" hidden>
        <textarea id="messageInput" placeholder="Escribe tu respuesta al cliente..." rows="3"></textarea>
        <div class="chat-input-footer">
          <span class="input-status" id="inputStatus">Presiona Enter para enviar</span>
          <button class="external-msg-btn" id="externalMsgBtn" title="Registrar mensaje enviado desde WhatsApp Web/Celular">📱 Registrar mensaje externo</button>
          <button class="send-btn" id="sendBtn">Enviar</button>
        </div>
      </div>

      <!-- Modal para registrar mensaje externo -->
      <div class="modal" id="externalMsgModal" hidden>
        <div class="modal-content">
          <div class="modal-header">
            <h3>📱 Registrar Mensaje Externo</h3>
            <button class="modal-close" id="closeExternalMsgModal">&times;</button>
          </div>
          <div class="modal-body">
            <p>Usa esta opción si respondiste al cliente desde <strong>WhatsApp Web</strong> o tu <strong>celular</strong> fuera del sistema.</p>
            <p class="modal-warning">⚠️ Este mensaje NO fue enviado por el sistema. Solo se registrará en el historial.</p>
            <textarea id="externalMessageInput" placeholder="Pega aquí el mensaje que enviaste al cliente..." rows="5"></textarea>
          </div>
          <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" id="cancelExternalMsg">Cancelar</button>
            <button class="modal-btn modal-btn-confirm" id="confirmExternalMsg">Registrar Mensaje</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
const API_BASE = "http://localhost:3000/api";
let currentAsesor = null;
let currentConversation = null;
let eventSource = null;
let authToken = localStorage.getItem('asesorAuthToken');
let currentUser = JSON.parse(localStorage.getItem('asesorCurrentUser') || 'null');

const $ = id => document.getElementById(id);

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
      if (data.usuario.rol !== 'asesor') {
        showLoginError("Esta cuenta no tiene rol de asesor");
        return;
      }

      authToken = data.token;
      currentUser = data.usuario;

      localStorage.setItem('asesorAuthToken', authToken);
      localStorage.setItem('asesorCurrentUser', JSON.stringify(currentUser));

      showMainInterface();
      setupAsesorInterface();
      loadConversations();
      connectSSE();
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
  localStorage.removeItem('asesorAuthToken');
  localStorage.removeItem('asesorCurrentUser');
  authToken = null;
  currentUser = null;
  currentAsesor = null;

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

// ======================================================
// CONFIGURAR INTERFAZ DEL ASESOR
// ======================================================

function setupAsesorInterface() {
  currentAsesor = currentUser;

  $("asesorName").textContent = currentAsesor.nombre;
  updateStatusUI(currentAsesor.estado);

  document.querySelectorAll(".status-btn").forEach(btn => {
    btn.addEventListener("click", () => changeStatus(btn.dataset.estado));
  });

  // Cargar clientes omnicanal
  loadClientesPanel();
}

async function loadClientesPanel() {
  try {
    const response = await authenticatedFetch(`${API_BASE}/clientes_panel/lista`);
    const data = await response.json();

    if (data.ok && data.clientes.length > 0) {
      const select = $("clientSelectAsesor");
      select.innerHTML = '<option value="">Todos los clientes</option>';
      data.clientes.forEach(cliente => {
        const option = document.createElement('option');
        option.value = cliente.id;
        option.textContent = cliente.nombre;
        select.appendChild(option);
      });
      $("clientSelectorHeader").hidden = false;
    }
  } catch (error) {
    console.error("Error cargando clientes panel:", error);
  }
}

function updateStatusUI(estado) {
  document.querySelectorAll(".status-btn").forEach(btn => {
    btn.classList.toggle("active", btn.dataset.estado === estado);
  });

  const indicator = $("statusIndicator");
  indicator.className = "status-indicator";
  indicator.classList.add(`status-${estado}`);

  const statusLabels = {
    disponible: "Disponible",
    ocupado: "Ocupado",
    desconectado: "Desconectado"
  };
  $("statusText").textContent = statusLabels[estado] || estado;
}

async function changeStatus(nuevoEstado) {
  if (!currentAsesor) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/asesor/${currentAsesor.id}/estado`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ estado: nuevoEstado })
    });

    const data = await response.json();

    if (data.ok) {
      currentAsesor.estado = nuevoEstado;
      updateStatusUI(nuevoEstado);
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error cambiando estado:", error);
    alert("Error al cambiar estado");
  }
}

// ======================================================
// CARGAR CONVERSACIONES
// ======================================================

async function loadConversations() {
  if (!currentAsesor) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/asesor/${currentAsesor.id}/conversaciones`);
    const data = await response.json();

    if (data.ok) {
      renderConversations(data.conversaciones);
    } else if (response.status === 401 || response.status === 403) {
      logout();
    }
  } catch (error) {
    console.error("Error cargando conversaciones:", error);
  }
}

function renderConversations(conversations) {
  const list = $("conversationList");

  if (conversations.length === 0) {
    list.innerHTML = '<div class="empty-state">No tienes conversaciones asignadas</div>';
    return;
  }

  list.innerHTML = conversations.map(c => {
    const time = c.fecha_ultimo_mensaje
      ? new Date(c.fecha_ultimo_mensaje).toLocaleTimeString("es-PE", { hour: '2-digit', minute: '2-digit' })
      : '—';

    const pendingBadge = c.mensajes_pendientes > 0
      ? `<span class="pending-badge">${c.mensajes_pendientes}</span>`
      : '';

    return `
      <div class="conversation-item" data-id="${c.conversacion_id}" data-phone="${escapeHTML(c.telefono)}">
        <div class="conversation-header">
          <div>
            <span class="conversation-client">${escapeHTML(c.cliente)}</span>
            <span class="conversation-phone">${escapeHTML(c.telefono)}</span>
          </div>
          <span class="conversation-time">${time}</span>
        </div>
        <div class="conversation-preview">${escapeHTML(c.ultimo_mensaje || 'Sin mensajes')}</div>
        <div class="conversation-meta">
          ${pendingBadge}
          <span>${c.estado_conversacion}</span>
        </div>
      </div>
    `;
  }).join("");

  list.querySelectorAll(".conversation-item").forEach(item => {
    item.addEventListener("click", () => {
      openConversation(
        Number(item.dataset.id),
        item.dataset.phone
      );
    });
  });
}

// ======================================================
// ABRIR CONVERSACIÓN
// ======================================================

async function openConversation(conversationId, telefono) {
  currentConversation = conversationId;

  // Resaltar conversación activa
  document.querySelectorAll(".conversation-item").forEach(item => {
    item.classList.toggle("active", Number(item.dataset.id) === conversationId);
  });

  // Mostrar header del chat
  $("chatHeader").hidden = false;
  $("chatInput").hidden = false;
  $("chatMessages").innerHTML = '<div class="empty-state">Cargando mensajes...</div>';

  // Cargar mensajes
  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${conversationId}/mensajes`);
    const data = await response.json();

    if (data.ok) {
      renderMessages(data.mensajes);
      loadClientData(telefono);
    }
  } catch (error) {
    console.error("Error cargando mensajes:", error);
    $("chatMessages").innerHTML = '<div class="empty-state">Error al cargar mensajes</div>';
  }
}

function renderMessages(messages) {
  const container = $("chatMessages");

  if (messages.length === 0) {
    container.innerHTML = '<div class="empty-state">No hay mensajes en esta conversación</div>';
    return;
  }

  container.innerHTML = messages.map(m => {
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

    // Indicador de mensaje externo
    const esExterno = m.origen_mensaje === 'externo';
    const indicadorExterno = esExterno
      ? '<span class="external-indicator" title="Enviado desde WhatsApp Web/Celular">📱</span>'
      : '';

    return `
      <article class="chat-message ${remitente} ${esExterno ? 'external' : ''}">
        <strong>${nombres[remitente]} ${indicadorExterno}</strong>
        <p>${escapeHTML(m.mensaje)}</p>
        <time>${escapeHTML(fecha)}</time>
      </article>
    `;
  }).join("");

  container.scrollTop = container.scrollHeight;
}

// ======================================================
// CARGAR DATOS DEL CLIENTE
// ======================================================

async function loadClientData(telefono) {
  $("chatClientName").textContent = "Cargando...";
  $("chatClientPhone").textContent = telefono;

  try {
    const response = await authenticatedFetch(`${API_BASE}/clientes/${telefono}`);
    const data = await response.json();

    if (data.ok) {
      const cliente = data.cliente;
      $("chatClientName").textContent = cliente.nombre || "Cliente sin nombre";
      renderClientCard(cliente);
    }
  } catch (error) {
    console.error("Error cargando datos del cliente:", error);
  }
}

function renderClientCard(cliente) {
  const fields = [
    { label: "Nombre", value: cliente.nombre, editable: true, field: 'nombre' },
    { label: "Teléfono", value: cliente.telefono, editable: false, field: 'telefono' },
    { label: "Distrito", value: cliente.distrito, editable: true, field: 'distrito' },
    { label: "Dirección", value: cliente.direccion, editable: true, field: 'direccion' },
    { label: "Origen", value: cliente.origen, editable: true, field: 'origen' },
    { label: "Campaña", value: cliente.campania, editable: true, field: 'campania' },
    { label: "Producto de interés", value: cliente.producto_interes, editable: true, field: 'producto_interes' },
    { label: "Observaciones", value: cliente.observaciones, editable: true, field: 'observaciones' }
  ];

  $("clientCardContent").innerHTML = fields.map(f => `
    <div class="client-field" data-field="${f.field}" data-editable="${f.editable}">
      <label>${escapeHTML(f.label)}:</label>
      <span class="field-value">${escapeHTML(f.value || '—')}</span>
    </div>
  `).join("") + `
    <button class="edit-client-btn" id="editClientBtn">✏️ Editar datos</button>
  `;

  // Agregar evento de edición
  $("editClientBtn").addEventListener("click", enableClientEditing);
}

function enableClientEditing() {
  document.querySelectorAll(".client-field[data-editable='true']").forEach(field => {
    const fieldName = field.dataset.field;
    const currentValue = field.querySelector(".field-value").textContent;

    field.classList.add("editing");
    field.innerHTML = `
      <label>${field.querySelector("label").textContent}</label>
      <input type="text" class="edit-input" data-field="${fieldName}" value="${currentValue === '—' ? '' : currentValue}">
    `;
  });

  $("editClientBtn").textContent = "💾 Guardar cambios";
  $("editClientBtn").onclick = saveClientData;
}

async function saveClientData() {
  const telefono = $("chatClientPhone").textContent;
  const updates = {};

  document.querySelectorAll(".edit-input").forEach(input => {
    updates[input.dataset.field] = input.value.trim() || null;
  });

  try {
    const response = await authenticatedFetch(`${API_BASE}/clientes/${telefono}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updates)
    });

    const data = await response.json();

    if (data.ok) {
      alert("Datos actualizados correctamente");
      loadClientData(telefono);
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error guardando datos:", error);
    alert("Error al guardar datos");
  }
}

// ======================================================
// ENVIAR MENSAJE
// ======================================================

$("sendBtn").addEventListener("click", sendMessage);
$("messageInput").addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
  }
});

// Event listeners para mensaje externo
$("externalMsgBtn").addEventListener("click", () => {
  $("externalMsgModal").hidden = false;
  $("externalMessageInput").value = "";
  $("externalMessageInput").focus();
});

$("closeExternalMsgModal").addEventListener("click", () => {
  $("externalMsgModal").hidden = true;
});

$("cancelExternalMsg").addEventListener("click", () => {
  $("externalMsgModal").hidden = true;
});

$("confirmExternalMsg").addEventListener("click", sendExternalMessage);

async function sendMessage() {
  if (!currentConversation) return;

  const mensaje = $("messageInput").value.trim();
  if (!mensaje) return;

  $("sendBtn").disabled = true;
  $("inputStatus").textContent = "Enviando...";

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/responder`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ mensaje })
    });

    const data = await response.json();

    if (data.ok) {
      $("messageInput").value = "";
      $("inputStatus").textContent = "Mensaje enviado";

      // Recargar mensajes
      const msgsResponse = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/mensajes`);
      const msgsData = await msgsResponse.json();

      if (msgsData.ok) {
        renderMessages(msgsData.mensajes);
      }
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error enviando mensaje:", error);
    alert("Error al enviar mensaje");
  } finally {
    $("sendBtn").disabled = false;
    $("inputStatus").textContent = "Presiona Enter para enviar";
  }
}

// ======================================================
// ENVIAR MENSAJE EXTERNO (WhatsApp Web/Celular)
// ======================================================

async function sendExternalMessage() {
  if (!currentConversation) return;

  const mensaje = $("externalMessageInput").value.trim();
  if (!mensaje) {
    alert("Por favor, ingresa el mensaje que enviaste al cliente.");
    return;
  }

  $("confirmExternalMsg").disabled = true;
  $("confirmExternalMsg").textContent = "Registrando...";

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/mensaje-externo`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ mensaje })
    });

    const data = await response.json();

    if (data.ok) {
      $("externalMsgModal").hidden = true;
      alert("✅ Mensaje externo registrado correctamente.\n\n⚠️ Recuerda: Este mensaje no fue enviado por el sistema. Lo respondiste desde WhatsApp Web/Celular.");

      // Recargar mensajes
      const msgsResponse = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/mensajes`);
      const msgsData = await msgsResponse.json();

      if (msgsData.ok) {
        renderMessages(msgsData.mensajes);
      }
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error registrando mensaje externo:", error);
    alert("Error al registrar mensaje externo");
  } finally {
    $("confirmExternalMsg").disabled = false;
    $("confirmExternalMsg").textContent = "Registrar Mensaje";
  }
}

// ======================================================
// ACTUALIZAR ESTADO DE NEGOCIO
// ======================================================

async function updateStatus() {
  if (!currentConversation) return;

  const estado = $("statusSelect").value;
  if (!estado) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/estado`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ estado_negocio: estado })
    });

    const data = await response.json();

    if (data.ok) {
      console.log("Estado actualizado:", estado);
    } else {
      alert("Error: " + data.error);
      $("statusSelect").value = "";
    }
  } catch (error) {
    console.error("Error actualizando estado:", error);
    alert("Error al actualizar estado");
    $("statusSelect").value = "";
  }
}

// ======================================================
// FINALIZAR CONVERSACIÓN
// ======================================================

$("finishConversationBtn").addEventListener("click", async () => {
  if (!currentConversation) return;

  if (!confirm("¿Estás seguro de finalizar esta conversación?")) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/finalizar`, {
      method: "POST"
    });

    const data = await response.json();

    if (data.ok) {
      alert("Conversación finalizada");
      currentConversation = null;
      $("chatHeader").hidden = true;
      $("chatInput").hidden = true;
      $("chatMessages").innerHTML = '<div class="empty-state">Selecciona una conversación para comenzar</div>';
      loadConversations();
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error finalizando conversación:", error);
    alert("Error al finalizar conversación");
  }
});

// ======================================================
// FICHA DEL CLIENTE
// ======================================================

$("showClientCardBtn").addEventListener("click", () => {
  $("clientCard").hidden = false;
});

$("closeCardBtn").addEventListener("click", () => {
  $("clientCard").hidden = true;
});

// ======================================================
// SSE
// ======================================================

function connectSSE() {
  if (eventSource) eventSource.close();

  eventSource = new EventSource(`${API_BASE}/panel/sse?token=${authToken}`);

  eventSource.addEventListener("nuevo_mensaje", () => {
    if (currentConversation) {
      openConversation(currentConversation, $("chatClientPhone").textContent);
    }
    loadConversations();
  });

  eventSource.addEventListener("asesor_estado_cambiado", (event) => {
    const data = JSON.parse(event.data);
    if (data.asesor_id === currentAsesor.id) {
      currentAsesor.estado = data.estado_nuevo;
      updateStatusUI(data.estado_nuevo);
    }
  });
}

// ======================================================
// INICIALIZACIÓN
// ======================================================

checkAuth();
$("refreshBtn").addEventListener("click", loadConversations);
</script>
</body>
</html>
