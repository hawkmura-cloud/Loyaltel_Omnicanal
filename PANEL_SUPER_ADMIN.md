# Panel de Super Administrador - Documentación

## 📋 Resumen

Se ha implementado un panel de super administrador con acceso total para gestionar usuarios y roles del sistema.

---

## 🔐 Credenciales

### Super Administrador
- **URL**: `http://127.0.0.1:8080/superadmin.html`
- **Usuario**: `superadmin`
- **Contraseña**: `superadmin123`

---

## 🎯 Funcionalidades

### 1. Gestión de Usuarios (CRUD)

#### Ver Usuarios
- Lista completa de todos los usuarios del sistema
- Información mostrada:
  - Nombre
  - Usuario
  - Rol (Asesor, Supervisor, Super Admin)
  - Estado (Disponible, Ocupado, Desconectado)
  - Teléfono
  - Fecha de registro

#### Crear Usuario
- Botón "+ Nuevo Usuario"
- Campos requeridos:
  - Nombre
  - Usuario
  - Contraseña
  - Rol (Asesor o Supervisor)
- Campos opcionales:
  - Teléfono
  - Estado (por defecto: Disponible)

#### Editar Usuario
- Botón "Editar" en cada fila
- Permite modificar todos los campos
- **Protección**: No se puede editar la cuenta de superadmin

#### Eliminar Usuario
- Botón "Eliminar" en cada fila
- **Protección**: No se puede eliminar la cuenta de superadmin
- **Validación**: No se pueden eliminar usuarios con conversaciones activas

---

## 🛡️ Seguridad

### Roles Disponibles
1. **super_admin**: Acceso total, puede gestionar usuarios
2. **supervisor**: Acceso al panel de supervisión
3. **asesor**: Acceso solo a sus conversaciones asignadas

### Middleware Implementado
- `requireSuperAdmin`: Verifica que el usuario tenga rol de super_admin
- Protección en endpoints de gestión de usuarios
- Protección en frontend para no mostrar acciones en superadmin

### Validaciones
- ✅ No se puede modificar la cuenta de superadmin
- ✅ No se puede eliminar la cuenta de superadmin
- ✅ No se pueden eliminar usuarios con conversaciones activas
- ✅ Roles válidos: asesor, supervisor (superadmin no se puede asignar desde el panel)

---

## 🔌 Endpoints API

| Endpoint | Método | Rol | Descripción |
|----------|--------|-----|-------------|
| `/api/usuarios` | GET | super_admin | Obtener todos los usuarios |
| `/api/usuarios` | POST | super_admin | Crear nuevo usuario |
| `/api/usuarios/:id` | PUT | super_admin | Actualizar usuario |
| `/api/usuarios/:id` | DELETE | super_admin | Eliminar usuario |

---

## 📂 Archivos Modificados/Creados

### Backend
- `backend/src/middleware/auth.js` - Agregado `requireSuperAdmin`
- `backend/server.js` - Endpoints CRUD para usuarios

### Frontend
- `frontend/superadmin.html` - Panel de super administrador (nuevo)

### Base de Datos
- Tabla `asesores` - ENUM `rol` modificado para incluir `super_admin`

---

## 🎨 Diseño

### Identidad Visual
- Colores Loyaltel (azul vibrante y negro)
- Logo de Loyaltel en el header
- Diseño consistente con otros paneles

### Componentes
- Tabla de usuarios con badges de rol y estado
- Modal para crear/editar usuarios
- Login dedicado para super admin
- Header con indicador de conexión

---

## 🚀 Servidor Backend

El servidor backend está corriendo en `http://localhost:3000` ✅

---

## ⚠️ Importante

- La cuenta de superadmin es la única que puede crear y gestionar usuarios
- No se puede eliminar la cuenta de superadmin desde el propio panel
- Para eliminar la cuenta de superadmin, se debe hacer directamente en la base de datos
- Las contraseñas se almacenan con bcrypt (hashing seguro)

---

## 📝 Próximos Pasos Sugeridos

1. **Logs de auditoría**: Registrar todas las acciones del super admin
2. **Límite de intentos**: Proteger contra ataques de fuerza bruta en login
3. **2FA**: Implementar autenticación de dos factores para super admin
4. **Notificaciones**: Alertar cuando se crea un nuevo usuario
5. **Historial de cambios**: Guardar cambios en usuarios con fecha y responsable

---

**Fecha de implementación:** 2026-10-09
**Estado:** ✅ Completado
**Servidor:** Corriendo en http://localhost:3000
