# Documentación Técnica - Loyaltel Contact Center

## 📋 Requisitos del Sistema

### Hardware Mínimo
- CPU: 2 núcleos
- RAM: 4GB
- Disco: 20GB

### Software Requerido
- Docker Desktop
- Git
- Node.js 18+ (para desarrollo local sin Docker)
- PHP 8.0+ (para desarrollo local sin Docker)

---

## 🐳 Docker

### Iniciar el Sistema

```bash
# Clonar repositorio
git clone https://github.com/hawkmura-cloud/Loyaltel_Omnicanal.git
cd Loyaltel_Omnicanal

# Iniciar todos los servicios
docker-compose up -d --build
```

### Servicios

| Servicio | Puerto | Descripción |
|----------|--------|-------------|
| mysql | 3306 | Base de datos MySQL 8.4 |
| backend | 3000 | API Node.js |
| frontend | 80 | PHP Apache |

### Comandos Útiles

```bash
# Ver estado
docker-compose ps

# Ver logs
docker-compose logs -f

# Logs específicos
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql

# Reiniciar servicios
docker-compose restart backend
docker-compose restart frontend

# Detener todo
docker-compose down

# Eliminar todo (incluyendo volúmenes)
docker-compose down -v

# Reconstruir
docker-compose up -d --build
```

### Base de Datos MySQL

**Credenciales:**
- Usuario: `claro_app`
- Contraseña: `claro_app_2026`
- Base de datos: `claro_chatbot`
- Root password: `claro_root_2026`

**Acceder a MySQL:**
```bash
docker-compose exec mysql mysql -u claro_app -pclaro_app_2026 claro_chatbot
```

---

## 🚀 Despliegue en Producción

### Opción 1: Servidor con Docker

**Requisitos:**
- Servidor Linux (Ubuntu 22.04 recomendado)
- Docker y Docker Compose instalados
- Dominio con HTTPS (requisito de WhatsApp)

**Pasos:**
1. Clonar repositorio en servidor
2. Configurar variables de entorno
3. Ejecutar `docker-compose up -d --build`
4. Configurar proxy reverso (nginx/caddy) para SSL

### Opción 2: DigitalOcean (Recomendado)

**Pasos:**
1. Crear Droplet Ubuntu 22.04 (2GB RAM, $5/mes)
2. Instalar Docker y Docker Compose
3. Clonar repositorio
4. Ejecutar `docker-compose up -d --build`
5. Configurar dominio y SSL con Let's Encrypt

---

## 📱 Integración WhatsApp Business Platform

### Credenciales Configuradas

- **Phone Number ID:** 1047615305094941 (Claro)
- **WhatsApp Business Account ID:** 1107510665091182
- **Access Token:** Configurado en backend/.env.docker
- **Webhook Verify Token:** loyaltel_whatsapp_verify_2026_secure

### Configurar Webhook en Meta

1. Ve a Meta Business Suite → WhatsApp Manager → Configuration → Webhooks
2. **URL de devolución de llamada:** `https://tu-dominio.com/webhook/whatsapp`
3. **Identificador de verificación:** `loyaltel_whatsapp_verify_2026_secure`
4. Suscribir a eventos: `messages`, `message_status`

### Estado Actual

- ✅ Webhook configurado y funcionando
- ✅ Campaña Claro creada (número: 51972477712)
- ⏸️ Empresa Meta no verificada (bloqueo para agregar números reales)
- ⏸️ Plantilla en revisión (esperando aprobación Meta)

### Pasos para Activar WhatsApp Real

1. **Verificar empresa en Meta** (1-2 semanas)
   - Enviar documentación de empresa
   - Esperar aprobación

2. **Agregar números reales**
   - Una vez verificada, agregar número de Claro
   - Agregar número de Win
   - Conectar a la aplicación

3. **Actualizar Phone IDs**
   - Por cada número, actualizar en backend/.env.docker:
     ```
     WHATSAPP_PHONE_NUMBER_ID=nuevo_phone_id
     ```
   - Reiniciar backend

4. **Plantillas de mensaje**
   - Crear plantillas pre-aprobadas para mensajes fuera de ventana
   - Esperar aprobación de Meta (24-48 horas)

---

## 🔐 Seguridad

### JWT Authentication

- Tokens expiran en 8 horas
- Secret configurado en variables de entorno
- Roles: superadmin, supervisor, asesor

### Base de Datos

- Contraseñas en variables de entorno (no en código)
- MySQL con usuario con permisos limitados
- Variables de entorno en .gitignore

### WhatsApp

- Access token permanente configurado
- Webhook con verify token
- No exponer credenciales en frontend

---

## 🐛 Troubleshooting

### Contenedor no inicia

```bash
docker-compose logs backend
docker-compose logs frontend
docker-compose logs mysql
```

### Base de datos no conecta

```bash
docker-compose ps mysql
docker-compose restart mysql
```

### Webhook no recibe mensajes

1. Verificar que ngrok esté corriendo
2. Verificar URL en Meta
3. Verificar que el Phone ID sea correcto
4. Verificar suscripción a eventos en Meta

### Reinstalar desde cero

```bash
docker-compose down -v
docker-compose up -d --build
```

---

## 📊 Monitoreo

### Ver Logs

```bash
# Backend
docker logs claro_backend -f

# Frontend
docker logs claro_frontend -f

# MySQL
docker logs claro_mysql -f
```

### Ver Base de Datos

```bash
# Ver conversaciones
docker exec claro_mysql mysql -u claro_app -pclaro_app_2026 claro_chatbot -e "SELECT * FROM conversaciones;"

# Ver mensajes
docker exec claro_mysql mysql -u claro_app -pclaro_app_2026 claro_chatbot -e "SELECT * FROM mensajes;"
```

---

## 🔄 Actualizar el Sistema

### Pull de cambios

```bash
git pull origin master
docker-compose up -d --build
```

### Resetear base de datos

```bash
docker-compose down -v
docker-compose up -d --build
```
