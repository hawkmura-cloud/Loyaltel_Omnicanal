# Guía de Usuario - Loyaltel Contact Center

## 👥 Cómo Mover el Proyecto a Otra PC

### Paso 1: Instalar Requisitos

En la nueva PC, instala:

1. **Git** - https://git-scm.com/download/win
2. **Docker Desktop** - https://www.docker.com/products/docker-desktop/

### Paso 2: Clonar el Repositorio

```bash
git clone https://github.com/hawkmura-cloud/Loyaltel_Omnicanal.git
cd Loyaltel_Omnicanal
```

### Paso 3: Iniciar el Sistema

```bash
docker-compose up -d --build
```

### Paso 4: Acceder al Sistema

- **Frontend:** http://localhost:80
- **Backend:** http://localhost:3000

### Paso 5: Verificar Funcionamiento

1. Accede a http://localhost:80
2. Inicia sesión como superadmin (superadmin/superadmin123)
3. Verifica que los paneles funcionen
4. Accede como supervisor (supervisor/supervisor123)
5. Accede como asesor (asesor1/asesor123)

---

## 🎯 Panel de Super Admin

### Credenciales
- Usuario: `superadmin`
- Contraseña: `superadmin123`

### Funcionalidades

#### Gestión de Usuarios
1. Ve a "Usuarios"
2. Haz clic en "+ Nuevo Usuario"
3. Completa: Nombre, teléfono, usuario, password, rol
4. Roles disponibles: asesor, supervisor

#### Gestión de Clientes
1. Ve a "Clientes"
2. Haz clic en "+ Nuevo Cliente"
3. Completa: Nombre, código, descripción
4. El cliente se usa para agrupar campañas WhatsApp

#### Gestión de Campañas WhatsApp
1. Ve a "Campañas WhatsApp"
2. Haz clic en "+ Nueva Campaña"
3. Completa:
   - Cliente (selecciona de la lista)
   - Nombre de la campaña
   - Número de WhatsApp (sin +)
   - Phone ID (de Meta)
   - Access Token (de Meta)
4. Activa la campaña

---

## 👨‍💼 Panel de Supervisor

### Credenciales
- Usuario: `supervisor`
- Contraseña: `supervisor123`

### Funcionalidades

#### Dashboard
- Ver métricas en tiempo real
- Total de conversaciones
- Conversaciones activas
- Asesores disponibles

#### Lista de Conversaciones
- Ver todas las conversaciones
- Asignar conversaciones a asesores
- Ver estado de cada conversación
- Finalizar conversaciones

#### Métricas de Ventas
- Ver asesor con más ventas
- Ver ranking de asesores
- Exportar reportes

---

## 👨‍💻 Panel de Asesor

### Credenciales
- Usuario: `asesor1`
- Contraseña: `asesador123`

### Funcionalidades

#### Estado de Disponibilidad
- Cambiar estado: Disponible, Ocupado, Desconectado
- El estado afecta asignación de conversaciones

#### Lista de Conversaciones
- Ver conversaciones asignadas
- Ver no leídas
- Ver en curso

#### Chat
- Enviar mensajes al cliente
- Ver historial de conversación
- Ver mensajes del cliente

#### Ficha del Cliente
- Ver información del cliente
- Editar datos del cliente
- Ver historial completo

#### Finalizar Conversación
- Seleccionar estado de negocio:
  - venta
  - no cobertura
  - falta información
  - volver a llamar
  - no desea
  - deuda
  - spam
  - etc.

#### Registrar Mensaje Externo
- Si respondes desde WhatsApp Web/celular
- Haz clic en "📱 Registrar mensaje externo"
- Ingresa el mensaje que enviaste
- El sistema lo guarda en el historial

---

## 📱 WhatsApp Business Platform

### Cómo Funciona

#### Recepción de Mensajes
1. Cliente envía mensaje al número de WhatsApp
2. Meta envía el mensaje al webhook del sistema
3. Sistema crea/actualiza conversación
4. Sistema guarda el mensaje
5. Asesor ve el mensaje en tiempo real

#### Envío de Mensajes
1. Asesor responde desde el panel
2. Sistema guarda el mensaje
3. Sistema envía por WhatsApp API
4. Cliente recibe el mensaje

#### Mensajes Externos
- Si asesor responde desde WhatsApp Web/celular
- Debe registrar el mensaje manualmente
- Usa el botón "📱 Registrar mensaje externo"
- Esto mantiene el historial completo

### Política de Uso

**✅ Recomendado:**
- Siempre responder desde el panel del asesor
- Esto garantiza historial completo
- Métricas de ventas funcionan correctamente

**⚠️ Si usas WhatsApp Web:**
- Debes registrar el mensaje manualmente
- Esto asegura que el sistema tenga el registro
- Métricas pueden estar incompletas si no registras

---

## 🧪 Pruebas del Sistema

### Prueba 1: Iniciar Sesión

1. Accede a http://localhost:80
2. Selecciona "Super Admin"
3. Ingresa: superadmin / superadmin123
4. Verifica que el panel cargue

### Prueba 2: Crear Usuario

1. En Super Admin, ve a "Usuarios"
2. Crea un asesor de prueba
3. Verifica que se guarde

### Prueba 3: Iniciar como Supervisor

1. Cierra sesión
2. Accede como supervisor / supervisor123
3. Verifica dashboard
4. Verifica lista de conversaciones

### Prueba 4: Iniciar como Asesor

1. Cierra sesión
2. Accede como asesor1 / asesor123
3. Verifica lista de conversaciones
4. Si hay conversaciones asignadas, ábrelas
5. Envía un mensaje de prueba

### Prueba 5: Finalizar Conversación

1. En el panel del asesor
2. Abre una conversación
3. Selecciona estado: "venta"
4. Verifica que se guarde

---

## 🔧 Solución de Problemas

### No puedo acceder a http://localhost:80

**Solución:**
- Verifica que Docker esté corriendo: `docker-compose ps`
- Verifica que el contenedor frontend esté activo
- Reinicia: `docker-compose restart frontend`

### Error de conexión a base de datos

**Solución:**
- Verifica que MySQL esté healthy: `docker-compose ps mysql`
- Reinicia MySQL: `docker-compose restart mysql`
- Si persiste, reinstala: `docker-compose down -v && docker-compose up -d --build`

### No veo las conversaciones en el panel del asesor

**Solución:**
- Verifica que haya conversaciones asignadas al asesor
- En Super Admin, asigna una conversación al asesor
- Verifica que el asesor tenga el estado "disponible"

### Webhook de WhatsApp no funciona

**Solución:**
- Verifica que ngrok esté corriendo (si usas ngrok)
- Verifica la URL del webhook en Meta
- Verifica el Phone ID en la configuración
- Verifica suscripción a eventos en Meta

---

## 📝 Notas Importantes

### Credenciales de Producción

⚠️ **IMPORTANTE:** Cambiar las credenciales por defecto antes de producción:

- superadmin / superadmin123
- supervisor / supervisor123
- asesor1 / asesor123

### Ventana de 24 Horas de WhatsApp

WhatsApp permite enviar mensajes solo dentro de las 24 horas después del último mensaje del cliente. Para mensajes fuera de ventana, necesitas plantillas pre-aprobadas en Meta.

### Meta Business Verification

Para agregar números reales a la API, Meta requiere:
- Verificación de empresa (1-2 semanas)
- Documentación de empresa
- Comprobante de domicilio

Este proceso está fuera de nuestro control.

---

## 📞 Soporte

Para soporte técnico, contactar al equipo de desarrollo de Loyaltel.
