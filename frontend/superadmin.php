<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel - Super Admin</title>
<style>
:root {
  --loyaltel-blue: #0066CC;
  --loyaltel-light-blue: #3399FF;
  --loyaltel-dark: #000000;
  --bg: #f8f9fa;
  --white: #fff;
  --text: #000000;
  --muted: #666666;
  --border: #e0e0e0;
  --green: #28a745;
  --red: #dc3545;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "Segoe UI", Arial, sans-serif; background: var(--bg); color: var(--text); }

.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: var(--bg);
}
.login-box {
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  width: 400px;
  text-align: center;
}
.login-box .logo {
  width: 120px;
  height: auto;
  margin-bottom: 20px;
}
.login-box h2 { 
  font-size: 24px; 
  font-weight: 800; 
  margin-bottom: 8px; 
  color: var(--loyaltel-dark);
}
.login-box p {
  color: var(--muted);
  font-size: 14px;
  text-align: center;
  margin-bottom: 24px;
}
.login-box input {
  width: 100%;
  padding: 12px;
  margin-bottom: 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
}
.login-box input:focus {
  outline: none;
  border-color: var(--loyaltel-blue);
}
.login-box button {
  width: 100%;
  padding: 12px;
  background: var(--loyaltel-blue);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 700;
}
.login-box button:hover { background: var(--loyaltel-light-blue); }
.error { color: var(--red); margin-top: 12px; font-size: 13px; text-align: center; }

