<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel · Panel de Supervisión Omnicanal</title>
<link rel="stylesheet" href="css/modern.css">
<style>
:root {
  --loyaltel-blue: #2563eb;
  --loyaltel-light-blue: #3b82f6;
  --loyaltel-dark: #0f172a;
  --loyaltel-gray: #1e293b;
  --bg: #f8fafc;
  --white: #ffffff;
  --text: #0f172a;
  --muted: #64748b;
  --border: #e2e8f0;
  --green: #10b981;
  --red: #ef4444;
  --sand: #d97706;
}

* { box-sizing: border-box; }
body {
  margin: 0;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
}

/* TOP HEADER */
header {
  background: var(--loyaltel-dark);
  color: white;
  padding: 12px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  position: sticky;
  top: 0;
  z-index: 40;
}

.brand {
  display: flex;
  align-items: center;
  gap: 14px;
}

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

.header-controls {
  display: flex;
  align-items: center;
  gap: 16px;
}

.client-selector-header {
  display: flex;
  align-items: center;
  gap: 8px;
}

.client-selector-header label {
  font-size: 12px;
  font-weight: 600;
  color: #94a3b8;
}

.client-select {
  padding: 6px 12px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 8px;
  font-size: 12px;
  background: #1e293b;
  color: white;
  min-width: 170px;
  outline: none;
  font-family: inherit;
  transition: border-color 0.2s;
}

.client-select:focus {
  border-color: #3b82f6;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.connection {
  font-size: 12px;
  color: #94a3b8;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
  padding: 4px 10px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: var(--radius-full);
}

