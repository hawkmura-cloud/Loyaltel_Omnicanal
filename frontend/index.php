<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel · Plataforma Omnicanal</title>
<link rel="stylesheet" href="css/modern.css">
<style>
:root {
  --portal-bg: #0b132b;
}

body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at 50% 0%, #1e293b 0%, #0f172a 50%, #020617 100%);
  color: #f8fafc;
  padding: 24px;
  position: relative;
  overflow-x: hidden;
}

/* Ambient glow blobs */
.glow-blob-1 {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, transparent 70%);
  top: -100px;
  left: 50%;
  transform: translateX(-50%);
  pointer-events: none;
  filter: blur(40px);
}

.glow-blob-2 {
  position: absolute;
  width: 400px;
  height: 400px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%);
  bottom: -50px;
  right: 10%;
  pointer-events: none;
  filter: blur(50px);
}

.portal-wrapper {
  width: 100%;
  max-width: 900px;
  position: relative;
  z-index: 10;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.portal-header {
  text-align: center;
  margin-bottom: 36px;
}

.portal-logo-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.12);
  padding: 16px 28px;
  border-radius: 20px;
  margin-bottom: 20px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
  transition: transform 0.3s ease, border-color 0.3s ease;
  text-decoration: none;
  cursor: pointer;
}

.portal-logo-wrap:hover {
  transform: translateY(-2px);
  border-color: rgba(255, 255, 255, 0.25);
}

.portal-logo {
  height: 50px;
  width: auto;
  object-fit: contain;
  filter: brightness(0) invert(1) drop-shadow(0 4px 12px rgba(0,0,0,0.4));
}

.portal-title {
  font-size: 32px;
  font-weight: 800;
  letter-spacing: -0.5px;
  color: #ffffff;
  margin-bottom: 8px;
}

.portal-subtitle {
  color: #94a3b8;
  font-size: 15px;
  max-width: 500px;
  margin: 0 auto;
  line-height: 1.5;
}

.portal-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(37, 99, 235, 0.15);
  border: 1px solid rgba(59, 130, 246, 0.3);
  color: #60a5fa;
  font-size: 12px;
  font-weight: 700;
  padding: 5px 12px;
  border-radius: 999px;
  margin-top: 14px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.role-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  width: 100%;
}

.role-card {
  background: rgba(30, 41, 59, 0.7);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 18px;
  padding: 28px 24px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  text-align: left;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}

.role-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  opacity: 0;
  transition: opacity 0.25s ease;
}

.role-card:hover {
  transform: translateY(-5px);
  background: rgba(30, 41, 59, 0.95);
  border-color: rgba(255, 255, 255, 0.2);
  box-shadow: 0 20px 35px -5px rgba(0, 0, 0, 0.45);
}

.role-card:hover::before {
  opacity: 1;
}