.dashboard { display: none; padding: 0; min-height: 100vh; }
.dashboard.visible { display: block; }
.header {
  background: var(--loyaltel-dark);
  color: white;
  padding: 18px 28px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.brand { display: flex; align-items: center; gap: 12px; font-weight: 800; }
.logo {
  width: 120px; height: auto;
  display: block;
}
.brand small { display: block; font-size: 10px; margin-top: 4px; color: #b3b3b3; }
.header h1 { margin: 0; font-size: 20px; }
.header button {
  background: var(--red);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
}
.header button:hover { background: #c82333; }

.layout {
  display: grid;
  grid-template-columns: 220px minmax(0,1fr);
  min-height: calc(100vh - 78px);
}
.sidebar {
  background: white;
  padding: 24px 14px;
  border-right: 1px solid var(--border);
}
.nav-label {
  font-size: 10px;
  color: var(--muted);
  font-weight: 800;
  letter-spacing: 1px;
  padding: 8px 10px 15px;
}
.nav {
  display: block;
  width: 100%;
  padding: 12px;
  margin-bottom: 5px;
  text-align: left;
  border: 0;
  border-radius: 9px;
  background: transparent;
  color: var(--muted);
  cursor: pointer;
  font-size: 13px;
}
.nav.active {
  background: #e8f0fe;
  color: var(--loyaltel-blue);
  font-weight: 800;
}

.content { padding: 28px; }
.content h2 { 
  font-size: 28px; 
  font-weight: 800; 
  margin-bottom: 8px; 
  color: var(--loyaltel-dark);
}
.content p {
  color: var(--muted);
  font-size: 14px;
  margin-bottom: 24px;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}
.users-table th, .users-table td {
  padding: 13px 12px;
  text-align: left;
  border-bottom: 1px solid var(--border);
}
.users-table th { 
  background: #f8f9fb; 
  color: var(--muted);
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 800;
}
.users-table tr:last-child td { border-bottom: none; }
.users-table tr:hover { background: #f9f9f9; }

.btn {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-right: 5px;
  font-size: 12px;
  font-weight: 700;
}
.btn-edit { background: var(--loyaltel-blue); color: white; }
.btn-edit:hover { background: var(--loyaltel-light-blue); }
.btn-delete { background: var(--red); color: white; }
.btn-delete:hover { background: #c82333; }
.btn-new { background: var(--loyaltel-blue); color: white; margin-bottom: 20px; padding: 11px 15px; font-weight: 700; }
.btn-new:hover { background: var(--loyaltel-light-blue); }

.modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal.active { display: flex; }
.modal-content {
  background: white;
  padding: 30px;
  border-radius: 12px;
  width: 400px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.modal-content h3 { 
  margin-top: 0; 
  margin-bottom: 20px;
  font-size: 18px;
  font-weight: 800;
  color: var(--loyaltel-dark);
}
.modal-content input {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
}
.modal-content input:focus {
  outline: none;
  border-color: var(--loyaltel-blue);
}
.modal-content select {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
}
.modal-content select:focus {
  outline: none;
  border-color: var(--loyaltel-blue);
}
.modal-content button {
  margin-right: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 700;
}
.btn-save { background: var(--loyaltel-blue); color: white; }
.btn-save:hover { background: var(--loyaltel-light-blue); }
.btn-cancel { background: var(--border); color: var(--text); }
.btn-cancel:hover { background: #d0d0d0; }
</style>
</head>
<body>

<div class="login-container" id="loginContainer">
  <div class="login-box">
    <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo">
    <h2>Super Administrador</h2>
    <p>Ingresa tus credenciales para acceder</p>
    <input type="text" id="username" placeholder="Usuario">
    <input type="password" id="password" placeholder="Contrasena">
    <button onclick="login()">Iniciar Sesion</button>
    <div class="error" id="error"></div>
  </div>
</div>

<div class="dashboard" id="dashboard">
  <div class="header">
    <div class="brand">
      <img src="logo-loyaltel.png" alt="Loyaltel Logo" class="logo">
      <div>LOYALTEL<small>PANEL DE SUPER ADMINISTRADOR</small></div>
    </div>
    <button onclick="logout()">Cerrar Sesion</button>
  </div>
  <div class="layout">
    <aside class="sidebar">
      <div class="nav-label">GESTION</div>
      <button class="nav active" data-view="usuarios" onclick="showView('usuarios')">Usuarios</button>
      <button class="nav" data-view="clientes" onclick="showView('clientes')">Clientes</button>
      <button class="nav" data-view="campanas" onclick="showView('campanas')">Campañas WhatsApp</button>
    </aside>
    <div class="content">
      <div id="view-usuarios">
        <h2>Gestion de Usuarios</h2>
        <p>Crear y gestionar cuentas de supervisor y asesores.</p>
        <button class="btn btn-new" onclick="openModal()">+ Nuevo Usuario</button>
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
        <tr><td colspan="5">Cargando...</td></tr>
      </tbody>
    </table>
      </div>
      <div id="view-clientes" style="display: none;">
        <h2>Gestion de Clientes</h2>
        <p>Crear y gestionar clientes (Claro, Win, etc.).</p>
        <button class="btn btn-new" onclick="openClientModal()">+ Nuevo Cliente</button>
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
            <tr><td colspan="4">Cargando clientes...</td></tr>
          </tbody>
        </table>
      </div>
      <div id="view-campanas" style="display: none;">
        <h2>Campañas WhatsApp</h2>
        <p>Configurar integración con WhatsApp Business Platform por cliente.</p>
        <button class="btn btn-new" onclick="openCampaignModal()">+ Nueva Campaña</button>
        <button class="btn btn-new" onclick="openImportModal()">📥 Importar Conversaciones</button>
        <table class="users-table">
          <thead>
            <tr>
              <th>Cliente</th>
              <th>Nombre</th>
              <th>Número WhatsApp</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="campaignsBody">
            <tr><td colspan="5">Cargando campañas...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="clientModal">
  <div class="modal-content">
    <h3 id="clientModalTitle">Nuevo Cliente</h3>
    <input type="hidden" id="clientId">
    <input type="text" id="clientNombre" placeholder="Nombre del cliente">
    <input type="text" id="clientCodigo" placeholder="Código (ej: CLARO, WIN)">
    <select id="clientEstado">
      <option value="activo">Activo</option>
      <option value="inactivo">Inactivo</option>
    </select>
    <button class="btn-save" onclick="saveClient()">Guardar</button>
    <button class="btn-cancel" onclick="closeClientModal()">Cancelar</button>
  </div>
</div>

<div class="modal" id="userModal">
  <div class="modal-content">
    <h3 id="modalTitle">Nuevo Usuario</h3>
    <input type="hidden" id="userId">
    <input type="text" id="userName" placeholder="Nombre">
    <input type="text" id="userTelefono" placeholder="Telefono">
    <input type="text" id="userUsuario" placeholder="Usuario">
    <input type="password" id="userPassword" placeholder="Contrasena">
    <select id="userRol" onchange="toggleAsesorFields()">
      <option value="asesor">Asesor</option>
      <option value="supervisor">Supervisor</option>
    </select>
    <select id="userEstado">
      <option value="disponible">Disponible</option>
      <option value="ocupado">Ocupado</option>
      <option value="desconectado">Desconectado</option>
    </select>
    <div id="asesorFields" style="display: none;">
      <select id="userCliente" onchange="loadCampaignsForClient()">
        <option value="">Seleccionar cliente (opcional)</option>
      </select>
      <select id="userCampana">
        <option value="">Seleccionar campaña (opcional)</option>
      </select>
    </div>
    <button class="btn-save" onclick="saveUser()">Guardar</button>
    <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
  </div>
</div>

<div class="modal" id="importModal">
  <div class="modal-content">
    <h3>Importar Conversaciones de WhatsApp</h3>
    <p style="font-size: 12px; color: var(--muted); margin-bottom: 15px;">
      Importa conversaciones históricas exportadas de WhatsApp Business.
      Solo archivos JSON con el formato de exportación de Meta.
    </p>
    <select id="importCampaign">
      <option value="">Seleccionar campaña</option>
    </select>
    <textarea id="importData" placeholder='Pega aquí el JSON exportado de WhatsApp Business...
Ejemplo:
[
  {
    "from": "51987654321",
    "text": {"body": "Hola"},
    "timestamp": "2024-01-01T10:00:00Z"
  }
]' rows="10" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 11px;"></textarea>
    <button class="btn-save" onclick="importConversations()">Importar</button>
    <button class="btn-cancel" onclick="closeImportModal()">Cancelar</button>
  </div>
</div>

<div class="modal" id="campaignModal">
  <div class="modal-content">
    <h3 id="campaignModalTitle">Nueva Campaña</h3>
    <input type="hidden" id="campaignId">
    <select id="campaignCliente">
      <option value="">Seleccionar cliente</option>
    </select>
    <input type="text" id="campaignNombre" placeholder="Nombre de la campaña">
    <input type="text" id="campaignDesc" placeholder="Descripción">
    <input type="text" id="campaignNumero" placeholder="Número WhatsApp (ej: 51987654321)">
    <input type="text" id="campaignApiKey" placeholder="API Key (opcional)">
    <input type="text" id="campaignPhoneId" placeholder="Phone ID (opcional)">
    <input type="text" id="campaignAccessToken" placeholder="Access Token (opcional)">
    <input type="text" id="campaignWebhook" placeholder="Webhook URL (opcional)">
    <input type="text" id="campaignSecret" placeholder="Webhook Secret (opcional)">
    <label style="display:block;margin:10px 0 5px;font-size:13px;">
      <input type="checkbox" id="campaignActivo" checked> Activo
    </label>
    <button class="btn-save" onclick="saveCampaign()">Guardar</button>
    <button class="btn-cancel" onclick="closeCampaignModal()">Cancelar</button>
  </div>
</div>

<script>
const API_BASE = 'http://localhost:3000/api';
let token = localStorage.getItem('superAdminToken');

// Timeout de inactividad: 15 minutos
const INACTIVITY_TIMEOUT = 15 * 60 * 1000; // 15 minutos en milisegundos
let inactivityTimer;

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(() => {
    // Limpiar localStorage y redirigir a index.php
    localStorage.removeItem('superAdminAuthToken');
    localStorage.removeItem('superAdminCurrentUser');
    window.location.href = 'index.php';
  }, INACTIVITY_TIMEOUT);
}

// Resetear timer en cualquier actividad del usuario
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keypress', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);
document.addEventListener('scroll', resetInactivityTimer);

// Iniciar timer al cargar la página (solo si hay token)
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
  const username = $('username').value;
  const password = $('password').value;

  if (!username || !password) {
    $('error').textContent = 'Usuario y contrasena requeridos';
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
      $('error').textContent = 'Credenciales invalidas o no tienes acceso';
    }
  } catch (err) {
    $('error').textContent = 'Error de conexion';
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
      $('usersBody').innerHTML = '<tr><td colspan="5">Error cargando usuarios</td></tr>';
    }
  } catch (err) {
    $('usersBody').innerHTML = '<tr><td colspan="5">Error de conexion</td></tr>';
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
      $('campaignsBody').innerHTML = '<tr><td colspan="5">Error cargando campañas</td></tr>';
    }
  } catch (err) {
    $('campaignsBody').innerHTML = '<tr><td colspan="5">Error de conexion</td></tr>';
  }
}

function renderCampaigns(campañas) {
  const html = campañas.map(c => `
    <tr>
      <td>${c.cliente_nombre}</td>
      <td>${c.nombre}</td>
      <td>${c.numero_whatsapp}</td>
      <td>${c.activo ? 'Activo' : 'Inactivo'}</td>
      <td>
        <button class="btn btn-edit" onclick="editCampaign(${c.id})">Editar</button>
        <button class="btn btn-delete" onclick="deleteCampaign(${c.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('campaignsBody').innerHTML = html;
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
      $('clientsBody').innerHTML = '<tr><td colspan="4">Error cargando clientes</td></tr>';
    }
  } catch (err) {
    $('clientsBody').innerHTML = '<tr><td colspan="4">Error de conexion</td></tr>';
  }
}

function renderClients(clientes) {
  const html = clientes.map(c => `
    <tr>
      <td>${c.nombre}</td>
      <td>${c.codigo}</td>
      <td>${c.estado}</td>
      <td>
        <button class="btn btn-edit" onclick="editClient(${c.id})">Editar</button>
        <button class="btn btn-delete" onclick="deleteClient(${c.id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('clientsBody').innerHTML = html;
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
    nombre: $('clientNombre').value,
    codigo: $('clientCodigo').value,
    estado: $('clientEstado').value
  };

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
      // Recargar lista de clientes para campañas
      loadClientes();
      alert(clientId ? 'Cliente actualizado' : 'Cliente creado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexion');
  }
}

function editClient(id) {
  // Implementación simplificada
  openClientModal(true);
  $('clientId').value = id;
}

async function deleteClient(id) {
  if (!confirm('¿Estas seguro de eliminar este cliente?')) return;

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
    alert('Error de conexion');
  }
}

