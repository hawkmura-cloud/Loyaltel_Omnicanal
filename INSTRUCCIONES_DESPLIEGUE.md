# Instrucciones de Despliegue

Este documento contiene las instrucciones paso a paso para desplegar el sistema Loyaltel Contact Center en una nueva computadora.

---

## 📋 Requisitos Previos

### Software Necesario

1. **Node.js** (v18 o superior)
   - Descargar: https://nodejs.org/
   - Instalar la versión LTS recomendada

2. **MySQL** (v8.0 o superior)
   - Descargar: https://dev.mysql.com/downloads/mysql/
   - Instalar con configuración por defecto
   - Anotar la contraseña de root

3. **PHP** (v8.0 o superior)
   - Descargar: https://windows.php.net/download/
   - Seleccionar la versión Thread Safe (TS) x64
   - Extraer en `C:\php`
   - Agregar `C:\php` al PATH del sistema

4. **Git** (opcional, para clonar el repositorio)
   - Descargar: https://git-scm.com/

---

## 📦 Instalación

### 1. Obtener el Código

**Opción A - Copiar carpeta:**
- Copia la carpeta completa del proyecto
- Pégala en la ubicación deseada (ej: `C:\Proyectos\claro-chatbot`)

**Opción B - Git:**
```bash
git clone <url-del-repositorio>
cd claro-chatbot
```

### 2. Configurar Base de Datos MySQL

1. Abre MySQL Workbench o la línea de comandos de MySQL
2. Crea la base de datos:
```sql
CREATE DATABASE claro_chatbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Crea el usuario de la base de datos:
```sql
CREATE USER 'claro_app'@'%' IDENTIFIED BY 'claro_app_2026';
GRANT ALL PRIVILEGES ON claro_chatbot.* TO 'claro_app'@'%';
FLUSH PRIVILEGES;
```

4. Ejecuta el script de base de datos:
```bash
mysql -u root -p claro_chatbot < database.sql
```

O desde MySQL Workbench:
- Abre la conexión a MySQL
- Carga el archivo `database.sql`

### 3. Configurar Variables de Entorno

1. Ve a la carpeta `backend`
2. Crea el archivo `.env` con el siguiente contenido:
```env
# Base de datos
DB_HOST=localhost
DB_PORT=3306
DB_USER=claro_app
DB_PASSWORD=claro_app_2026
DB_NAME=claro_chatbot

# JWT
JWT_SECRET=claro_chatbot_secret_key_2026_CHANGE_IN_PRODUCTION
JWT_EXPIRES_IN=8h

# WhatsApp (opcional, para integración)
WHATSAPP_VERIFY_TOKEN=loyaltel_webhook_2026
```

**IMPORTANTE:** Cambia `JWT_SECRET` y `DB_PASSWORD` en producción.

### 4. Instalar Dependencias de Node.js

1. Abre una terminal o PowerShell
2. Navega a la carpeta `backend`:
```bash
cd C:\Proyectos\claro-chatbot\backend
```

3. Instala las dependencias:
```bash
npm install
```

### 5. Ejecutar Migraciones de Base de Datos

Si hay scripts de migración en la carpeta `backend/`, ejecútalos:
```bash
node migrate_db.js
node migrate_auth.js
node migrate_omnicanal.js
node migrate_superadmin.js
node migrate_whatsapp.js
node migrate_externos.js
```

**Nota:** `migrate_externos.js` agrega el campo `origen_mensaje` para registrar mensajes enviados desde WhatsApp Web/Celular fuera del sistema.

---

## 🚀 Iniciar el Sistema

### 1. Iniciar el Servidor Backend

1. Abre una terminal
2. Navega a la carpeta `backend`:
```bash
cd C:\Proyectos\claro-chatbot\backend
```

3. Inicia el servidor:
```bash
node server.js
```

El servidor iniciará en `http://localhost:3000`

### 2. Iniciar el Servidor Frontend

1. Abre otra terminal
2. Navega a la carpeta `frontend`:
```bash
cd C:\Proyectos\claro-chatbot\frontend
```

3. Inicia el servidor PHP:
```bash
php -S localhost:8080
```

El servidor iniciará en `http://localhost:8080`

---

## 🔐 Credenciales por Defecto

### Super Administrador
- **Usuario:** `superadmin`
- **Contraseña:** `superadmin123`
- **URL:** `http://localhost:8080/superadmin.html`

### Supervisor
- **Usuario:** `admin`
- **Contraseña:** `admin123`
- **URL:** `http://localhost:8080/supervision.php`

### Asesores
- **Asesor 1:** `asesor1` / `asesor1123`
- **Asesor 2:** `asesor2` / `asesor2123`
- **Asesor 3:** `asesor3` / `asesor3123`
- **URL:** `http://localhost:8080/asesor.html`

---

## 📡 Acceso al Sistema

1. Abre el navegador
2. Ve a: `http://localhost:8080/index.php`
3. Selecciona el rol deseado:
   - 👑 Super Administrador
   - 👨‍💼 Supervisión
   - 🎧 Asesor
