<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel · Espacio de Trabajo del Asesor</title>
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
  --orange: #f59e0b;
}

* { box-sizing: border-box; }
body {
  margin: 0;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  color: var(--text);
  background: var(--bg);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
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

.asesor-info {
  display: flex;
  align-items: center;
  gap: 18px;
  flex-wrap: wrap;
}

.advisor-profile-card {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255, 255, 255, 0.06);
  padding: 6px 14px;
  border-radius: var(--radius-full);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.advisor-avatar {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  color: white;
}

.asesor-name {
  font-weight: 700;
  font-size: 13px;
  color: #f8fafc;
}

.asesor-status {
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 6px;
  color: #cbd5e1;
}

.status-indicator {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  position: relative;
}

.status-indicator.status-disponible {
  background: var(--green);
  box-shadow: 0 0 8px var(--green);
}

.status-indicator.status-ocupado {
  background: var(--orange);
  box-shadow: 0 0 8px var(--orange);
}

.status-indicator.status-desconectado {
  background: var(--muted);
}

/* Status Controls Segmented */
.status-controls {
  display: flex;
  background: rgba(255, 255, 255, 0.08);
  padding: 3px;
  border-radius: var(--radius-sm);
  gap: 3px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.status-btn {
  border: 0;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 600;
  background: transparent;
  color: #94a3b8;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-btn:hover {
  color: white;
  background: rgba(255, 255, 255, 0.05);
}

.status-btn.active {
  color: white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.status-btn.disponible.active {
  background: var(--green);
}

.status-btn.ocupado.active {
  background: var(--orange);
}

.status-btn.desconectado.active {
  background: #475569;
}

/* Client Selector Header */
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
  min-width: 160px;
  outline: none;
  font-family: inherit;
  transition: border-color 0.2s;
}

.client-select:focus {
  border-color: #3b82f6;
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
  border-color: var(--red);
}

/* MAIN LAYOUT */
.layout {
  display: grid;
  grid-template-columns: 360px minmax(0, 1fr);
  flex: 1;
  min-height: calc(100vh - 63px);
}

/* SIDEBAR CONVERSATIONS */
.sidebar {
  background: white;
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.sidebar-header {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #ffffff;
}

.sidebar-title {
  font-size: 14px;
  font-weight: 800;
  color: var(--text);
  display: flex;
  align-items: center;
  gap: 8px;
}

.sidebar-count-badge {
  background: var(--primary-light);
  color: var(--primary);
  font-size: 11px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 999px;
  border: 1px solid var(--primary-border);
}

.refresh-btn {
  border: 1px solid var(--border);
  background: white;
  cursor: pointer;
  color: var(--muted);
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.refresh-btn:hover {
  background: var(--bg);
  color: var(--primary);
  border-color: var(--primary-border);
}

/* Sidebar Search */
.sidebar-search-box {
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
  background: #f8fafc;
}

.sidebar-search-input {
  width: 100%;
  padding: 8px 12px 8px 34px;
  font-size: 13px;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: white url('data:image/svg2+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%2394a3b8" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>') no-repeat 10px center;
  outline: none;
  font-family: inherit;
  transition: all 0.2s;
}

.sidebar-search-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.conversation-list {
  flex: 1;
  overflow-y: auto;
}

.conversation-item {
  padding: 14px 18px;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
  display: flex;
  gap: 12px;
  align-items: flex-start;
}

.conversation-item:hover {
  background: #f8fafc;
}

.conversation-item.active {
  background: #eff6ff;
  border-left: 4px solid var(--primary);
}

.conversation-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
  color: #334155;
  font-weight: 700;
  font-size: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  position: relative;
}

.conversation-item.active .conversation-avatar {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.conversation-content {
  flex: 1;
  min-width: 0;
}

.conversation-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
}

.conversation-client {
  font-weight: 700;
  font-size: 13.5px;
  color: var(--text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 170px;
}

.conversation-phone {
  font-size: 11px;
  color: var(--muted);
  display: block;
  margin-top: 1px;
}

.conversation-time {
  font-size: 11px;
  color: #94a3b8;
  font-weight: 500;
}

.conversation-preview {
  font-size: 12.5px;
  color: var(--muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 6px;
  line-height: 1.4;
}

.conversation-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 11px;
}

.conversation-channel-tag {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #ecfdf5;
  color: #047857;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 10px;
  font-weight: 600;
}

.pending-badge {
  background: var(--red);
  color: white;
  padding: 2px 7px;
  border-radius: 99px;
  font-weight: 800;
  font-size: 10px;
  animation: pulse-ring 2s infinite;
}

/* CHAT AREA */
main {
  padding: 20px 24px;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
  background: #f8fafc;
}

.chat-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
}

.chat-header {
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  background: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

.chat-header-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.chat-header-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 2px solid #bfdbfe;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-weight: 800;
  font-size: 15px;
}

.chat-header-info h2 {
  margin: 0;
  font-size: 16px;
  font-weight: 800;
  color: var(--text);
}

.chat-header-info small {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-top: 3px;
  color: var(--muted);
  font-size: 12px;
}

.chat-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Status Selector */
.status-selector {
  display: flex;
  align-items: center;
}

.status-select {
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: #f8fafc;
  color: var(--text);
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  outline: none;
  transition: all 0.2s;
  font-family: inherit;
}

.status-select:focus {
  border-color: var(--primary);
  background: white;
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.chat-btn {
  border: 0;
  border-radius: 8px;
  padding: 8px 14px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}

.btn-client-card {
  background: var(--primary-light);
  color: var(--primary);
  border: 1px solid var(--primary-border);
}

.btn-client-card:hover {
  background: var(--primary);
  color: white;
}

.btn-finish {
  background: var(--danger-light);
  color: var(--danger-text);
  border: 1px solid var(--danger-border);
}

.btn-finish:hover {
  background: var(--danger);
  color: white;
}

/* CHAT MESSAGES STREAM */
.chat-messages {
  flex: 1;
  padding: 24px;
  overflow-y: auto;
  background: #f1f5f9;
  min-height: 380px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.chat-message {
  max-width: 72%;
  padding: 12px 16px;
  border-radius: 16px;
  background: white;
  border: 1px solid var(--border);
  overflow-wrap: anywhere;
  position: relative;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* Cliente Message */
.chat-message.cliente {
  margin-right: auto;
  background: #ffffff;
  border-color: #e2e8f0;
  border-bottom-left-radius: 4px;
}

.chat-message.cliente strong {
  color: #047857;
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Bot Message */
.chat-message.bot {
  margin-right: auto;
  background: #f8fafc;
  border-color: #cbd5e1;
  border-bottom-left-radius: 4px;
}

.chat-message.bot strong {
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Asesor Message */
.chat-message.asesor {
  margin-left: auto;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  border-color: #1d4ed8;
  border-bottom-right-radius: 4px;
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}

.chat-message.asesor strong {
  color: #bfdbfe;
}

.chat-message.asesor time {
  color: #93c5fd;
}

/* External Message */
.chat-message.external {
  border-color: #f59e0b;
  background: #fffbeb;
  color: #78350f;
  margin-left: auto;
  border-bottom-right-radius: 4px;
}

.chat-message.external strong {
  color: #b45309;
}

.chat-message.external time {
  color: #d97706;
}

.chat-message strong {
  display: block;
  margin-bottom: 5px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

.chat-message p {
  margin: 0;
  font-size: 13.5px;
  line-height: 1.55;
  white-space: pre-wrap;
}

.chat-message time {
  display: block;
  margin-top: 6px;
  font-size: 10px;
  color: var(--muted);
  text-align: right;
}

.external-indicator {
  font-size: 13px;
  margin-left: 4px;
}

/* CHAT INPUT */
.chat-input {
  padding: 16px 20px;
  border-top: 1px solid var(--border);
  background: white;
}

.chat-input textarea {
  display: block;
  width: 100%;
  resize: vertical;
  min-height: 80px;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 12px;
  background: #f8fafc;
  color: var(--text);
  font: inherit;
  font-size: 13.5px;
  outline: none;
  transition: all 0.2s;
}

.chat-input textarea:focus {
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.chat-input-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: 12px;
}

.input-status {
  font-size: 12px;
  color: var(--muted);
  display: flex;
  align-items: center;
  gap: 6px;
}

.send-btn {
  background: var(--primary);
  color: white;
  border: 0;
  border-radius: 10px;
  padding: 10px 22px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}

.send-btn:hover {
  background: var(--primary-hover);
  transform: translateY(-1px);
}

.send-btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
  transform: none;
}

.external-msg-btn {
  background: #fffbeb;
  color: #b45309;
  border: 1px solid #fde68a;
  border-radius: 10px;
  padding: 10px 16px;
  font-weight: 700;
  cursor: pointer;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}

.external-msg-btn:hover {
  background: #fef3c7;
  border-color: #fcd34d;
}

/* CLIENT CARD DRAWER */
.client-card {
  padding: 18px 22px;
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
  animation: slide-down 0.25s ease-out;
}

@keyframes slide-down {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}

.client-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border);
}

.client-card-title {
  font-size: 15px;
  font-weight: 800;
  color: var(--text);
  display: flex;
  align-items: center;
  gap: 8px;
}

.close-card {
  border: 0;
  background: #f1f5f9;
  cursor: pointer;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--muted);
  transition: all 0.2s;
}

.close-card:hover {
  background: #e2e8f0;
  color: var(--text);
}

.client-fields-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 12px;
}

.client-field {
  padding: 8px 12px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid var(--border);
  font-size: 12.5px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.client-field label {
  color: var(--muted);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.client-field .field-value {
  color: var(--text);
  font-weight: 600;
}

.client-field.editing input {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid var(--primary);
  border-radius: 6px;
  font-size: 13px;
  font-family: inherit;
  outline: none;
  background: white;
}

.edit-client-btn {
  border: 1px solid var(--primary-border);
  background: var(--primary-light);
  color: var(--primary);
  cursor: pointer;
  font-size: 12px;
  font-weight: 700;
  padding: 8px 16px;
  border-radius: 8px;
  margin-top: 14px;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.edit-client-btn:hover {
  background: var(--primary);
  color: white;
}

/* MODALS */
.modal {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 16px;
  max-width: 520px;
  width: 100%;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  border: 1px solid var(--border);
  animation: modal-zoom-in 0.25s ease-out;
}

.modal-header {
  padding: 18px 24px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8fafc;
}

.modal-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 800;
  color: var(--text);
}

.modal-close {
  border: 0;
  background: transparent;
  cursor: pointer;
  font-size: 22px;
  color: var(--muted);
}

.modal-close:hover {
  color: var(--text);
}

.modal-body {
  padding: 24px;
}

.modal-body p {
  margin: 0 0 12px 0;
  font-size: 13.5px;
  color: var(--muted);
  line-height: 1.5;
}

.modal-warning {
  background: #fffbeb;
  color: #92400e;
  border: 1px solid #fde68a;
  padding: 12px 14px;
  border-radius: 10px;
  font-size: 12.5px;
  margin-bottom: 16px;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

.modal-body textarea {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid var(--border);
  border-radius: 10px;
  font-size: 13.5px;
  outline: none;
  font-family: inherit;
  transition: all 0.2s;
}

.modal-body textarea:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px var(--primary-glow);
}

.modal-footer {
  padding: 14px 24px;
  border-top: 1px solid var(--border);
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  background: #f8fafc;
}

.modal-btn {
  border: 0;
  border-radius: 8px;
  padding: 9px 18px;
  font-weight: 700;
  cursor: pointer;
  font-size: 13px;
  transition: all 0.2s;
}

.modal-btn-cancel {
  background: white;
  border: 1px solid var(--border);
  color: var(--muted);
}

.modal-btn-cancel:hover {
  background: #f1f5f9;
  color: var(--text);
}

.modal-btn-confirm {
  background: var(--primary);
  color: white;
}

.modal-btn-confirm:hover {
  background: var(--primary-hover);
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
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  border: 0;
  border-radius: 10px;
  padding: 13px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.login-btn:hover {
  background: linear-gradient(135deg, #1d4ed8, #1e40af);
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

.empty-state {
  padding: 48px 24px;
  text-align: center;
  color: var(--muted);
  font-size: 13.5px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.empty-state-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  margin-bottom: 4px;
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
    <h1>Espacio del Asesor</h1>
    <p>Ingresa tus credenciales para acceder a la atención en vivo</p>
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
      <small>WORKSPACE ASESOR</small>
    </div>
  </div>

  <div class="asesor-info">
    <div class="advisor-profile-card">
      <div class="advisor-avatar" id="advisorAvatarInitial">A</div>
      <div>
        <span class="asesor-name" id="asesorName">—</span>
        <span class="asesor-status">
          <span class="status-indicator" id="statusIndicator"></span>
          <span id="statusText">—</span>
        </span>
      </div>
    </div>

    <div class="client-selector-header" id="clientSelectorHeader" hidden>
      <label for="clientSelectAsesor">Cliente:</label>
      <select id="clientSelectAsesor" class="client-select">
        <option value="">Todos los clientes</option>
      </select>
    </div>

    <div class="status-controls">
      <button class="status-btn disponible" data-estado="disponible">
        <span class="status-indicator status-disponible"></span>
        Disponible
      </button>
      <button class="status-btn ocupado" data-estado="ocupado">
        <span class="status-indicator status-ocupado"></span>
        Ocupado
      </button>
      <button class="status-btn desconectado" data-estado="desconectado">
        <span class="status-indicator status-desconectado"></span>
        Desconectado
      </button>
    </div>

    <button class="logout-btn" id="logoutBtn" title="Cerrar sesión">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
      </svg>
      Salir
    </button>
  </div>
</header>

<!-- Layout Principal -->
<div class="layout" id="mainLayout" hidden>
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-title">
        Mis conversaciones
        <span class="sidebar-count-badge" id="convCountBadge">0</span>
      </div>
      <button class="refresh-btn" id="refreshBtn" title="Actualizar conversaciones">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 4 23 10 17 10"></polyline>
          <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
        </svg>
      </button>
    </div>

    <div class="sidebar-search-box">
      <input type="text" class="sidebar-search-input" id="searchConversationInput" placeholder="Buscar por cliente o teléfono...">
    </div>

    <div class="conversation-list" id="conversationList">
      <div class="empty-state">
        <div class="empty-state-icon">💬</div>
        Cargando conversaciones...
      </div>
    </div>
  </aside>

  <main>
    <!-- Ficha del cliente -->
    <div class="client-card" id="clientCard" hidden>
      <div class="client-card-header">
        <span class="client-card-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <polyline points="17 11 19 13 23 9"></polyline>
          </svg>
          Ficha comercial del cliente
        </span>
        <button class="close-card" id="closeCardBtn" title="Cerrar ficha">×</button>
      </div>
      <div id="clientCardContent"></div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
      <div class="chat-header" id="chatHeader" hidden>
        <div class="chat-header-info">
          <div class="chat-header-avatar" id="chatHeaderAvatar">C</div>
          <div>
            <h2 id="chatClientName">—</h2>
            <small id="chatClientPhone">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <span>—</span>
            </small>
          </div>
        </div>

        <div class="chat-actions">
          <div class="status-selector">
            <select class="status-select" id="statusSelect" onchange="updateStatus()">
              <option value="">Estado comercial...</option>
              <option value="atendiendo">🔵 ATENDIENDO</option>
              <option value="venta">🟢 VENTA REALIZADA</option>
              <option value="volver_a_llamar">🟡 VOLVER A LLAMAR</option>
              <option value="cliente_potencial">⭐ CLIENTE POTENCIAL</option>
              <option value="nuevo_pedido">📦 NUEVO PEDIDO</option>
              <option value="cobertura_en_proceso">📡 COBERTURA EN PROCESO</option>
              <option value="falta_informacion">📝 FALTA INFORMACIÓN</option>
              <option value="sin_cobertura">🚫 SIN COBERTURA</option>
              <option value="no_desea">❌ NO DESEA</option>
              <option value="no_contesta">📞 NO CONTESTA</option>
              <option value="numero_no_existe">⚠️ NÚMERO NO EXISTE</option>
              <option value="no_aplica">⚪ NO APLICA</option>
              <option value="deuda">💳 DEUDA</option>
              <option value="spam">⛔ SPAM</option>
            </select>
          </div>

          <button class="chat-btn btn-client-card" id="showClientCardBtn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            Ficha
          </button>

          <button class="chat-btn btn-finish" id="finishConversationBtn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            Finalizar
          </button>
        </div>
      </div>

      <div class="chat-messages" id="chatMessages">
        <div class="empty-state">
          <div class="empty-state-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
          </div>
          <strong style="color:var(--text); font-size:15px;">Selecciona una conversación</strong>
          <span>Haz clic en un cliente de la lista para ver el historial y responder mensajes.</span>
        </div>
      </div>

      <div class="chat-input" id="chatInput" hidden>
        <textarea id="messageInput" placeholder="Escribe tu respuesta al cliente aquí..." rows="3"></textarea>
        <div class="chat-input-footer">
          <span class="input-status" id="inputStatus">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 10 4 15 9 20"></polyline>
              <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
            </svg>
            Presiona Enter para enviar · Shift+Enter para salto de línea
          </span>

          <div style="display:flex; gap:10px;">
            <button class="external-msg-btn" id="externalMsgBtn" title="Registrar mensaje enviado desde WhatsApp Web o Celular">
              📱 Registrar mensaje externo
            </button>
            <button class="send-btn" id="sendBtn">
              <span>Enviar</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Modal para registrar mensaje externo -->
      <div class="modal" id="externalMsgModal" hidden>
        <div class="modal-content">
          <div class="modal-header">
            <h3>📱 Registrar Mensaje Externo (WhatsApp)</h3>
            <button class="modal-close" id="closeExternalMsgModal">&times;</button>
          </div>
          <div class="modal-body">
            <p>Usa esta opción si respondiste al cliente directamente desde <strong>WhatsApp Web</strong> o tu <strong>celular</strong> fuera de la plataforma.</p>
            <div class="modal-warning">
              <span>⚠️</span>
              <div>
                <strong>Aviso de sincronización:</strong> Este mensaje NO será enviado por el sistema. Solo se registrará en el historial de la conversación para auditoría y trazabilidad.
              </div>
            </div>
            <textarea id="externalMessageInput" placeholder="Pega aquí el mensaje exacto que enviaste al cliente..." rows="5"></textarea>
          </div>
          <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" id="cancelExternalMsg">Cancelar</button>
            <button class="modal-btn modal-btn-confirm" id="confirmExternalMsg">Registrar en historial</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
const API_BASE = window.location.origin ? `${window.location.origin}/api` : "/api";
let currentAsesor = null;
let currentConversation = null;
let eventSource = null;
let authToken = localStorage.getItem('asesorAuthToken');
let currentUser = JSON.parse(localStorage.getItem('asesorCurrentUser') || 'null');
let rawConversations = [];

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
  setupAsesorInterface();
  loadConversations();
  connectSSE();
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
  if ($("advisorAvatarInitial")) {
    $("advisorAvatarInitial").textContent = (currentAsesor.nombre || "A").charAt(0).toUpperCase();
  }
  updateStatusUI(currentAsesor.estado);

  document.querySelectorAll(".status-btn").forEach(btn => {
    btn.onclick = () => changeStatus(btn.dataset.estado);
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
      rawConversations = data.conversaciones || [];
      if ($("convCountBadge")) {
        $("convCountBadge").textContent = rawConversations.length;
      }
      filterAndRenderConversations();
    } else if (response.status === 401 || response.status === 403) {
      logout();
    }
  } catch (error) {
    console.error("Error cargando conversaciones:", error);
  }
}

function filterAndRenderConversations() {
  const searchTerm = ($("searchConversationInput") ? $("searchConversationInput").value.trim().toLowerCase() : "");
  const filtered = rawConversations.filter(c => {
    if (!searchTerm) return true;
    const clientMatch = (c.cliente || "").toLowerCase().includes(searchTerm);
    const phoneMatch = (c.telefono || "").toLowerCase().includes(searchTerm);
    return clientMatch || phoneMatch;
  });
  renderConversations(filtered);
}

if ($("searchConversationInput")) {
  $("searchConversationInput").addEventListener("input", filterAndRenderConversations);
}

function renderConversations(conversations) {
  const list = $("conversationList");

  if (conversations.length === 0) {
    list.innerHTML = `
      <div class="empty-state">
        <div class="empty-state-icon">🔍</div>
        <span>No hay conversaciones disponibles</span>
      </div>`;
    return;
  }

  list.innerHTML = conversations.map(c => {
    const time = c.fecha_ultimo_mensaje
      ? new Date(c.fecha_ultimo_mensaje).toLocaleTimeString("es-PE", { hour: '2-digit', minute: '2-digit' })
      : '—';

    const pendingBadge = c.mensajes_pendientes > 0
      ? `<span class="pending-badge">${c.mensajes_pendientes}</span>`
      : '';

    const initial = (c.cliente || c.telefono || 'C').charAt(0).toUpperCase();

    const isActive = currentConversation === Number(c.conversacion_id);

    return `
      <div class="conversation-item ${isActive ? 'active' : ''}" data-id="${c.conversacion_id}" data-phone="${escapeHTML(c.telefono)}" data-name="${escapeHTML(c.cliente || c.telefono)}">
        <div class="conversation-avatar">${escapeHTML(initial)}</div>
        <div class="conversation-content">
          <div class="conversation-header">
            <span class="conversation-client">${escapeHTML(c.cliente || c.telefono)}</span>
            <span class="conversation-time">${time}</span>
          </div>
          <span class="conversation-phone">${escapeHTML(c.telefono)}</span>
          <div class="conversation-preview">${escapeHTML(c.ultimo_mensaje || 'Sin mensajes registrados')}</div>
          <div class="conversation-meta">
            <span class="conversation-channel-tag">
              <span>●</span> WhatsApp
            </span>
            ${pendingBadge}
          </div>
        </div>
      </div>
    `;
  }).join("");

  list.querySelectorAll(".conversation-item").forEach(item => {
    item.addEventListener("click", () => {
      openConversation(
        Number(item.dataset.id),
        item.dataset.phone,
        item.dataset.name
      );
    });
  });
}

// ======================================================
// ABRIR CONVERSACIÓN
// ======================================================

async function openConversation(conversationId, telefono, clientName) {
  currentConversation = conversationId;

  // Resaltar conversación activa
  document.querySelectorAll(".conversation-item").forEach(item => {
    item.classList.toggle("active", Number(item.dataset.id) === conversationId);
  });

  // Mostrar header del chat
  $("chatHeader").hidden = false;
  $("chatInput").hidden = false;
  $("chatClientName").textContent = clientName || "Cliente";
  $("chatClientPhone").innerHTML = `
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
    </svg>
    <span>${escapeHTML(telefono)}</span>
  `;
  if ($("chatHeaderAvatar")) {
    $("chatHeaderAvatar").textContent = (clientName || "C").charAt(0).toUpperCase();
  }

  $("chatMessages").innerHTML = '<div class="empty-state"><div class="empty-state-icon">⏳</div>Cargando mensajes...</div>';

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

  if (!messages || messages.length === 0) {
    container.innerHTML = '<div class="empty-state"><div class="empty-state-icon">💬</div>No hay mensajes en esta conversación</div>';
    return;
  }

  container.innerHTML = messages.map(m => {
    const remitente = ["cliente", "bot", "asesor"].includes(m.remitente)
      ? m.remitente
      : "bot";

    const nombres = {
      cliente: "Cliente",
      bot: "Bot Loyaltel",
      asesor: "Asesor (Tú)"
    };

    const fecha = m.fecha
      ? new Date(m.fecha).toLocaleTimeString("es-PE", { hour: '2-digit', minute: '2-digit' })
      : "";

    // Indicador de mensaje externo
    const esExterno = m.origen_mensaje === 'externo';
    const indicadorExterno = esExterno
      ? '<span class="external-indicator" title="Enviado desde WhatsApp Web/Celular">📱 Externo</span>'
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
  try {
    const response = await authenticatedFetch(`${API_BASE}/clientes/${telefono}`);
    const data = await response.json();

    if (data.ok) {
      const cliente = data.cliente;
      if (cliente.nombre) {
        $("chatClientName").textContent = cliente.nombre;
        if ($("chatHeaderAvatar")) {
          $("chatHeaderAvatar").textContent = cliente.nombre.charAt(0).toUpperCase();
        }
      }
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

  $("clientCardContent").innerHTML = `
    <div class="client-fields-grid">
      ${fields.map(f => `
        <div class="client-field" data-field="${f.field}" data-editable="${f.editable}">
          <label>${escapeHTML(f.label)}</label>
          <span class="field-value">${escapeHTML(f.value || '—')}</span>
        </div>
      `).join("")}
    </div>
    <button class="edit-client-btn" id="editClientBtn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 20h9"></path>
        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
      </svg>
      Editar ficha comercial
    </button>
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

  $("editClientBtn").innerHTML = `
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
      <polyline points="17 21 17 13 7 13 7 21"></polyline>
      <polyline points="7 3 7 8 15 8"></polyline>
    </svg>
    Guardar cambios
  `;
  $("editClientBtn").onclick = saveClientData;
}

async function saveClientData() {
  const telefono = $("chatClientPhone").textContent.trim();
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
      alert("✅ Datos actualizados correctamente");
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
  $("inputStatus").textContent = "Enviando respuesta...";

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/responder`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ mensaje })
    });

    const data = await response.json();

    if (data.ok) {
      $("messageInput").value = "";
      $("inputStatus").textContent = "Mensaje enviado con éxito";

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
    $("inputStatus").innerHTML = `
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="9 10 4 15 9 20"></polyline>
        <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
      </svg>
      Presiona Enter para enviar · Shift+Enter para salto de línea
    `;
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
      alert("✅ Mensaje externo registrado correctamente en el historial.");

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
    $("confirmExternalMsg").textContent = "Registrar en historial";
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

  if (!confirm("¿Estás seguro de finalizar la atención de esta conversación?")) return;

  try {
    const response = await authenticatedFetch(`${API_BASE}/conversaciones/${currentConversation}/finalizar`, {
      method: "POST"
    });

    const data = await response.json();

    if (data.ok) {
      alert("Conversación finalizada correctamente");
      currentConversation = null;
      $("chatHeader").hidden = true;
      $("chatInput").hidden = true;
      $("chatMessages").innerHTML = '<div class="empty-state"><div class="empty-state-icon">✅</div>Conversación finalizada. Selecciona otra conversación para continuar.</div>';
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
// FICHA DEL CLIENTE TOGGLE
// ======================================================

$("showClientCardBtn").addEventListener("click", () => {
  $("clientCard").hidden = !$("clientCard").hidden;
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
    if (currentAsesor && data.asesor_id === currentAsesor.id) {
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

<footer style="margin-top: auto; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
  Loyaltel Contact Center Platform © 2026 - Desarrollado por Paulo Tenorio
</footer>
</body>
</html>