function renderUsers(users) {
  const html = users.map(u => `
    <tr>
      <td>${u.nombre}</td>
      <td>${u.usuario}</td>
      <td>${u.rol}</td>
      <td>${u.estado}</td>
      <td>
        ${u.usuario !== 'superadmin' ? `
          <button class="btn btn-edit" onclick="editUser(${u.id})">Editar</button>
          <button class="btn btn-delete" onclick="deleteUser(${u.id})">Eliminar</button>
        ` : '-'}
      </td>
    </tr>
  `).join('');

  $('usersBody').innerHTML = html;
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
  const asesorFields = $('asesorFields');
  asesorFields.style.display = rol === 'asesor' ? 'block' : 'none';
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
  }
}

function closeModal() {
  $('userModal').classList.remove('active');
}

async function saveUser() {
  const userId = $('userId').value;
  const userData = {
    nombre: $('userName').value,
    telefono: $('userTelefono').value,
    usuario: $('userUsuario').value,
    password: $('userPassword').value,
    rol: $('userRol').value,
    estado: $('userEstado').value
  };

  // Agregar cliente y campaña si es asesor
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
      alert(userId ? 'Usuario actualizado' : 'Usuario creado');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexion');
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
      $('userRol').value = cells[2].textContent;
      $('userEstado').value = cells[3].textContent;
      $('userPassword').value = ''; // No mostrar contrasena existente
      openModal(true);
      break;
    }
  }
}

async function deleteUser(id) {
  if (!confirm('¿Estas seguro de eliminar este usuario?')) return;

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
    alert('Error de conexion');
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
    alert('Selecciona una campaña');
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
      alert('Error: El JSON no es válido');
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
    nombre: $('campaignNombre').value,
    descripcion: $('campaignDesc').value,
    numero_whatsapp: $('campaignNumero').value,
    api_key: $('campaignApiKey').value,
    phone_id: $('campaignPhoneId').value,
    access_token: $('campaignAccessToken').value,
    webhook_url: $('campaignWebhook').value,
    webhook_secret: $('campaignSecret').value,
    activo: $('campaignActivo').checked
  };

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
      alert(campaignId ? 'Campaña actualizada' : 'Campaña creada');
    } else {
      alert('Error: ' + data.error);
    }
  } catch (err) {
    alert('Error de conexion');
  }
}

function editCampaign(id) {
  // Implementación simplificada - en producción se debería cargar los datos desde la API
  openCampaignModal(true);
  $('campaignId').value = id;
}

async function deleteCampaign(id) {
  if (!confirm('¿Estas seguro de eliminar esta campaña?')) return;

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
    alert('Error de conexion');
  }
}

checkAuth();
</script>
</body>
</html>
