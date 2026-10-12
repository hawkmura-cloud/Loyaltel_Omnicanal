# Loyaltel Contact Center

Sistema omnicanal de atención al cliente para call centers, con integración con WhatsApp Business Platform.

## 🎯 Características

- **Panel de Supervisor:** Dashboard en tiempo real, asignación de conversaciones, métricas
- **Espacio de Asesor:** Chat con clientes, historial, gestión de estados
- **Panel de Super Admin:** Gestión de usuarios, clientes y campañas WhatsApp
- **Integración WhatsApp:** Webhook, envío de mensajes, gestión de campañas
- **Registro de Mensajes Externos:** Para respuestas desde WhatsApp Web/Celular
- **Sistema de Colas:** Asignación automática de conversaciones
- **Real-time Updates:** Server-Sent Events para actualizaciones en vivo
- **Autenticación JWT:** Roles: superadmin, supervisor, asesor

## 🚀 Quick Start

### Desarrollo Local

**Requisitos:**
- Node.js 18+
- MySQL 8.0+
- PHP 8.0+

**Instalación:**
```bash
# Clonar repositorio
git clone https://github.com/hawkmura-cloud/claro-chatbot.git
cd claro-chatbot

# Configurar base de datos MySQL
# Crear base de datos: claro_chatbot
# Ejecutar migraciones (ver backend/migrate_*.js)

# Configurar backend
cd backend
npm install
cp .env.example .env
# Editar .env con tus credenciales

# Iniciar backend
node server.js

# En otra terminal, iniciar frontend
cd frontend
php -S localhost:8080
```

### Docker

```bash
# Construir y ejecutar
docker-compose up -d

# Servicios:
# - Backend: http://localhost:3000
# - Frontend: http://localhost:80
# - MySQL: localhost:3306
```

Ver [DOCKER.md](DOCKER.md) para más detalles.

## 📱 Integración WhatsApp

Ver [INTEGRACION_WHATSAPP.md](INTEGRACION_WHATSAPP.md) para configuración completa de WhatsApp Business Platform.

## 🔐 Credenciales por Defecto

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Super Admin | superadmin | superadmin123 |
| Supervisor | supervisor | supervisor123 |
| Asesor | asesor1 | asesor123 |

**⚠️ Cambiar estas credenciales en producción.**

## 📁 Estructura del Proyecto

```
claro-chatbot/
├── backend/              # API Node.js
│   ├── src/
│   │   ├── config/      # Configuración de base de datos
│   │   ├── middleware/  # Middleware de autenticación
│   │   ├── services/    # Servicios de negocio
│   │   └── utils/       # Utilidades (SSE, logger)
│   ├── migrate_*.js     # Scripts de migración
│   └── server.js        # Servidor Express
├── frontend/            # Panel PHP
│   ├── index.php        # Selección de roles
│   ├── supervision.php  # Panel supervisor
│   ├── asesor.php       # Panel asesor
│   └── superadmin.php   # Panel super admin
├── docker-compose.yml    # Configuración Docker
├── Dockerfile.backend    # Docker para backend
└── Dockerfile.frontend  # Docker para frontend
```

## 🛠 Tecnologías

- **Backend:** Node.js, Express, MySQL
- **Frontend:** PHP, JavaScript, HTML/CSS
- **Auth:** JWT, bcrypt
- **Real-time:** Server-Sent Events (SSE)
- **WhatsApp:** Meta Graph API
- **Container:** Docker, Docker Compose

## 📖 Documentación

- [INSTRUCCIONES_DESPLIEGUE.md](INSTRUCCIONES_DESPLIEGUE.md) - Guía de despliegue
- [INTEGRACION_WHATSAPP.md](INTEGRACION_WHATSAPP.md) - Integración WhatsApp
- [DOCKER.md](DOCKER.md) - Despliegue con Docker
- [AUDITORIA_SEGURIDAD.md](AUDITORIA_SEGURIDAD.md) - Auditoría de seguridad
- [PANEL_SUPER_ADMIN.md](PANEL_SUPER_ADMIN.md) - Guía del panel super admin

## 🤝 Contribuciones

Este es un proyecto privado. Contactar al mantenedor para contribuciones.

## 📄 Licencia

Propiedad de Loyaltel. Todos los derechos reservados.

## 🆘 Soporte

Para soporte técnico, contactar al equipo de desarrollo de Loyaltel.