/* Card Themes */
.role-card.superadmin::before {
  background: linear-gradient(90deg, #8b5cf6, #3b82f6);
}
.role-card.superadmin .role-icon-box {
  background: rgba(139, 92, 246, 0.15);
  color: #a78bfa;
  border: 1px solid rgba(139, 92, 246, 0.25);
}
.role-card.superadmin:hover {
  border-color: rgba(139, 92, 246, 0.4);
  box-shadow: 0 20px 35px -5px rgba(139, 92, 246, 0.2);
}

.role-card.supervision::before {
  background: linear-gradient(90deg, #0284c7, #38bdf8);
}
.role-card.supervision .role-icon-box {
  background: rgba(2, 132, 199, 0.15);
  color: #38bdf8;
  border: 1px solid rgba(2, 132, 199, 0.25);
}
.role-card.supervision:hover {
  border-color: rgba(56, 189, 248, 0.4);
  box-shadow: 0 20px 35px -5px rgba(2, 132, 199, 0.2);
}

.role-card.asesor::before {
  background: linear-gradient(90deg, #10b981, #34d399);
}
.role-card.asesor .role-icon-box {
  background: rgba(16, 185, 129, 0.15);
  color: #34d399;
  border: 1px solid rgba(16, 185, 129, 0.25);
}
.role-card.asesor:hover {
  border-color: rgba(52, 211, 153, 0.4);
  box-shadow: 0 20px 35px -5px rgba(16, 185, 129, 0.2);
}

.role-icon-box {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  transition: transform 0.25s ease;
}

.role-icon-box svg {
  width: 26px;
  height: 26px;
}

.role-card:hover .role-icon-box {
  transform: scale(1.08);
}

.role-name {
  font-size: 19px;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 8px;
}

.role-desc {
  font-size: 13px;
  color: #94a3b8;
  line-height: 1.5;
  margin-bottom: 22px;
  flex: 1;
}

.role-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding-top: 14px;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
}

.role-tag {
  font-size: 11px;
  font-weight: 700;
  color: #cbd5e1;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-action-arrow {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  transition: all 0.2s ease;
}

.role-card:hover .role-action-arrow {
  background: var(--primary);
  transform: translateX(3px);
}

.portal-footer {
  margin-top: 40px;
  text-align: center;
  color: #64748b;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.portal-status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #10b981;
  font-weight: 600;
}

.portal-status .dot {
  width: 8px;
  height: 8px;
  background: #10b981;
  border-radius: 50%;
  box-shadow: 0 0 8px #10b981;
}

@media (max-width: 820px) {
  .role-grid {
    grid-template-columns: 1fr;
    max-width: 440px;
  }
}
</style>
</head>
<body>

<div class="glow-blob-1"></div>
<div class="glow-blob-2"></div>

<div class="portal-wrapper">
  <header class="portal-header">
    <a href="https://www.loyaltel.pe/" target="_blank" rel="noopener noreferrer" class="portal-logo-wrap" title="Visitar Loyaltel (loyaltel.pe)">
      <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="portal-logo" onerror="this.src='logo-loyaltel.png'">
    </a>
    <h1 class="portal-title">Loyaltel Omnicanal</h1>
    <p class="portal-subtitle">Plataforma integral de gestión de clientes, supervisión operativa y atención en tiempo real.</p>
    <div class="portal-badge">
      <span class="dot" style="width:6px; height:6px; background:#38bdf8; border-radius:50%;"></span>
      Contact Center Cloud Suite
    </div>
  </header>

  <main class="role-grid">
    <!-- SUPERADMIN -->
    <div class="role-card superadmin" onclick="goTo('superadmin')" role="button" tabindex="0">
      <div class="role-icon-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
        </svg>
      </div>
      <h2 class="role-name">Super Administrador</h2>
      <p class="role-desc">Gestión integral de usuarios, asignación de permisos, clientes corporativos y campañas de WhatsApp.</p>
      <div class="role-footer">
        <span class="role-tag">Gestión Global</span>
        <div class="role-action-arrow">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </div>
      </div>
    </div>

    <!-- SUPERVISOR -->
    <div class="role-card supervision" onclick="goTo('supervision')" role="button" tabindex="0">
      <div class="role-icon-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
          <line x1="8" y1="21" x2="16" y2="21"></line>
          <line x1="12" y1="17" x2="12" y2="21"></line>
        </svg>
      </div>
      <h2 class="role-name">Supervisión</h2>
      <p class="role-desc">Monitoreo en vivo de atención, panel de métricas KPI, control de cola de espera y reasignación de chats.</p>
      <div class="role-footer">
        <span class="role-tag">Control & Auditoría</span>
        <div class="role-action-arrow">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </div>
      </div>
    </div>

    <!-- ASESOR -->
    <div class="role-card asesor" onclick="goTo('asesor')" role="button" tabindex="0">
      <div class="role-icon-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
          <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
        </svg>
      </div>
      <h2 class="role-name">Espacio del Asesor</h2>
      <p class="role-desc">Atención interactiva de clientes en tiempo real, registro de mensajes omnicanal y ficha de contacto comercial.</p>
      <div class="role-footer">
        <span class="role-tag">Atención en Vivo</span>
        <div class="role-action-arrow">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </div>
      </div>
    </div>
  </main>

  <footer class="portal-footer">
    <div class="portal-status">
      <span class="dot"></span>
      Servicio en línea
    </div>
    <span>•</span>
    <span>Versión 2.6</span>
  </footer>
</div>

<script>
function goTo(role) {
  window.location.href = role + '.php';
}

// Enable keyboard navigation
document.querySelectorAll('.role-card').forEach(card => {
  card.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      card.click();
    }
  });
});
</script>

<footer style="margin-top: auto; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
  Loyaltel Contact Center Platform © 2026 - Desarrollado por Paulo Tenorio
</footer>
</body>
</html>
