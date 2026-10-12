<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel · Super Administrador</title>
<link rel="stylesheet" href="css/modern.css">
<style>
:root {
  --loyaltel-blue: #2563eb;
  --loyaltel-light-blue: #3b82f6;
  --loyaltel-dark: #0f172a;
  --bg: #f8fafc;
  --white: #fff;
  --text: #0f172a;
  --muted: #64748b;
  --border: #e2e8f0;
  --green: #10b981;
  --red: #ef4444;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
}

/* LOGIN SCREEN */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: radial-gradient(circle at 50% 0%, #1e293b 0%, #0f172a 60%, #020617 100%);
  padding: 20px;
}

.login-box {
  background: rgba(30, 41, 59, 0.85);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
  width: min(420px, 100%);
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.login-box .logo {
  height: 48px;
  width: auto;
  margin-bottom: 24px;
  filter: brightness(0) invert(1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));
}

.login-box h2 { 
  font-size: 22px; 
  font-weight: 800; 
  margin-bottom: 8px; 
  color: white;
}

.login-box p {
  color: #94a3b8;
  font-size: 13px;
  text-align: center;
  margin-bottom: 24px;
}

.login-box input {
  width: 100%;
  padding: 12px 16px;
  margin-bottom: 14px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  background: rgba(15, 23, 42, 0.6);
  border-radius: 10px;
  font-size: 14px;
  color: white;
  outline: none;
  font-family: inherit;
  transition: all 0.2s;
}

.login-box input:focus {
  outline: none;
  border-color: #8b5cf6;
  background: rgba(15, 23, 42, 0.9);
  box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25);
}

.login-box input::placeholder {
  color: #64748b;
}

