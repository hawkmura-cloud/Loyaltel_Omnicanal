# Loyaltel Contact Center Platform

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

## � Credenciales por Defecto

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Super Admin | superadmin | superadmin123 |
| Supervisor | supervisor | supervisor123 |
| Asesor | asesor1 | asesor123 |

**⚠️ Cambiar estas credenciales en producción.**

## 🚀 Quick Start (Docker)

### Requisitos Previos

- Docker Desktop instalado
- Git instalado

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/hawkmura-cloud/Loyaltel_Omnicanal.git
cd Loyaltel_Omnicanal
```

### Paso 2: Configurar Variables de Entorno

El backend usa variables de entorno configuradas en `backend/.env.docker`. Para producción, puedes crear `backend/.env.production` con tus propias credenciales.

### Paso 3: Iniciar el Sistema

```bash
docker-compose up -d --build
```

### Paso 4: Acceder al Sistema

- **Frontend:** http://localhost:80
- **Backend API:** http://localhost:3000
- **MySQL:** localhost:3306

### Paso 5: Detener el Sistema

```bash
docker-compose down
```

## � Estructura del Proyecto

```
Loyaltel_Omnicanal/
├── backend/              # API Node.js
│   ├── src/
│   │   ├── config/      # Configuración de base de datos
│   │   ├── middleware/  # Middleware de autenticación
│   │   ├── services/    # Servicios de negocio
│   │   └── utils/       # Utilidades (SSE, logger)
│   ├── server.js        # Servidor Express
│   └── .env.docker     # Variables de entorno Docker
├── frontend/            # Panel PHP
│   ├── index.php        # Selección de roles
│   ├── supervision.php  # Panel supervisor
│   ├── asesor.php       # Panel asesor
│   ├── superadmin.php   # Panel super admin
│   └── css/             # Estilos modernos
├── docker-compose.yml    # Configuración Docker
├── Dockerfile.backend    # Docker para backend
└── Dockerfile.frontend  # Docker para frontend
```

## 🛠 Tecnologías

- **Backend:** Node.js 18, Express, MySQL 8.4
- **Frontend:** PHP 8.2, Apache, JavaScript
- **Auth:** JWT, bcrypt
- **Real-time:** Server-Sent Events (SSE)
- **WhatsApp:** Meta Graph API
- **Container:** Docker, Docker Compose

## 📖 Documentación Adicional

- [DOCUMENTACION_TECNICA.md](DOCUMENTACION_TECNICA.md) - Docker, Despliegue, WhatsApp, Seguridad
- [GUIA_USUARIO.md](GUIA_USUARIO.md) - Paneles de usuario, Pruebas

## 🤝 Licencia

Propiedad de Loyaltel. Todos los derechos reservados.

## 🆘 Soporte

Para soporte técnico, contactar al equipo de desarrollo de Loyaltel.
