<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loyaltel - Plataforma Omnicanal</title>
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
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-container {
  background: white;
  padding: 50px;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  width: 450px;
  text-align: center;
}
.logo {
  width: 150px;
  height: auto;
  margin-bottom: 24px;
}
h1 {
  font-size: 28px;
  font-weight: 800;
  color: var(--loyaltel-dark);
  margin-bottom: 8px;
}
.subtitle {
  color: var(--muted);
  font-size: 14px;
  margin-bottom: 32px;
}
.role-buttons {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.role-btn {
  padding: 16px 24px;
  border: 2px solid var(--border);
  border-radius: 12px;
  background: white;
  color: var(--text);
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  text-align: left;
  display: flex;
  align-items: center;
  gap: 12px;
}
.role-btn:hover {
  border-color: var(--loyaltel-blue);
  background: #f8f9fb;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.role-btn .icon {
  font-size: 24px;
}
.role-btn .title {
  flex: 1;
}
.role-btn .desc {
  font-size: 12px;
  color: var(--muted);
  font-weight: 400;
  margin-top: 4px;
}
.role-btn.superadmin {
  border-color: var(--loyaltel-blue);
  background: #f0f7ff;
}
.role-btn.supervision {
  border-color: var(--loyaltel-light-blue);
  background: #f5faff;
}
.role-btn.asesor {
  border-color: #28a745;
  background: #f0fdf4;
}
</style>
</head>
<body>
<div class="login-container">
  <img src="logo-loyaltel-alt.png" alt="Loyaltel Logo" class="logo">
  <h1>Loyaltel</h1>
  <p class="subtitle">Plataforma Omnicanal de Contact Center</p>
  
  <div class="role-buttons">
    <button class="role-btn superadmin" onclick="goTo('superadmin')">
      <span class="icon">👑</span>
      <div class="content">
        <div class="title">Super Administrador</div>
        <div class="desc">Gestión de usuarios, clientes y campañas</div>
      </div>
    </button>
    
    <button class="role-btn supervision" onclick="goTo('supervision')">
      <span class="icon">👨‍💼</span>
      <div class="content">
        <div class="title">Supervisión</div>
        <div class="desc">Monitoreo de conversaciones y asesores</div>
      </div>
    </button>
    
    <button class="role-btn asesor" onclick="goTo('asesor')">
      <span class="icon">🎧</span>
      <div class="content">
        <div class="title">Asesor</div>
        <div class="desc">Atención de clientes en tiempo real</div>
      </div>
    </button>
  </div>
</div>

<script>
function goTo(role) {
  window.location.href = role + '.php';
}
</script>
</body>
</html>