.login-box button {
  width: 100%;
  padding: 13px;
  background: linear-gradient(135deg, #7c3aed, #6366f1);
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 700;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.login-box button:hover {
  background: linear-gradient(135deg, #6d28d9, #4f46e5);
  transform: translateY(-1px);
}

.error {
  color: #f87171;
  margin-top: 14px;
  font-size: 12.5px;
  text-align: center;
  background: rgba(239, 68, 68, 0.15);
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid rgba(239, 68, 68, 0.25);
}

/* DASHBOARD MAIN */
.dashboard { display: none; padding: 0; min-height: 100vh; }
.dashboard.visible { display: block; }

.header {
  background: var(--loyaltel-dark);
  color: white;
  padding: 12px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.brand { display: flex; align-items: center; gap: 14px; }

.brand .logo {
  height: 38px;
  width: auto;
  display: block;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
}

.brand-text {
  font-weight: 800;
  font-size: 15px;
  letter-spacing: 0.5px;
  display: flex;
  flex-direction: column;
}

.brand small {
  display: block;
  font-size: 10px;
  margin-top: 2px;
  color: #94a3b8;
  letter-spacing: 1px;
  font-weight: 600;
}

.header button {
  background: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #fca5a5;
  padding: 7px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 12.5px;
  font-weight: 600;
  transition: all 0.2s;
}

.header button:hover {
  background: var(--red);
  color: white;
}

.layout {
  display: grid;
  grid-template-columns: 240px minmax(0, 1fr);
  min-height: calc(100vh - 63px);
}

.sidebar {
  background: white;
  padding: 20px 14px;
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
}

.nav-label {
  font-size: 11px;
  color: var(--muted);
  font-weight: 800;
  letter-spacing: 1.2px;
  padding: 8px 12px 14px;
  text-transform: uppercase;
}

.nav {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 11px 14px;
  margin-bottom: 4px;
  text-align: left;
  border: 0;
  border-radius: 10px;
  background: transparent;
  color: #475569;
  cursor: pointer;
  font-size: 13.5px;
  font-weight: 600;
  font-family: inherit;
  transition: all 0.2s;
}

.nav:hover {
  background: #f1f5f9;
  color: var(--text);
}

.nav.active {
  background: #f3e8ff;
  color: #7c3aed;
  font-weight: 700;
}

.content {
  padding: 28px 32px;
  background: #f8fafc;
}

.content h2 { 
  font-size: 26px; 
  font-weight: 800; 
  margin-bottom: 4px; 
  color: var(--text);
  letter-spacing: -0.5px;
}

.content p {
  color: var(--muted);
  font-size: 14px;
  margin-bottom: 22px;
}

.action-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
  gap: 12px;
}

.users-table-container {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.users-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}

.users-table th, .users-table td {
  padding: 13px 18px;
  text-align: left;
}

.users-table th { 
  background: #f8fafc; 
  color: var(--muted);
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 0.5px;
  border-bottom: 1px solid var(--border);
}

.users-table td {
  border-top: 1px solid var(--border);
  vertical-align: middle;
}

.users-table tr:hover td {
  background: #f8fafc;
}

/* Action Buttons */
.btn {
  padding: 6px 12px;
  border: 1px solid transparent;
  border-radius: 6px;
  cursor: pointer;
  margin-right: 5px;
  font-size: 12px;
  font-weight: 700;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.btn-edit {
  background: var(--primary-light);
  color: var(--primary);
  border-color: var(--primary-border);
}
.btn-edit:hover {
  background: var(--primary);
  color: white;
}

.btn-delete {
  background: var(--danger-light);
  color: var(--danger-text);
  border-color: var(--danger-border);
}
.btn-delete:hover {
  background: var(--red);
  color: white;
}

.btn-new {
  background: var(--loyaltel-blue);
  color: white;
  padding: 10px 18px;
  font-weight: 700;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
  font-size: 13px;
}
.btn-new:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}

/* MODAL STYLES */
.modal {
  display: none;
  position: fixed;
  inset: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.modal.active { display: flex; }

.modal-content {
  background: white;
  padding: 28px;
  border-radius: 18px;
  width: min(480px, 100%);
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
  border: 1px solid var(--border);
  animation: modal-zoom-in 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-content h3 { 
  margin-top: 0; 
  margin-bottom: 18px; 
  font-size: 18px; 
  font-weight: 800; 
  color: var(--text);
}

.modal-content input, .modal-content select {
  width: 100%;
  padding: 10px 14px;
  margin: 6px 0 14px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 13.5px;
  font-family: inherit;
  outline: none;
  background: #f8fafc;
  transition: all 0.2s;
}

.modal-content input:focus, .modal-content select:focus {
  border-color: var(--loyaltel-blue);
  background: white;
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.btn-save {
  background: var(--loyaltel-blue);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 700;
  transition: all 0.2s;
}
.btn-save:hover { background: #1d4ed8; }

.btn-cancel {
  background: white;
  border: 1px solid var(--border);
  color: var(--muted);
  padding: 10px 18px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 600;
}
.btn-cancel:hover { background: #f1f5f9; color: var(--text); }
</style>
</head>
<body>

<div class="login-container" id="loginContainer">
  <div class="login-box">
    <a href="https://www.loyaltel.pe/" target="_blank" rel="noopener noreferrer" class="brand-logo-link" title="Visitar Loyaltel (loyaltel.pe)">
      <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo" onerror="this.src='logo-loyaltel.png'">
    </a>
    <h2>Super Administrador</h2>
    <p>Ingresa tus credenciales maestras para acceder</p>
    <input type="text" id="username" placeholder="Usuario">
    <input type="password" id="password" placeholder="Contraseña">
    <button onclick="login()">Iniciar Sesión</button>
    <div class="error" id="error" style="display:none;"></div>
  </div>
</div>

<div class="dashboard" id="dashboard">
  <div class="header">
    <div class="brand">
      <a href="https://www.loyaltel.pe/" target="_blank" rel="noopener noreferrer" class="brand-logo-link" title="Visitar Loyaltel (loyaltel.pe)">
        <img src="logo-loyaltel.png" alt="Loyaltel Logo" class="logo">
      </a>
      <div class="brand-text">
        LOYALTEL
        <small>PANEL DE SUPER ADMINISTRADOR</small>
      </div>
    </div>
    <button onclick="logout()">Cerrar Sesión</button>
  </div>

  <div class="layout">
    <aside class="sidebar">
      <div class="nav-label">Administración</div>
      <button class="nav active" data-view="usuarios" onclick="showView('usuarios')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        Usuarios
      </button>
      <button class="nav" data-view="clientes" onclick="showView('clientes')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 21h18"></path>
          <path d="M9 8h1"></path>
          <path d="M9 12h1"></path>
          <path d="M9 16h1"></path>
          <path d="M14 8h1"></path>
          <path d="M14 12h1"></path>
          <path d="M14 16h1"></path>
          <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
        </svg>
        Clientes
      </button>
      <button class="nav" data-view="campanas" onclick="showView('campanas')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
        </svg>
        Campañas WhatsApp
      </button>
    </aside>

    <div class="content">
      <!-- VISTA USUARIOS -->
      <div id="view-usuarios">
        <div class="action-bar">
          <div>
            <h2>Gestión de Usuarios</h2>
            <p>Crear y administrar cuentas de supervisores y asesores del contact center.</p>
          </div>
          <button class="btn btn-new" onclick="openModal()">+ Nuevo Usuario</button>
        </div>

        <div class="users-table-container">
          <table class="users-table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="usersBody">
              <tr><td colspan="5" class="empty">Cargando usuarios...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- VISTA CLIENTES -->
      <div id="view-clientes" style="display: none;">
        <div class="action-bar">
          <div>
            <h2>Gestión de Clientes</h2>
            <p>Crear y gestionar empresas clientes (Claro, Win, etc.).</p>
          </div>
          <button class="btn btn-new" onclick="openClientModal()">+ Nuevo Cliente</button>
        </div>

        <div class="users-table-container">
          <table class="users-table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="clientsBody">
              <tr><td colspan="4" class="empty">Cargando clientes...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- VISTA CAMPAÑAS -->
      <div id="view-campanas" style="display: none;">
        <div class="action-bar">
          <div>
            <h2>Campañas WhatsApp Business</h2>
            <p>Configurar integración de números y credenciales WhatsApp por cliente.</p>
          </div>
          <div style="display:flex; gap:10px;">
            <button class="btn btn-new" onclick="openCampaignModal()">+ Nueva Campaña</button>
            <button class="btn btn-new" onclick="openImportModal()" style="background:#059669;">📥 Importar Conversaciones</button>
          </div>
        </div>

        <div class="users-table-container">
          <table class="users-table">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Nombre Campaña</th>
                <th>Número WhatsApp</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="campaignsBody">
              <tr><td colspan="5" class="empty">Cargando campañas...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CLIENTE -->
<div class="modal" id="clientModal">
  <div class="modal-content">
    <h3 id="clientModalTitle">Nuevo Cliente</h3>
    <input type="hidden" id="clientId">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Nombre de la empresa</label>
    <input type="text" id="clientNombre" placeholder="Ej: Claro Perú">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Código de identificación</label>
    <input type="text" id="clientCodigo" placeholder="Ej: CLARO, WIN">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Estado</label>
    <select id="clientEstado">
      <option value="activo">Activo</option>
      <option value="inactivo">Inactivo</option>
    </select>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeClientModal()">Cancelar</button>
      <button class="btn-save" onclick="saveClient()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL USUARIO -->
<div class="modal" id="userModal">
  <div class="modal-content">
    <h3 id="modalTitle">Nuevo Usuario</h3>
    <input type="hidden" id="userId">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Nombre completo</label>
    <input type="text" id="userName" placeholder="Nombre completo">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Teléfono de contacto</label>
    <input type="text" id="userTelefono" placeholder="Teléfono">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Nombre de usuario</label>
    <input type="text" id="userUsuario" placeholder="Usuario para iniciar sesión">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Contraseña</label>
    <input type="password" id="userPassword" placeholder="Contraseña">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Rol</label>
    <select id="userRol" onchange="toggleAsesorFields()">
      <option value="asesor">Asesor</option>
      <option value="supervisor">Supervisor</option>
    </select>
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Estado inicial</label>
    <select id="userEstado">
      <option value="disponible">Disponible</option>
      <option value="ocupado">Ocupado</option>
      <option value="desconectado">Desconectado</option>
    </select>
    <div id="asesorFields" style="display: none;">
      <label style="font-size:12px; font-weight:700; color:var(--muted);">Asignar a Cliente</label>
      <select id="userCliente" onchange="loadCampaignsForClient()">
        <option value="">Seleccionar cliente (opcional)</option>
      </select>
      <label style="font-size:12px; font-weight:700; color:var(--muted);">Asignar a Campaña</label>
      <select id="userCampana">
        <option value="">Seleccionar campaña (opcional)</option>
      </select>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
      <button class="btn-save" onclick="saveUser()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL IMPORTACIÓN -->
<div class="modal" id="importModal">
  <div class="modal-content" style="max-width: 560px;">
    <h3>Importar Conversaciones de WhatsApp</h3>
    <p style="font-size: 13px; color: var(--muted); margin-bottom: 15px;">
      Importa mensajes históricos en formato JSON exportado de Meta WhatsApp Business.
    </p>
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Campaña de destino</label>
    <select id="importCampaign">
      <option value="">Seleccionar campaña</option>
    </select>
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Datos JSON</label>
    <textarea id="importData" placeholder='Pega aquí el JSON exportado...' rows="9" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-family: monospace; font-size: 11.5px;"></textarea>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeImportModal()">Cancelar</button>
      <button class="btn-save" onclick="importConversations()" style="background:#059669;">Importar Mensajes</button>
    </div>
  </div>
</div>

<!-- MODAL CAMPAÑA -->
<div class="modal" id="campaignModal">
  <div class="modal-content" style="max-width: 560px; max-height:90vh; overflow-y:auto;">
    <h3 id="campaignModalTitle">Nueva Campaña</h3>
    <input type="hidden" id="campaignId">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Cliente asociado</label>
    <select id="campaignCliente">
      <option value="">Seleccionar cliente</option>
    </select>
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Nombre de la campaña</label>
    <input type="text" id="campaignNombre" placeholder="Ej: Claro Ventas Hogar">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Descripción</label>
    <input type="text" id="campaignDesc" placeholder="Descripción de la campaña">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Número de WhatsApp Business</label>
    <input type="text" id="campaignNumero" placeholder="Ej: 51987654321">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">API Key (opcional)</label>
    <input type="text" id="campaignApiKey" placeholder="API Key">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Phone ID (opcional)</label>
    <input type="text" id="campaignPhoneId" placeholder="Phone ID de Meta">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Access Token (opcional)</label>
    <input type="text" id="campaignAccessToken" placeholder="Token permanente de Meta">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Webhook URL (opcional)</label>
    <input type="text" id="campaignWebhook" placeholder="URL para recibir webhooks">
    <label style="font-size:12px; font-weight:700; color:var(--muted);">Webhook Secret (opcional)</label>
    <input type="text" id="campaignSecret" placeholder="Token de verificación">
    <label style="display:flex; align-items:center; gap:8px; margin:10px 0; font-size:13px; font-weight:600;">
      <input type="checkbox" id="campaignActivo" checked style="width:auto; margin:0;"> Campaña Activa
    </label>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeCampaignModal()">Cancelar</button>
      <button class="btn-save" onclick="saveCampaign()">Guardar Campaña</button>
    </div>
  </div>
</div>

<script>
const API_BASE = 'http://localhost:3000/api';
let token = localStorage.getItem('superAdminToken');

const INACTIVITY_TIMEOUT = 15 * 60 * 1000;
let inactivityTimer;

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(() => {
    localStorage.removeItem('superAdminToken');
    window.location.href = 'index.php';
  }, INACTIVITY_TIMEOUT);
}

document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keypress', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);
document.addEventListener('scroll', resetInactivityTimer);

if (token) {
  resetInactivityTimer();
}

const $ = id => document.getElementById(id);

function checkAuth() {
  if (token) {
    showDashboard();
  }
}

function showDashboard() {
  $('loginContainer').style.display = 'none';
  $('dashboard').classList.add('visible');
  loadUsers();
  loadClientes();
  loadCampaigns();
  loadClients();
  loadClientesForModal();
}

function showLogin() {
  $('loginContainer').style.display = 'flex';
  $('dashboard').classList.remove('visible');
}

function showView(view) {
  document.querySelectorAll('.nav').forEach(btn => btn.classList.remove('active'));
  document.querySelector(`[data-view="${view}"]`).classList.add('active');

  $('view-usuarios').style.display = view === 'usuarios' ? 'block' : 'none';
  $('view-clientes').style.display = view === 'clientes' ? 'block' : 'none';
  $('view-campanas').style.display = view === 'campanas' ? 'block' : 'none';

  if (view === 'clientes') loadClients();
}

async function login() {
  const username = $('username').value.trim();
  const password = $('password').value;

  if (!username || !password) {
    $('error').style.display = 'block';
    $('error').textContent = 'Usuario y contraseña requeridos';
    return;
  }

  try {
    const res = await fetch(`${API_BASE}/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ usuario: username, password })
    });

    const data = await res.json();

    if (data.ok && data.usuario.rol === 'super_admin') {
      token = data.token;
      localStorage.setItem('superAdminToken', token);
      showDashboard();
    } else {
      $('error').style.display = 'block';
      $('error').textContent = 'Credenciales inválidas o no tienes permisos de super admin';
    }
  } catch (err) {
    $('error').style.display = 'block';
    $('error').textContent = 'Error de conexión con el servidor';
  }
}

function logout() {
  localStorage.removeItem('superAdminToken');
  token = null;
  showLogin();
}

async function loadUsers() {
  try {
    const res = await fetch(`${API_BASE}/usuarios`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      renderUsers(data.usuarios);
    } else {
      $('usersBody').innerHTML = '<tr><td colspan="5" class="empty">Error cargando usuarios</td></tr>';
    }
  } catch (err) {
    $('usersBody').innerHTML = '<tr><td colspan="5" class="empty">Error de conexión</td></tr>';
  }
}

async function loadClientes() {
  try {
    const res = await fetch(`${API_BASE}/clientes_panel/lista`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      const select = $('campaignCliente');
      select.innerHTML = '<option value="">Seleccionar cliente</option>';
      data.clientes.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.nombre;
        select.appendChild(option);
      });
    }
  } catch (err) {
    console.error('Error cargando clientes:', err);
  }
}

async function loadCampaigns() {
  try {
    const res = await fetch(`${API_BASE}/campañas_whatsapp`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      renderCampaigns(data.campañas);
    } else {
      $('campaignsBody').innerHTML = '<tr><td colspan="5" class="empty">Error cargando campañas</td></tr>';
    }
  } catch (err) {
    $('campaignsBody').innerHTML = '<tr><td colspan="5" class="empty">Error de conexión</td></tr>';
  }
}

function renderCampaigns(campañas) {
  const html = campañas.map(c => `
    <tr>
      <td><strong>${c.cliente_nombre}</strong></td>
      <td>${c.nombre}</td>
      <td>📱 ${c.numero_whatsapp}</td>
      <td><span class="badge ${c.activo ? 'badge-success' : 'badge-danger'}">${c.activo ? '● Activa' : '○ Inactiva'}</span></td>
      <td>
        <button class="btn btn-edit" onclick="editCampaign(${c.id})">Editar</button>
        <button class="btn btn-delete" onclick="deleteCampaign(${c.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('campaignsBody').innerHTML = html || '<tr><td colspan="5" class="empty">No hay campañas registradas</td></tr>';
}

async function loadClients() {
  try {
    const res = await fetch(`${API_BASE}/clientes_panel`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      renderClients(data.clientes);
    } else {
      $('clientsBody').innerHTML = '<tr><td colspan="4" class="empty">Error cargando clientes</td></tr>';
    }
  } catch (err) {
    $('clientsBody').innerHTML = '<tr><td colspan="4" class="empty">Error de conexión</td></tr>';
  }
}

function renderClients(clientes) {
  const html = clientes.map(c => `
    <tr>
      <td><strong>${c.nombre}</strong></td>
      <td><span class="badge badge-primary">${c.codigo}</span></td>
      <td><span class="badge ${c.estado === 'activo' ? 'badge-success' : 'badge-danger'}">${c.estado}</span></td>
      <td>
        <button class="btn btn-edit" onclick="editClient(${c.id})">Editar</button>
        <button class="btn btn-delete" onclick="deleteClient(${c.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('clientsBody').innerHTML = html || '<tr><td colspan="4" class="empty">No hay clientes registrados</td></tr>';
}

function openClientModal(isEdit = false) {
  $('clientModal').classList.add('active');
  $('clientModalTitle').textContent = isEdit ? 'Editar Cliente' : 'Nuevo Cliente';
  if (!isEdit) {
    $('clientId').value = '';
    $('clientNombre').value = '';
    $('clientCodigo').value = '';
    $('clientEstado').value = 'activo';
  }
}

function closeClientModal() {
  $('clientModal').classList.remove('active');
}

async function saveClient() {
  const clientId = $('clientId').value;
  const clientData = {
    nombre: $('clientNombre').value.trim(),
    codigo: $('clientCodigo').value.trim(),
    estado: $('clientEstado').value
  };

  if (!clientData.nombre || !clientData.codigo) {
    alert("Por favor completa nombre y código");
    return;
  }

  try {
    const url = clientId ? `${API_BASE}/clientes_panel/${clientId}` : `${API_BASE}/clientes_panel`;
    const method = clientId ? 'PUT' : 'POST';

    const res = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(clientData)
    });

    const data = await res.json();

    if (data.ok) {
      closeClientModal();
      loadClients();
      loadClientes();
      alert(clientId ? '✅ Cliente actualizado' : '✅ Cliente creado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

function editClient(id) {
  openClientModal(true);
  $('clientId').value = id;
}

async function deleteClient(id) {
  if (!confirm('¿Estás seguro de eliminar este cliente?')) return;

  try {
    const res = await fetch(`${API_BASE}/clientes_panel/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      loadClients();
      loadClientes();
      alert('Cliente eliminado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

function renderUsers(users) {
  const roleBadges = {
    super_admin: '<span class="badge" style="background:#f3e8ff; color:#7c3aed; border:1px solid #d8b4fe;">👑 Super Admin</span>',
    supervisor: '<span class="badge badge-primary">👨‍💼 Supervisor</span>',
    asesor: '<span class="badge badge-success">🎧 Asesor</span>'
  };

  const html = users.map(u => `
    <tr>
      <td><strong>${u.nombre}</strong></td>
      <td><code>${u.usuario}</code></td>
      <td>${roleBadges[u.rol] || u.rol}</td>
      <td><span class="badge ${u.estado === 'disponible' ? 'badge-success' : (u.estado === 'ocupado' ? 'badge-warning' : 'badge-neutral')}">${u.estado}</span></td>
      <td>
        ${u.usuario !== 'superadmin' ? `
          <button class="btn btn-edit" onclick="editUser(${u.id})">Editar</button>
          <button class="btn btn-delete" onclick="deleteUser(${u.id})">Eliminar</button>
        ` : '<span style="color:var(--muted); font-size:12px;">Cuenta principal</span>'}
      </td>
    </tr>
  `).join('');

  $('usersBody').innerHTML = html || '<tr><td colspan="5" class="empty">No hay usuarios registrados</td></tr>';
}

async function loadClientesForModal() {
  try {
    const res = await fetch(`${API_BASE}/clientes_panel/lista`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      const select = $('userCliente');
      select.innerHTML = '<option value="">Seleccionar cliente (opcional)</option>';
      data.clientes.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.nombre;
        select.appendChild(option);
      });
    }
  } catch (err) {
    console.error('Error cargando clientes:', err);
  }
}

async function loadCampaignsForClient() {
  const clienteId = $('userCliente').value;
  const select = $('userCampana');
  
  if (!clienteId) {
    select.innerHTML = '<option value="">Seleccionar campaña (opcional)</option>';
    return;
  }

  try {
    const res = await fetch(`${API_BASE}/campañas_whatsapp`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      select.innerHTML = '<option value="">Seleccionar campaña (opcional)</option>';
      data.campañas
        .filter(c => c.cliente_panel_id == clienteId)
        .forEach(c => {
          const option = document.createElement('option');
          option.value = c.id;
          option.textContent = c.nombre;
          select.appendChild(option);
        });
    }
  } catch (err) {
    console.error('Error cargando campañas:', err);
  }
}

function toggleAsesorFields() {
  const rol = $('userRol').value;
  $('asesorFields').style.display = rol === 'asesor' ? 'block' : 'none';
}

function openModal(isEdit = false) {
  $('userModal').classList.add('active');
  $('modalTitle').textContent = isEdit ? 'Editar Usuario' : 'Nuevo Usuario';
  if (!isEdit) {
    $('userId').value = '';
    $('userName').value = '';
    $('userTelefono').value = '';
    $('userUsuario').value = '';
    $('userPassword').value = '';
    $('userRol').value = 'asesor';
    $('userEstado').value = 'disponible';
    toggleAsesorFields();
  }
}

function closeModal() {
  $('userModal').classList.remove('active');
}

async function saveUser() {
  const userId = $('userId').value;
  const userData = {
    nombre: $('userName').value.trim(),
    telefono: $('userTelefono').value.trim(),
    usuario: $('userUsuario').value.trim(),
    password: $('userPassword').value,
    rol: $('userRol').value,
    estado: $('userEstado').value
  };

  if (userData.rol === 'asesor') {
    userData.cliente_panel_id = $('userCliente').value || null;
    userData.campaña_whatsapp_id = $('userCampana').value || null;
  }

  try {
    const url = userId ? `${API_BASE}/usuarios/${userId}` : `${API_BASE}/usuarios`;
    const method = userId ? 'PUT' : 'POST';

    const res = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(userData)
    });

    const data = await res.json();

    if (data.ok) {
      closeModal();
      loadUsers();
      alert(userId ? '✅ Usuario actualizado' : '✅ Usuario creado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

function editUser(id) {
  const usersTable = $('usersBody');
  const rows = usersTable.querySelectorAll('tr');
  
  for (let row of rows) {
    const editBtn = row.querySelector('.btn-edit');
    if (editBtn && editBtn.getAttribute('onclick').includes(id)) {
      const cells = row.querySelectorAll('td');
      $('userId').value = id;
      $('userName').value = cells[0].textContent;
      $('userUsuario').value = cells[1].textContent;
      $('userPassword').value = '';
      openModal(true);
      break;
    }
  }
}

async function deleteUser(id) {
  if (!confirm('¿Estás seguro de eliminar este usuario?')) return;

  try {
    const res = await fetch(`${API_BASE}/usuarios/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      loadUsers();
      alert('Usuario eliminado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

function openImportModal() {
  $('importModal').classList.add('active');
  loadCampaignsForImport();
}

function closeImportModal() {
  $('importModal').classList.remove('active');
}

async function loadCampaignsForImport() {
  try {
    const res = await fetch(`${API_BASE}/campañas_whatsapp`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      const select = $('importCampaign');
      select.innerHTML = '<option value="">Seleccionar campaña</option>';
      data.campañas.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = `${c.cliente_nombre} - ${c.nombre}`;
        select.appendChild(option);
      });
    }
  } catch (err) {
    console.error('Error cargando campañas:', err);
  }
}

async function importConversations() {
  const campañaId = $('importCampaign').value;
  const jsonData = $('importData').value;

  if (!campañaId) {
    alert('Selecciona una campaña de destino');
    return;
  }

  if (!jsonData.trim()) {
    alert('Pega el JSON de las conversaciones');
    return;
  }

  try {
    const datos = JSON.parse(jsonData);

    if (!Array.isArray(datos)) {
      alert('El JSON debe ser un array de mensajes');
      return;
    }

    const res = await fetch(`${API_BASE}/importar/whatsapp`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ datos, campaña_id: campañaId })
    });

    const data = await res.json();

    if (data.ok) {
      alert(`Importación exitosa:\n- Conversaciones creadas: ${data.conversaciones_creadas}\n- Mensajes importados: ${data.mensajes_importados}`);
      closeImportModal();
      $('importData').value = '';
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    if (err instanceof SyntaxError) {
      alert('Error: El formato JSON no es válido');
    } else {
      alert('Error de conexión');
    }
  }
}

function openCampaignModal(isEdit = false) {
  $('campaignModal').classList.add('active');
  $('campaignModalTitle').textContent = isEdit ? 'Editar Campaña' : 'Nueva Campaña';
  if (!isEdit) {
    $('campaignId').value = '';
    $('campaignCliente').value = '';
    $('campaignNombre').value = '';
    $('campaignDesc').value = '';
    $('campaignNumero').value = '';
    $('campaignApiKey').value = '';
    $('campaignPhoneId').value = '';
    $('campaignAccessToken').value = '';
    $('campaignWebhook').value = '';
    $('campaignSecret').value = '';
    $('campaignActivo').checked = true;
  }
}

function closeCampaignModal() {
  $('campaignModal').classList.remove('active');
}

async function saveCampaign() {
  const campaignId = $('campaignId').value;
  const campaignData = {
    cliente_panel_id: $('campaignCliente').value,
    nombre: $('campaignNombre').value.trim(),
    descripcion: $('campaignDesc').value.trim(),
    numero_whatsapp: $('campaignNumero').value.trim(),
    api_key: $('campaignApiKey').value.trim(),
    phone_id: $('campaignPhoneId').value.trim(),
    access_token: $('campaignAccessToken').value.trim(),
    webhook_url: $('campaignWebhook').value.trim(),
    webhook_secret: $('campaignSecret').value.trim(),
    activo: $('campaignActivo').checked
  };

  if (!campaignData.cliente_panel_id || !campaignData.nombre || !campaignData.numero_whatsapp) {
    alert("Por favor completa cliente, nombre y número de WhatsApp");
    return;
  }

  try {
    const url = campaignId ? `${API_BASE}/campañas_whatsapp/${campaignId}` : `${API_BASE}/campañas_whatsapp`;
    const method = campaignId ? 'PUT' : 'POST';

    const res = await fetch(url, {
      method,
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(campaignData)
    });

    const data = await res.json();

    if (data.ok) {
      closeCampaignModal();
      loadCampaigns();
      alert(campaignId ? '✅ Campaña actualizada' : '✅ Campaña creada');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

function editCampaign(id) {
  openCampaignModal(true);
  $('campaignId').value = id;
}

async function deleteCampaign(id) {
  if (!confirm('¿Estás seguro de eliminar esta campaña?')) return;

  try {
    const res = await fetch(`${API_BASE}/campañas_whatsapp/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });

    const data = await res.json();

    if (data.ok) {
      loadCampaigns();
      alert('Campaña eliminada');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexión');
  }
}

checkAuth();
</script>
</body>
</html>