.refresh-header-btn {
  background: rgba(37, 99, 235, 0.2);
  color: #60a5fa;
  border: 1px solid rgba(59, 130, 246, 0.3);
  border-radius: 8px;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.refresh-header-btn:hover {
  background: var(--loyaltel-blue);
  color: white;
}

.logout-btn {
  border: 0;
  background: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #fca5a5;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s ease;
}

.logout-btn:hover {
  background: var(--red);
  color: white;
}

/* LAYOUT */
.layout {
  display: grid;
  grid-template-columns: 240px minmax(0, 1fr);
  min-height: calc(100vh - 63px);
}

/* SIDEBAR */
aside {
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
  padding: 10px 14px;
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
  background: var(--primary-light);
  color: var(--loyaltel-blue);
  font-weight: 700;
}

.nav svg {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

/* MAIN CONTENT */
main {
  padding: 28px 32px;
  min-width: 0;
  background: #f8fafc;
}

.pagehead {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
}

h1 {
  margin: 0;
  font-size: 26px;
  font-weight: 800;
  letter-spacing: -0.5px;
  color: var(--text);
}

.subtitle {
  margin-top: 4px;
  color: var(--muted);
  font-size: 14px;
}

#feedback {
  background: white;
  border: 1px solid var(--border);
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 12px;
  color: var(--muted);
  margin-bottom: 20px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

#feedback.error {
  border-color: #fecaca;
  background: #fef2f2;
  color: #991b1b;
}

/* KPI METRIC CARDS */
.cards {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
  margin-bottom: 28px;
}

.metric {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px 22px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.metric::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
}

.metric:nth-child(1)::before { background: #3b82f6; }
.metric:nth-child(2)::before { background: #f59e0b; }
.metric:nth-child(3)::before { background: #10b981; }
.metric:nth-child(4)::before { background: #8b5cf6; }

.metric:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
  border-color: #cbd5e1;
}

.metric-top {
  color: var(--muted);
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.metric-value {
  font-size: 34px;
  font-weight: 800;
  margin-top: 10px;
  color: var(--text);
  line-height: 1;
}

.metric-note {
  color: var(--muted);
  font-size: 12px;
  margin-top: 8px;
  font-weight: 500;
}

/* TWO COLUMNS DASHBOARD */
.columns {
  display: grid;
  grid-template-columns: minmax(0, 1.55fr) minmax(320px, 1fr);
  gap: 24px;
  align-items: start;
}

.panel {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.panel-head {
  padding: 16px 22px;
  border-bottom: 1px solid var(--border);
  background: #fafafa;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-title {
  font-size: 15px;
  font-weight: 800;
  color: var(--text);
}

.panel-sub {
  color: var(--muted);
  font-size: 12px;
  margin-top: 2px;
}

.table-wrap {
  overflow-x: auto;
}

table {
  border-collapse: collapse;
  width: 100%;
  font-size: 13px;
}

th {
  background: #f8fafc;
  color: var(--muted);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-align: left;
  padding: 12px 18px;
  border-bottom: 1px solid var(--border);
}

td {
  padding: 13px 18px;
  border-top: 1px solid var(--border);
  vertical-align: middle;
  color: var(--text);
}

tr:hover td {
  background: #f8fafc;
}

/* Status Pills */
.pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border-radius: 99px;
  padding: 4px 10px;
  font-size: 11px;
  font-weight: 700;
  white-space: nowrap;
}

.available { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.busy { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.offline { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.waiting { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

/* Action Buttons */
.history-button {
  background: white;
  border: 1px solid var(--border);
  color: var(--loyaltel-blue);
  border-radius: 6px;
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.history-button:hover {
  background: var(--primary-light);
  border-color: var(--primary-border);
}

/* Queue List */
.queue-list {
  padding: 6px 18px;
}

.queue-item {
  padding: 14px 0;
  border-bottom: 1px solid var(--border);
  display: flex;
  gap: 14px;
  align-items: center;
}

.queue-item:last-child {
  border-bottom: 0;
}

.queue-num {
  width: 32px;
  height: 32px;
  display: grid;
  place-items: center;
  background: #f1f5f9;
  border-radius: 8px;
  font-weight: 800;
  font-size: 12px;
  color: #334155;
}

.queue-info {
  flex: 1;
  min-width: 0;
}

.queue-info strong {
  display: block;
  font-size: 13.5px;
  overflow-wrap: anywhere;
  color: var(--text);
}

.queue-info small {
  display: block;
  color: var(--muted);
  font-size: 11px;
  margin-top: 2px;
}

.empty {
  padding: 32px 16px;
  color: var(--muted);
  text-align: center;
  font-size: 13px;
}

.notice {
  padding: 14px 18px;
  border: 1px solid var(--border);
  border-radius: 12px;
  color: var(--muted);
  font-size: 12px;
  line-height: 1.6;
  background: white;
  margin-top: 14px;
}

/* REPORTS SECTION */
.reports-section {
  margin-top: 10px;
}

.report-cards {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 14px;
  padding: 18px;
}

.report-table {
  margin: 18px;
}

/* MODAL HISTORIAL */
.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}

.modal {
  display: flex;
  flex-direction: column;
  width: min(720px, 100%);
  max-height: 88vh;
  overflow: hidden;
  background: white;
  border-radius: 18px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  border: 1px solid var(--border);
  animation: modal-zoom-in 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  background: #f8fafc;
}

.modal-header h2 {
  margin: 0;
  font-size: 17px;
  font-weight: 800;
  color: var(--text);
}

.modal-header small {
  color: var(--muted);
  font-size: 12px;
  display: block;
  margin-top: 2px;
}

.close-modal {
  border: 0;
  background: #e2e8f0;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 18px;
  display: grid;
  place-items: center;
  color: var(--muted);
  transition: all 0.2s;
}

.close-modal:hover {
  background: #cbd5e1;
  color: var(--text);
}

.chat-messages {
  flex: 1;
  padding: 20px 24px;
  overflow-y: auto;
  background: #f1f5f9;
  min-height: 280px;
  max-height: 380px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.chat-message {
  max-width: 75%;
  padding: 12px 16px;
  border-radius: 14px;
  background: white;
  border: 1px solid var(--border);
  overflow-wrap: anywhere;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.chat-message.cliente {
  margin-right: auto;
  background: white;
  border-bottom-left-radius: 3px;
}

.chat-message.cliente strong {
  color: #047857;
}

.chat-message.bot {
  margin-right: auto;
  background: #f8fafc;
  border-bottom-left-radius: 3px;
}

.chat-message.bot strong {
  color: var(--loyaltel-blue);
}

.chat-message.asesor {
  margin-left: auto;
  background: var(--loyaltel-blue);
  color: white;
  border-color: var(--loyaltel-blue);
  border-bottom-right-radius: 3px;
}

.chat-message.asesor strong {
  color: #bfdbfe;
}

.chat-message.asesor time {
  color: #93c5fd;
}

.chat-message strong {
  display: block;
  font-size: 11px;
  font-weight: 700;
  margin-bottom: 4px;
}

.chat-message p {
  margin: 0;
  font-size: 13.5px;
  line-height: 1.5;
  white-space: pre-wrap;
}

.chat-message time {
  display: block;
  font-size: 10px;
  color: var(--muted);
  text-align: right;
  margin-top: 6px;
}

.history-status {
  text-align: center;
  color: var(--muted);
  font-size: 13px;
  padding: 20px;
}

/* Activity Timeline */
.activity-timeline {
  padding: 14px 24px;
  background: #f8fafc;
  border-top: 1px solid var(--border);
  max-height: 140px;
  overflow-y: auto;
}

.activity-item {
  padding: 8px 12px;
  border-left: 3px solid var(--border);
  margin-bottom: 8px;
  background: white;
  border-radius: 0 6px 6px 0;
  font-size: 12px;
}

.activity-item.asignacion { border-left-color: var(--green); }
.activity-item.reasignacion { border-left-color: var(--sand); }
.activity-item.finalizacion { border-left-color: var(--red); }
.activity-item.respuesta { border-left-color: var(--loyaltel-blue); }

.activity-time {
  font-size: 10px;
  color: var(--muted);
}

.activity-desc {
  color: var(--text);
  margin-top: 2px;
}

/* Supervisor Reply Box */
.reply-box {
  padding: 18px 24px;
  border-top: 1px solid var(--border);
  background: white;
}

.reply-box textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid var(--border);
  border-radius: 10px;
  font-size: 13px;
  font-family: inherit;
  outline: none;
  resize: vertical;
}

.reply-box textarea:focus {
  border-color: var(--loyaltel-blue);
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.reply-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
  gap: 12px;
}

.reply-footer small {
  color: var(--muted);
  font-size: 11px;
  font-weight: 500;
}

.primary {
  background: var(--loyaltel-blue);
  color: white;
  border: 0;
  border-radius: 8px;
  padding: 9px 18px;
  font-weight: 700;
  font-size: 12.5px;
  cursor: pointer;
  transition: all 0.2s;
}

.primary:hover {
  background: var(--loyaltel-light-blue);
}

.primary:disabled {
  opacity: 0.6;
  cursor: wait;
}

.small-btn {
  background: var(--danger-light);
  color: var(--danger-text);
  border: 1px solid var(--danger-border);
  border-radius: 8px;
  padding: 9px 14px;
  font-weight: 700;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s;
}

.small-btn:hover {
  background: var(--red);
  color: white;
}

footer {
  margin-top: 40px;
  text-align: center;
  color: #94a3b8;
  font-size: 12px;
  padding: 14px 0;
}

/* LOGIN SCREEN */
.login-screen {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at 50% 0%, #1e293b 0%, #0f172a 60%, #020617 100%);
  padding: 20px;
}

.login-card {
  background: rgba(30, 41, 59, 0.85);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  padding: 40px;
  width: min(420px, 100%);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  text-align: center;
}

.login-card .logo {
  height: 48px;
  width: auto;
  margin-bottom: 24px;
  filter: brightness(0) invert(1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));
}

.login-card h1 {
  margin: 0 0 8px;
  font-size: 22px;
  font-weight: 800;
  color: white;
}

.login-card p {
  margin: 0 0 24px;
  color: #94a3b8;
  font-size: 13px;
}

.login-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  background: rgba(15, 23, 42, 0.6);
  border-radius: 10px;
  font-size: 14px;
  color: white;
  margin-bottom: 14px;
  outline: none;
  font-family: inherit;
  transition: all 0.2s;
}

.login-input:focus {
  border-color: #3b82f6;
  background: rgba(15, 23, 42, 0.9);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}

.login-input::placeholder {
  color: #64748b;
}

.login-btn {
  width: 100%;
  background: linear-gradient(135deg, #0284c7, #0369a1);
  color: white;
  border: 0;
  border-radius: 10px;
  padding: 13px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
}

.login-btn:hover {
  background: linear-gradient(135deg, #0369a1, #075985);
  transform: translateY(-1px);
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.login-error {
  color: #f87171;
  font-size: 12.5px;
  margin-top: 14px;
  display: none;
  background: rgba(239, 68, 68, 0.15);
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid rgba(239, 68, 68, 0.25);
}

[hidden] { display: none !important; }
</style>
</head>
<body>

<!-- Pantalla de login -->
<div class="login-screen" id="loginScreen">
  <div class="login-card">
    <a href="https://www.loyaltel.pe/" target="_blank" rel="noopener noreferrer" class="brand-logo-link" title="Visitar Loyaltel (loyaltel.pe)">
      <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo" onerror="this.src='logo-loyaltel.png'">
    </a>
    <h1>Panel de Supervisor</h1>
    <p>Ingresa tus credenciales para acceder al monitoreo operativo</p>
    <input type="text" class="login-input" id="loginUsername" placeholder="Usuario" autocomplete="username">
    <input type="password" class="login-input" id="loginPassword" placeholder="Contraseña" autocomplete="current-password">
    <button class="login-btn" id="loginBtn">Iniciar sesión</button>
    <div class="login-error" id="loginError"></div>
  </div>
</div>

<!-- Header Principal -->
<header id="mainHeader" hidden>
  <div class="brand">
    <a href="https://www.loyaltel.pe/" target="_blank" rel="noopener noreferrer" class="brand-logo-link" title="Visitar Loyaltel (loyaltel.pe)">
      <img src="logo-loyaltel.png" alt="Loyaltel Logo" class="logo">
    </a>
    <div class="brand-text">
      LOYALTEL
      <small>PLATAFORMA OMNICANAL</small>
    </div>
  </div>

  <div class="header-controls">
    <div class="client-selector-header" id="clientSelectorHeader" hidden>
      <label for="clientSelect">Cliente:</label>
      <select id="clientSelect" class="client-select">
        <option value="">Todos los clientes</option>
      </select>
    </div>
    <div class="header-actions">
      <div class="connection" id="connection">
        <span class="pulse-dot online"></span>
        <span>Conectando con el servidor...</span>
      </div>
      <button class="refresh-header-btn" id="refresh" title="Actualizar datos en vivo">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="23 4 23 10 17 10"></polyline>
          <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
        </svg>
      </button>
      <button class="logout-btn" id="logoutBtn" title="Cerrar sesión">Salir</button>
    </div>
  </div>
</header>

<div class="layout">
  <aside>
    <div class="nav-label">Panel Operativo</div>
    <button class="nav active" data-view="Resumen">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7"></rect>
        <rect x="14" y="3" width="7" height="7"></rect>
        <rect x="14" y="14" width="7" height="7"></rect>
        <rect x="3" y="14" width="7" height="7"></rect>
      </svg>
      Resumen
    </button>
    <button class="nav" data-view="Conversaciones">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      </svg>
      Conversaciones
    </button>
    <button class="nav" data-view="Cola de atención">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
      Cola de atención
    </button>
    <button class="nav" data-view="Asesores">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
        <circle cx="9" cy="7" r="4"></circle>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
      </svg>
      Asesores
    </button>
    <button class="nav" data-view="Reportes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="20" x2="18" y2="10"></line>
        <line x1="12" y1="20" x2="12" y2="4"></line>
        <line x1="6" y1="20" x2="6" y2="14"></line>
      </svg>
      Reportes
    </button>
  </aside>

  <main>
    <div class="pagehead">
      <div>
        <h1 id="heading">Resumen de atención</h1>
        <div class="subtitle" id="subtitle">Indicadores actualizados desde MySQL y eventos en tiempo real.</div>
      </div>
    </div>

    <div id="feedback" role="status">
      <span class="pulse-dot online"></span>
      Consultando API de atención...
    </div>

    <!-- CARDS KPI -->
    <section class="cards">
      <div class="metric">
        <div class="metric-top">
          <span>Conversaciones activas</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
        </div>
        <div class="metric-value" id="activeCount">—</div>
        <div class="metric-note">En curso o atendidas por bot / asesor</div>
      </div>

      <div class="metric">
        <div class="metric-top">
          <span>En cola de espera</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
        </div>
        <div class="metric-value" id="queueCount">—</div>
        <div class="metric-note">Clientes esperando asignación</div>
      </div>

      <div class="metric">
        <div class="metric-top">
          <span>Asesores disponibles</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <polyline points="17 11 19 13 23 9"></polyline>
          </svg>
        </div>
        <div class="metric-value" id="availableCount">—</div>
        <div class="metric-note">Listos para recibir conversaciones</div>
      </div>

      <div class="metric">
        <div class="metric-top">
          <span>🏆 Mejor Vendedor</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2">
            <circle cx="12" cy="8" r="7"></circle>
            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
          </svg>
        </div>
        <div class="metric-value" id="topSeller" style="color:#8b5cf6;">—</div>
        <div class="metric-note" id="topSellerSales">— ventas registradas</div>
      </div>
    </section>

    <!-- COLUMNAS -->
    <div class="columns">
      <div>
        <!-- PANEL ASESORES -->
        <section class="panel" id="advisorPanel">
          <div class="panel-head">
            <div>
              <div class="panel-title">Estado del equipo de asesores</div>
              <div class="panel-sub">Disponibilidad en vivo y carga de chats</div>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Asesor</th>
                  <th>Estado</th>
                  <th>Chats Activos</th>
                </tr>
              </thead>
              <tbody id="advisorRows">
                <tr><td colspan="3" class="empty">Cargando asesores...</td></tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- PANEL CONVERSACIONES -->
        <section class="panel" id="conversationPanel">
          <div class="panel-head">
            <div>
              <div class="panel-title">Conversaciones recientes</div>
              <div class="panel-sub">Registro de actividad y atención en curso</div>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Conversación / Cliente</th>
                  <th>Estado</th>
                  <th>Asesor Asignado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="conversationRows">
                <tr><td colspan="4" class="empty">Cargando conversaciones...</td></tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <div>
        <!-- PANEL COLA -->
        <section class="panel" id="queuePanel">
          <div class="panel-head">
            <div>
              <div class="panel-title">Cola de espera</div>
              <div class="panel-sub">Clientes aguardando respuesta de asesor</div>
            </div>
          </div>
          <div class="queue-list" id="queueRows">
            <div class="empty">Consultando cola...</div>
          </div>
        </section>

        <!-- PANEL REPORTES -->
        <section class="panel reports-section" id="reportsPanel" hidden>
          <div class="panel-head">
            <div>
              <div class="panel-title">Reportes comerciales</div>
              <div class="panel-sub">Métricas de desempeño y tiempos de respuesta</div>
            </div>
          </div>
          <div class="report-cards">
            <div class="metric" style="padding:14px;">
              <div class="metric-top">Finalizadas</div>
              <div class="metric-value" id="reportTotalConv" style="font-size:24px;">—</div>
              <div class="metric-note">Total completadas</div>
            </div>
            <div class="metric" style="padding:14px;">
              <div class="metric-top">Clientes únicos</div>
              <div class="metric-value" id="reportTotalClients" style="font-size:24px;">—</div>
              <div class="metric-note">Total atendidos</div>
            </div>
            <div class="metric" style="padding:14px;">
              <div class="metric-top">Prom. Respuesta</div>
              <div class="metric-value" id="reportAvgResponse" style="font-size:24px;">—</div>
              <div class="metric-note">Segundos 1ra respuesta</div>
            </div>
          </div>

          <div class="report-table">
            <div class="panel-title" style="padding:0 0 10px 0; font-size:13px;">Por Campaña</div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Campaña</th><th>Conversaciones</th><th>Clientes</th></tr></thead>
                <tbody id="reportByCampaign">
                  <tr><td colspan="3" class="empty">Cargando datos...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="report-table">
            <div class="panel-title" style="padding:0 0 10px 0; font-size:13px;">Por Asesor</div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Asesor</th><th>Conversaciones</th><th>Prom. Respuesta</th></tr></thead>
                <tbody id="reportByAdvisor">
                  <tr><td colspan="3" class="empty">Cargando datos...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <div class="notice">
          <strong>⚡ Actualización en tiempo real:</strong> Los datos se actualizan automáticamente vía Server-Sent Events (SSE) y con respaldo cada 15 segundos.
        </div>
      </div>
    </div>

    <!-- MODAL HISTORIAL -->
    <div class="modal-backdrop" id="historyModal" hidden>
      <section class="modal" role="dialog" aria-modal="true" aria-labelledby="historyTitle">
        <div class="modal-header">
          <div>
            <h2 id="historyTitle">Historial de conversación</h2>
            <small id="historySubtitle">Cargando mensajes...</small>
          </div>
          <button class="close-modal" id="closeHistory" type="button" aria-label="Cerrar historial">×</button>
        </div>

        <div class="chat-messages" id="historyMessages" aria-live="polite">
          <div class="history-status">Consultando historial...</div>
        </div>

        <div class="activity-timeline" id="activityTimeline" aria-live="polite">
          <div class="history-status">Consultando actividad...</div>
        </div>

        <div class="reply-box">
          <textarea
            id="advisorReply"
            placeholder="Escribe tu mensaje al cliente como supervisor / asesor..."
            rows="3"
            maxlength="2000"
            aria-label="Respuesta del asesor"></textarea>

          <div class="reply-footer">
            <small id="replyStatus">Intervención directa en la conversación</small>
            <div style="display:flex; gap:10px;">
              <button class="small-btn" id="finishConversation" type="button">
                Finalizar atención
              </button>
              <button class="primary" id="sendReply" type="button">
                Enviar respuesta
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>

    <footer>Loyaltel · Plataforma Omnicanal de Contact Center © 2026</footer>
  </main>
</div>

<script>
const API_BASE = "http://localhost:3000/api";
const API_URL = `${API_BASE}/panel/resumen`;
const SSE_URL = `${API_BASE}/panel/sse`;

// Timeout de inactividad: 15 minutos
const INACTIVITY_TIMEOUT = 15 * 60 * 1000;
let inactivityTimer;

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(() => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('currentUser');
    window.location.href = 'index.php';
  }, INACTIVITY_TIMEOUT);
}

document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keypress', resetInactivityTimer);
document.addEventListener('click', resetInactivityTimer);
document.addEventListener('scroll', resetInactivityTimer);
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
  cargarResumen();
  conectarSSE();
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

$("loginBtn").addEventListener("click", login);
$("loginPassword").addEventListener("keydown", (e) => {
  if (e.key === "Enter") login();
});
$("logoutBtn").addEventListener("click", logout);

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
        const initial = (a.nombre || "A").charAt(0).toUpperCase();
        return `<tr>
          <td>
            <div style="display:flex; align-items:center; gap:10px;">
              <div style="width:28px; height:28px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; color:#334155;">
                ${escapeHTML(initial)}
              </div>
              <div>
                <strong>${escapeHTML(a.nombre || "Asesor " + a.id)}</strong>
                <br><small style="color:var(--muted);">${escapeHTML(a.telefono || "Sin teléfono")}</small>
              </div>
            </div>
          </td>
          <td><span class="pill ${css}"><span class="pulse-dot ${css === 'available' ? 'online' : (css === 'busy' ? 'busy' : 'offline')}"></span>${escapeHTML(label)}</span></td>
          <td><strong>${Number(a.conversaciones_activas) || 0}</strong></td>
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
            <div style="display:flex; align-items:center; gap:8px;">
              <span class="badge badge-primary">#${escapeHTML(c.conversacion_id)}</span>
              <strong>${escapeHTML(cliente)}</strong>
            </div>
            <small style="color:var(--muted); margin-top:2px; display:block;">📱 ${escapeHTML(c.telefono || "Sin teléfono")}</small>
          </td>
          <td>
            <span class="pill ${css}">${escapeHTML(label)}</span>
          </td>
          <td>${escapeHTML(c.asesor || "Sin asignar")}</td>
          <td>
            <div style="display:flex; gap:6px;">
              <button
                class="history-button"
                data-history-id="${Number(c.conversacion_id)}"
                data-history-phone="${escapeHTML(c.telefono || "")}">
                💬 Mensajes
              </button>
              ${c.asesor ? `
              <button
                class="history-button"
                data-transfer-id="${Number(c.conversacion_id)}"
                data-transfer-asesor="${escapeHTML(c.asesor)}"
                style="color: var(--sand); border-color:#fde68a; background:#fffbeb;">
                🔄 Transferir
              </button>
              ` : ''}
            </div>
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
    : '<div class="empty">La cola está vacía. Todos los clientes fueron asignados.</div>';
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
            <td><strong>${c.conversaciones}</strong></td>
            <td>${c.clientes}</td>
          </tr>
        `).join("")
        : '<tr><td colspan="3" class="empty">No hay datos por campaña</td></tr>';

      // Por asesor
      $("reportByAdvisor").innerHTML = data.por_asesor.length
        ? data.por_asesor.map(a => `
          <tr>
            <td><strong>${escapeHTML(a.nombre)}</strong></td>
            <td><strong>${a.conversaciones_atendidas}</strong></td>
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
      const topSeller = data.ventas[0];
      if (topSeller.ventas > 0) {
        $("topSeller").textContent = topSeller.asesor_nombre.split(' ')[0];
        $("topSellerSales").textContent = `${topSeller.ventas} ventas concretadas`;
      } else {
        $("topSeller").textContent = "—";
        $("topSellerSales").textContent = "Sin ventas aún";
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
  if (isLoading) return;

  isLoading = true;
  const button = $("refresh");
  button.disabled = true;
  $("feedback").className = "";
  $("feedback").innerHTML = `<span class="pulse-dot online"></span> Actualizando información...`;

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

    await loadTopSeller();

    // Poblar selector de clientes
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

    $("connection").innerHTML = `<span class="pulse-dot online"></span> API conectada (en vivo)`;
    $("feedback").innerHTML = `<span class="pulse-dot online"></span> Datos sincronizados · ${new Date().toLocaleTimeString("es-PE")}`;

  } catch (error) {
    console.error("Error cargando el panel:", error);
    $("connection").innerHTML = `<span class="pulse-dot offline"></span> Sin conexión API`;
    $("feedback").className = "error";
    $("feedback").textContent = "Error al consultar la API: " + error.message;
  } finally {
    button.disabled = false;
    isLoading = false;
  }
}

// Navegación Sidebar
document.querySelectorAll(".nav").forEach(button => {
  button.addEventListener("click", () => {
    document.querySelectorAll(".nav").forEach(item => item.classList.remove("active"));
    button.classList.add("active");

    const view = button.dataset.view;
    $("heading").textContent = view === "Resumen" ? "Resumen de atención" : view;
    $("subtitle").textContent = {
      "Resumen": "Indicadores actualizados desde MySQL y eventos en tiempo real.",
      "Conversaciones": "Últimas conversaciones y su estado actual.",
      "Cola de atención": "Clientes pendientes de asignación de asesor.",
      "Asesores": "Disponibilidad y carga del equipo comercial.",
      "Reportes": "Métricas comerciales y análisis de desempeño por campaña y asesor."
    }[view];

    const target = {
      "Resumen": null,
      "Conversaciones": "conversationPanel",
      "Cola de atención": "queuePanel",
      "Asesores": "advisorPanel",
      "Reportes": "reportsPanel"
    }[view];

    if (view === "Reportes") {
      $("reportsPanel").hidden = false;
      cargarReportes();
    }

    if (target) $(target).scrollIntoView({ behavior: "smooth", block: "start" });
    else window.scrollTo({ top: 0, behavior: "smooth" });
  });
});

const historyModal = $("historyModal");

async function abrirHistorial(conversacionId, telefono) {
  window.conversacionHistorialActual = conversacionId;
  historyModal.hidden = false;

  $("historyTitle").textContent = `Conversación #${conversacionId}`;
  $("historySubtitle").textContent = telefono ? `Cliente: ${telefono}` : "Historial de mensajes";
  $("historyMessages").innerHTML = '<div class="history-status">Consultando mensajes...</div>';
  $("activityTimeline").innerHTML = '<div class="history-status">Consultando actividad...</div>';

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
        '<div class="history-status">Esta conversación todavía no tiene mensajes registrados.</div>';
      return;
    }

    $("historyMessages").innerHTML = data.mensajes.map(m => {
      const remitente = ["cliente", "bot", "asesor"].includes(m.remitente)
        ? m.remitente
        : "bot";

      const nombres = {
        cliente: "Cliente",
        bot: "Bot Loyaltel",
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

    $("historySubtitle").textContent = `${data.total} mensaje(s) · Teléfono: ${telefono || "No registrado"}`;
    $("historyMessages").scrollTop = $("historyMessages").scrollHeight;

    loadActivityTimeline(conversacionId);

  } catch (error) {
    console.error("Error consultando historial:", error);
    $("historyMessages").innerHTML = `<div class="history-status">${escapeHTML(error.message)}</div>`;
  }
}

async function loadActivityTimeline(conversacionId) {
  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/historial`,
      { cache: "no-store" }
    );

    const data = await response.json();

    if (!response.ok || !data.ok || !data.historial.length) {
      $("activityTimeline").innerHTML = '<div class="history-status">No hay eventos adicionales en la línea de tiempo.</div>';
      return;
    }

    const actionLabels = {
      asignacion: '📋 Asignación',
      reasignacion: '🔄 Reasignación',
      finalizacion: '✕ Finalización',
      respuesta: '💬 Respuesta'
    };

    $("activityTimeline").innerHTML = data.historial.map(h => {
      const fecha = h.fecha ? new Date(h.fecha).toLocaleString("es-PE") : "";
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
    $("activityTimeline").innerHTML = `<div class="history-status">${escapeHTML(error.message)}</div>`;
  }
}

$("conversationRows").addEventListener("click", event => {
  const button = event.target.closest("[data-history-id]");
  if (!button) return;
  abrirHistorial(Number(button.dataset.historyId), button.dataset.historyPhone);
});

// Transferir conversación
$("conversationRows").addEventListener("click", async (event) => {
  const button = event.target.closest("[data-transfer-id]");
  if (!button) return;

  const conversacionId = Number(button.dataset.transferId);
  const asesorActual = button.dataset.transferAsesor;

  const asesorDestino = prompt(`Transferir conversación #${conversacionId}\nActualmente atendida por: ${asesorActual}\n\nIngresa el ID del nuevo asesor:`);
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
      alert("✅ Conversación transferida con éxito");
      cargarResumen();
    } else {
      alert("Error: " + data.error);
    }
  } catch (error) {
    console.error("Error transfiriendo conversación:", error);
    alert("Error al transferir conversación");
  }
});

// Enviar respuesta como supervisor
const sendReplyButton = $("sendReply");
const advisorReplyInput = $("advisorReply");
const replyStatus = $("replyStatus");

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
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mensaje })
      }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo enviar la respuesta.");
    }

    advisorReplyInput.value = "";
    replyStatus.textContent = "Respuesta enviada correctamente.";

    const responseHistory = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/mensajes`,
      { cache: "no-store" }
    );

    const historyData = await responseHistory.json();

    if (historyData.ok) {
      $("historyMessages").innerHTML = historyData.mensajes.map(m => {
        const remitente = ["cliente", "bot", "asesor"].includes(m.remitente) ? m.remitente : "bot";
        const nombres = { cliente: "Cliente", bot: "Bot Loyaltel", asesor: "Asesor" };
        const fecha = m.fecha ? new Date(m.fecha).toLocaleString("es-PE") : "";
        return `
          <article class="chat-message ${remitente}">
            <strong>${nombres[remitente]}</strong>
            <p>${escapeHTML(m.mensaje)}</p>
            <time>${escapeHTML(fecha)}</time>
          </article>
        `;
      }).join("");

      $("historyMessages").scrollTop = $("historyMessages").scrollHeight;
    }

  } catch (error) {
    console.error("Error enviando respuesta:", error);
    replyStatus.textContent = error.message;
  } finally {
    sendReplyButton.disabled = false;
  }
}

// Finalizar atención
const finishConversationButton = $("finishConversation");
finishConversationButton.addEventListener("click", finalizarAtencion);

async function finalizarAtencion() {
  const conversacionId = window.conversacionHistorialActual;
  if (!conversacionId) return;

  if (!confirm(`¿Deseas finalizar la atención de la conversación #${conversacionId}?`)) return;

  finishConversationButton.disabled = true;
  finishConversationButton.textContent = "Finalizando...";

  try {
    const response = await authenticatedFetch(
      `${API_BASE}/conversaciones/${conversacionId}/finalizar`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" }
      }
    );

    const data = await response.json();

    if (!response.ok || !data.ok) {
      throw new Error(data.error || "No se pudo finalizar la atención.");
    }

    alert("La atención se finalizó correctamente.");
    historyModal.hidden = true;
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

$("closeHistory").addEventListener("click", () => historyModal.hidden = true);
historyModal.addEventListener("click", event => {
  if (event.target === historyModal) historyModal.hidden = true;
});
document.addEventListener("keydown", event => {
  if (event.key === "Escape" && !historyModal.hidden) historyModal.hidden = true;
});

$("refresh").addEventListener("click", cargarResumen);

// ======================================================
// CONEXIÓN SSE
// ======================================================

let eventSource = null;
let isLoading = false;

function conectarSSE() {
  if (eventSource) eventSource.close();

  eventSource = new EventSource(SSE_URL);

  eventSource.addEventListener("connected", () => {
    $("connection").innerHTML = `<span class="pulse-dot online"></span> API conectada (tiempo real)`;
  });

  const eventNames = ["nueva_conversacion", "nuevo_mensaje", "asesor_asignado", "asesor_liberado", "cola_actualizada"];
  eventNames.forEach(evt => {
    eventSource.addEventListener(evt, async () => {
      if (!isLoading) await cargarResumen();
    });
  });

  eventSource.onerror = () => {
    $("connection").innerHTML = `<span class="pulse-dot offline"></span> Reconectando SSE...`;
  };
}

checkAuth();

setInterval(() => {
  if (authToken && (!eventSource || eventSource.readyState === EventSource.CLOSED)) {
    cargarResumen();
  }
}, 30000);
</script>
</body>
</html>