4. Ingresa las credenciales correspondientes

---

## ⚙️ Configuración Adicional

### Habilitar HTTPS (Producción)

Para producción, necesitas:
1. Certificado SSL (Let's Encrypt o commercial)
2. Configurar nginx o Apache como reverse proxy
3. Configurar Node.js detrás del proxy

### Configurar Webhook de WhatsApp

1. Ve al panel de Super Admin
2. Sección "Campañas WhatsApp"
3. Crea una nueva campaña con:
   - Número de WhatsApp
   - Phone ID de Meta
   - Access Token de Meta
4. Configura el webhook en Meta Business Manager
   - URL: `https://tu-dominio.com/webhook/whatsapp`
   - Verify Token: `loyaltel_webhook_2026`

---

## 🔧 Solución de Problemas

### Error: "Module not found"
**Solución:**
```bash
cd backend
npm install
```

### Error: "Access denied for user"
**Solución:**
- Verifica que el usuario de MySQL exista
- Verifica la contraseña en `.env`
- Verifica que el usuario tenga permisos en la base de datos

### Error: "php no se reconoce"
**Solución:**
- Verifica que PHP esté instalado en `C:\php`
- Agrega `C:\php` al PATH del sistema
- Reinicia la terminal

### Error: "Conexión rechazada"
**Solución:**
- Verifica que MySQL esté corriendo
- Verifica que el puerto 3306 esté disponible
- Verifica el firewall

### El frontend no carga
**Solución:**
- Verifica que el servidor PHP esté corriendo
- Verifica que no haya otro servicio usando el puerto 8080
- Intenta con otro puerto: `php -S localhost:8081`

---

## 📁 Estructura del Proyecto

```
claro-chatbot/
├── backend/
│   ├── server.js              # Servidor principal
│   ├── package.json           # Dependencias Node.js
│   ├── .env                   # Variables de entorno
│   ├── .env.example           # Plantilla de .env
│   ├── src/
│   │   ├── config/
│   │   │   └── database.js     # Configuración MySQL
│   │   ├── middleware/
│   │   │   └── auth.js         # Middleware JWT
│   │   └── services/
│   │       ├── whatsappService.js
│   │       └── ...
│   └── __tests__/
│       ├── integration.test.js
│       └── setup.js
├── frontend/
│   ├── index.php              # Panel de selección de roles
│   ├── supervision.php        # Panel de supervisor
│   ├── asesor.php            # Panel de asesor
│   ├── superadmin.php        # Panel de super admin
│   ├── logo-loyaltel.png
│   └── logo-loyaltel-alt.png
└── database.sql                # Script de base de datos
```

---

## 🧪 Ejecutar Pruebas

Para ejecutar las pruebas automatizadas:

```bash
cd backend
npm test
```

Las pruebas verificarán:
- Asignación de conversaciones
- Cola de atención
- Protección de concurrencia
- Autenticación JWT
- Respuestas duplicadas

---

## 📝 Notas Importantes

1. **Seguridad en Producción:**
   - Cambiar todas las contraseñas por defecto
   - Cambiar `JWT_SECRET` en `.env`
   - Usar HTTPS obligatoriamente
   - No exponer credenciales en el código

2. **Base de Datos:**
   - Hacer backup regular de la base de datos
   - Usar una base de datos separada para pruebas
   - Considerar usar Docker para contenerización

3. **Logs:**
   - Revisar logs del servidor Node.js regularmente
   - Implementar sistema de logs centralizado en producción

4. **Monitoreo:**
   - Implementar monitoreo de errores (Sentry, etc.)
   - Monitorear rendimiento y uptime
   - Configurar alertas para fallos críticos

---

## 📚 Documentación Adicional

- **Integración WhatsApp:** `INTEGRACION_WHATSAPP.md`
- **Pruebas Automatizadas:** `PRUEBAS.md`
- **Auditoría de Seguridad:** `AUDITORIA_SEGURIDAD.md`
- **Cambios Omnicanal:** `CAMBIOS_OMNICANAL.md`
- **Panel Super Admin:** `PANEL_SUPER_ADMIN.md`

---

## 🆘 Soporte

Si encuentras problemas:
1. Revisa la sección de solución de problemas
2. Verifica los logs del servidor
3. Consulta la documentación adicional
4. Revisa que todos los requisitos estén instalados

---

## ✅ Checklist de Despliegue

- [ ] Node.js instalado
- [ ] MySQL instalado y configurado
- [ ] PHP instalado y en PATH
- [ ] Base de datos creada
- [ ] Usuario de base de datos creado
- [ ] Script database.sql ejecutado
- [ ] Archivo .env configurado
- [ ] Dependencias Node.js instaladas
- [ ] Migraciones ejecutadas
- [ ] Servidor backend iniciado
- [ ] Servidor frontend iniciado
- [ ] Acceso a paneles verificado
- [ ] Login funcional
- [ ] Contraseñas cambiadas (producción)

---

**Última actualización:** 2026-10-09
