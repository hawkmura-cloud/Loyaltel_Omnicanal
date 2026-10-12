# Integración con WhatsApp Business Platform

Este documento explica cómo configurar la integración del sistema con WhatsApp Business Platform para recibir y enviar mensajes reales de clientes.

---

## 📋 Requisitos Previos

1. **Cuenta de Meta Business**: Necesitas una cuenta de Meta Business (Facebook Business)
2. **WhatsApp Business API Access**: Debes tener acceso a la API de WhatsApp Business Platform
3. **Número de WhatsApp Business**: Un número de teléfono habilitado para WhatsApp Business
4. **Servidor HTTPS**: El webhook requiere HTTPS para recibir mensajes de Meta

---

## 🔧 Configuración en Meta Business

### 1. Crear una App en Meta

1. Ve a [Meta for Developers](https://developers.facebook.com/)
2. Crea una nueva app
3. Selecciona "Business" como tipo de app
4. Agrega el producto "WhatsApp"

### 2. Configurar WhatsApp Business API

1. En la sección "WhatsApp", selecciona "Getting Started"
2. Selecciona o crea un número de WhatsApp Business
3. Obtén:
   - **Phone ID**: ID del número de WhatsApp
   - **Access Token**: Token de acceso de larga duración
   - **WABA ID**: ID de la cuenta de WhatsApp Business

### 3. Configurar el Webhook

1. En la sección "Webhooks", configura tu URL de webhook:
   ```
   https://tu-dominio.com/webhook/whatsapp
   ```
2. Verifica el webhook con el token:
   ```
   Verify Token: loyaltel_webhook_2026
   ```
3. Suscríbete a los eventos:
   - `messages` (para recibir mensajes)
   - `message_status` (para confirmaciones de entrega)

---

## ⚙️ Configuración en el Sistema

### 1. Crear Cliente (si no existe)

1. Ingresa al panel de Super Admin
2. Ve a la sección "Campañas WhatsApp"
3. Asegúrate de que el cliente exista en la tabla de clientes

### 2. Crear Campaña de WhatsApp

1. En el panel de Super Admin, ve a "Campañas WhatsApp"
2. Haz clic en "+ Nueva Campaña"
3. Completa los campos:

| Campo | Descripción | Ejemplo |
|-------|-------------|---------|
| Cliente | Cliente asociado | Claro |
| Nombre | Nombre de la campaña | Campaña Promocional |
| Descripción | Descripción opcional | Ventas de internet |
| Número WhatsApp | Número de WhatsApp (sin +) | 51987654321 |
| API Key | Opcional, para autenticación | xxxxx |
| Phone ID | ID del número de WhatsApp | 123456789012345 |
| Access Token | Token de acceso de larga duración | EAAG... |
| Webhook URL | URL del webhook (opcional) | https://... |
| Webhook Secret | Secreto para validar webhooks | secreto123 |
| Activo | Si la campaña está activa | ✅ |

### 3. Variables de Entorno

Agrega estas variables al archivo `.env` (obtenidas desde Meta Business Suite):

```env
# Configuración WhatsApp Business Platform
WHATSAPP_PHONE_NUMBER_ID=1359816560552036
WHATSAPP_BUSINESS_ACCOUNT_ID=1107510665091182
WHATSAPP_ACCESS_TOKEN=EAAXNPT58kD0BSjkc2SQvjAVnJUzG5WdS7xV9H5h4eqEyZAj7CEcEOPQNTmWg7rXxm7tHMBvnGsAadZCTCxIHYLaTqaiUqFPCQWuR6Q2zTw9VszmFKZA6NW88R30jMApKLVZBnIutvDGLlnV1dYZAIZCSdxJV3AWIfRyZCtT2npP7IgGMyC9d4wZCahTbPanL4iJFe7UgOMKX5PCTNZBFKxZCSVqRAC9lRQY4TzhrkDZCfeOx68xNvmMbG8bgP16zxN0GBeeiy0xYASK2IZC5ZCC9ZCfZApxrgZDZD
WHATSAPP_WEBHOOK_VERIFY_TOKEN=loyaltel_whatsapp_verify_2026_secure
```

**Nota:** Las credenciales de WhatsApp se configuran globalmente en el `.env` para toda la aplicación.

---

## 🔄 Flujo de Mensajes

### Recepción de Mensajes

1. Cliente envía mensaje al número de WhatsApp configurado
2. Meta envía el mensaje al webhook del sistema
3. El sistema:
   - Identifica la campaña por el número de destino
   - Crea o actualiza la conversación
   - Guarda el mensaje en la base de datos
   - Notifica a asesores vía SSE

### Envío de Mensajes

1. Asesor responde una conversación en el panel
2. El sistema:
   - Guarda el mensaje en la base de datos
   - Verifica si la conversación tiene campaña WhatsApp configurada
   - Si está configurada, envía el mensaje por WhatsApp API
   - Si no está configurada, solo guarda el mensaje

---

## 🧪 Pruebas

### 1. Verificar Webhook

```bash
curl "https://tu-dominio.com/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=loyaltel_webhook_2026&hub.challenge=prueba"
```

Respuesta esperada: `prueba`

### 2. Enviar Mensaje de Prueba

Usa la API de WhatsApp directamente o envía un mensaje desde tu teléfono al número configurado.

---

## 🛡️ Seguridad

1. **HTTPS Obligatorio**: Meta requiere HTTPS para webhooks en producción
2. **Tokens Seguros**: Usa tokens de larga duración y guárdalos en la base de datos (no en código)
3. **Validación de Webhook**: Verifica el `webhook_secret` en cada solicitud
4. **Rate Limiting**: Implementa rate limiting para evitar abuse

---

## 📝 Notas Importantes

1. **Ventana de 24 Horas**: WhatsApp permite enviar mensajes solo dentro de las 24 horas después del último mensaje del cliente. Mensajes fuera de ventana requieren plantillas pre-aprobadas.

2. **Plantillas de Mensaje**: Para mensajes fuera de ventana, debes usar plantillas pre-aprobadas por Meta.

3. **Costos**: WhatsApp Business API tiene costos por conversación iniciada. Revisa los precios de Meta.

4. **Números por Campaña**: Cada campaña puede tener un número diferente. Esto permite múltiples campañas por cliente.

---

## 🔍 Troubleshooting

### Webhook no funciona

- Verifica que el servidor tenga HTTPS
- Confirma que el `WHATSAPP_VERIFY_TOKEN` coincida
- Revisa los logs del servidor para errores

### Mensajes no se envían

- Verifica que el `access_token` sea válido
- Confirma que el `phone_id` sea correcto
- Revisa que la campaña esté marcada como "Activa"

### Conversaciones no se crean

- Verifica que el número de destino coincida con una campaña activa
- Revisa los logs del webhook para ver si se recibió el mensaje

---

## � Importar Conversaciones Históricas

El sistema permite importar conversaciones previas exportadas de WhatsApp Business para preservar el historial antes de la integración.

### Exportar desde WhatsApp Business

1. Ve a [Meta Business Suite](https://business.facebook.com/)
2. Navega a WhatsApp Business Manager
3. Ve a la sección de configuración o datos
4. Solicita exportación de datos de conversaciones
5. Recibe el archivo JSON con el historial

### Formato del JSON de Exportación

El sistema espera un array de mensajes con este formato:

```json
[
  {
    "from": "51987654321",
    "text": {
      "body": "Hola, me interesa el servicio"
    },
    "timestamp": "2024-01-15T10:30:00Z",
    "type": "text",
    "direction": "inbound"
  },
  {
    "from": "51987654321",
    "text": {
      "body": "Gracias por la información"
    },
    "timestamp": "2024-01-15T10:35:00Z",
    "type": "text",
    "direction": "outbound"
  }
]
```

**Campos soportados:**
- `from` / `phone_number` / `from_phone`: Número de teléfono del cliente
- `text.body` / `content` / `message`: Contenido del mensaje
- `timestamp` / `created_at` / `date`: Fecha y hora del mensaje
- `type`: Tipo de mensaje (text, image, etc.)
- `direction`: `inbound` (cliente → empresa) o `outbound` (empresa → cliente)

### Pasos para Importar

1. **Loguéate como Super Admin**
   - URL: `http://127.0.0.1:8080/superadmin.html`
   - Credenciales: `superadmin` / `superadmin123`

2. **Ve a la sección "Campañas WhatsApp"**
   - En el sidebar lateral

3. **Haz clic en "📥 Importar Conversaciones"**
   - Se abrirá un modal de importación

4. **Selecciona la campaña**
   - Elige la campaña de WhatsApp asociada a las conversaciones
   - Ejemplo: "Claro - Campaña Promocional"

5. **Pega el JSON exportado**
   - Copia el contenido del archivo JSON exportado
   - Pégalo en el textarea del modal

6. **Haz clic en "Importar"**
   - El sistema procesará los mensajes
   - Creará conversaciones agrupadas por número de teléfono
   - Importará todos los mensajes

### Resultado de la Importación

El sistema mostrará:
- **Conversaciones creadas**: Número de nuevas conversaciones creadas
- **Mensajes importados**: Total de mensajes importados
- **Errores**: Si hubo mensajes que no se pudieron procesar

### Consideraciones Importantes

1. **Conversaciones duplicadas**
   - Si ya existe una conversación con el mismo número y campaña, los mensajes se agregan a la existente
   - No se crean conversaciones duplicadas

2. **Estado de conversaciones**
   - Las conversaciones importadas se marcan como "finalizada"
   - Pueden ser reactivadas si se recibe un nuevo mensaje

3. **Fechas de mensajes**
   - Se usan las fechas originales del JSON exportado
   - Si no hay fecha, se usa la fecha actual

4. **Formato flexible**
   - El sistema es tolerante con variaciones en el formato del JSON
   - Soporta múltiples formatos de exportación de Meta

### Ejemplo Completo

```json
[
  {
    "from": "51987654321",
    "text": {"body": "Hola, quiero contratar internet"},
    "timestamp": "2024-01-15T10:30:00Z",
    "type": "text",
    "direction": "inbound"
  },
  {
    "from": "51987654321",
    "text": {"body": "¿Cuáles son los planes disponibles?"},
    "timestamp": "2024-01-15T10:32:00Z",
    "type": "text",
    "direction": "inbound"
  },
  {
    "from": "51987654321",
    "text": {"body": "El plan de 100MB es ideal para usted"},
    "timestamp": "2024-01-15T10:35:00Z",
    "type": "text",
    "direction": "outbound"
  }
]
```

### Limitaciones

- Solo se pueden importar conversaciones en formato JSON
- El archivo debe ser un array de mensajes
- Los números deben estar en formato internacional (ej: 51987654321)
- Los archivos muy grandes pueden tardar más en procesar

---

## �📚 Recursos

- [WhatsApp Business API Documentation](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Meta for Developers](https://developers.facebook.com/)
- [WhatsApp Webhook Guide](https://developers.facebook.com/docs/whatsapp/cloud-api/webhooks)

---

---

## 📱 Sincronización Bidireccional de Mensajes

### Limitaciones de la API de WhatsApp Business

Es importante entender cómo funciona la sincronización de mensajes entre WhatsApp y el sistema.

### Flujo de Mensajes

#### Mensajes Entrantes (Cliente → Asesor)
**✅ Funciona completamente con webhook:**
1. Cliente envía mensaje desde WhatsApp (celular)
2. Meta envía el mensaje al webhook del sistema
3. Sistema recibe el mensaje
4. Conversación se crea/actualiza automáticamente
5. Asesor ve el mensaje en el panel en tiempo real

**Requisito:** Webhook debe estar configurado y accesible públicamente (HTTPS).

---

#### Mensajes Salientes (Asesor → Cliente)

**Desde el Panel del Asesor:**
- ✅ Sistema envía el mensaje por WhatsApp API
- ✅ Mensaje aparece en WhatsApp del cliente
- ✅ Mensaje se guarda en el historial del sistema
- ✅ Sistema mantiene registro completo

**Desde WhatsApp Web/Celular directamente:**
- ❌ Sistema NO recibe notificación automática
- ❌ El mensaje NO se guarda automáticamente en el sistema
- ❌ No hay sincronización bidireccional nativa
- ❌ Meta no envía webhook para mensajes salientes

**Por qué:** La API de WhatsApp Business está diseñada principalmente para enviar mensajes, no para notificar cuando se envían desde fuera del sistema.

---

## 🎯 Política de Uso Recomendada

### Para los Asesores

**Política oficial:**
1. **Las respuestas deben hacerse desde el panel del sistema**
   - Abre la conversación en el panel del asesor
   - Escribe y envía la respuesta desde el panel
   - Esto garantiza que el mensaje se guarde en el historial
   - Métricas de ventas y reportes funcionan correctamente

2. **WhatsApp Web/Celular es solo para:**
   - Consulta rápida del historial
   - Situaciones de emergencia cuando no hay acceso al panel
   - Verificación de contacto con el cliente

3. **Si un asesor responde desde WhatsApp Web:**
   - Debe copiar el mensaje al panel del sistema manualmente
   - Usar la función "Responder" en el panel para registrar la respuesta
   - Esto asegura que el sistema tenga el historial completo

4. **Para mantener métricas precisas:**
   - Todas las ventas deben registrarse desde el panel
   - Cambios de estado (venta, no desea, etc.) desde el panel
   - Esto asegura que el ranking de ventas sea correcto

---

## ⚠️ Soluciones Alternativas

### Opción 1: Solo respuestas desde el sistema (Recomendado)

**Implementación actual del sistema:**
- ✅ Los asesores responden desde el panel
- ✅ Sistema envía automáticamente por WhatsApp API
- ✅ Historial completo y métricas precisas

**Ventajas:**
- Sin costo adicional
- Historial completo
- Métricas de ventas funcionan
- Auditoría de actividad

**Desventajas:**
- Los asesores deben usar el panel (no es un problema para work-from-home)

---

### Nueva Funcionalidad: Registro de Mensajes Externos

**Si un asesor responde desde WhatsApp Web/Celular:**

El sistema ahora incluye una funcionalidad para registrar mensajes enviados fuera del sistema:

**En el panel del asesor:**
- ✅ Botón naranja "📱 Registrar mensaje externo" junto al botón de enviar
- ✅ Modal para ingresar el mensaje enviado desde WhatsApp Web/Celular
- ✅ El mensaje se guarda en el historial con indicador especial (📱)
- ✅ El mensaje se marca como "externo" en la base de datos
- ✅ El supervisor puede ver qué mensajes fueron enviados fuera del sistema

**En el historial de mensajes:**
- ✅ Los mensajes externos tienen borde naranja
- ✅ Icono 📱 junto al nombre del remitente
- ✅ Título "Enviado desde WhatsApp Web/Celular" al pasar el mouse

**En la base de datos:**
- ✅ Campo `origen_mensaje` en tabla `mensajes`
- ✅ Valores: `sistema` (enviado desde el panel) o `externo` (WhatsApp Web/Celular)

**Endpoint nuevo:**
- ✅ `POST /api/conversaciones/:id/mensaje-externo` - Registrar mensaje externo

**Beneficios:**
- ✅ Historial completo incluso si responden fuera del sistema
- ✅ Auditoría clara de qué mensajes fueron externos
- ✅ Supervisor puede identificar patrones de uso
- ✅ Métricas de ventas pueden seguir siendo precisas (si se registran)

**Limitaciones:**
- ⚠️ El mensaje NO se envía por WhatsApp API (ya fue enviado manualmente)
- ⚠️ Requiere acción manual del asesor para registrar
- ⚠️ Si el asesor no registra, el mensaje no aparece en el sistema

---

### Opción 2: WhatsApp Business Plus (Avanzado)

WhatsApp Business Plus tiene una API más completa que puede proporcionar:
- ✅ Notificaciones de mensajes enviados
- ✅ Sincronización bidireccional completa
- ✅ Integración nativa con CRMs
- ✅ Webhooks para todos los eventos

**Requiere:**
- Cuenta de WhatsApp Business Plus (costo mensual)
- Configuración adicional de la API
- Cambios en el código del sistema

**Decisión:** No implementado actualmente. Puede agregarse en el futuro si el cliente lo requiere.

---

### Opción 3: Polling de Conversaciones (Limitado)

El sistema podría:
- Consultar periódicamente la API de WhatsApp por mensajes nuevos
- Comparar con los mensajes en el sistema
- Importar los faltantes

**Problemas:**
- ❌ API de WhatsApp no proporciona endpoint para listar todos los mensajes
- ❌ Solo puede ver mensajes dentro de ventana de 24 horas
- ❌ No es una solución práctica o eficiente
- ❌ Alto costo de API calls

**Decisión:** No implementado.

---

## 📊 Tabla de Comportamiento

| Acción | ¿Se ve en el sistema? | ¿Se guarda en historial? | ¿Afecta métricas? |
|--------|---------------------|-----------------------|----------------|
| Cliente escribe al asesor | ✅ Sí (con webhook) | ✅ Sí | ✅ Sí |
| Asesor responde desde panel | ✅ Sí | ✅ Sí | ✅ Sí |
| Asesor responde desde WhatsApp Web (sin registrar) | ❌ No | ❌ No | ❌ No |
| Asesor responde desde WhatsApp Web (con registro) | ✅ Sí | ✅ Sí | ✅ Sí |
| Asesor registra mensaje externo | ✅ Sí | ✅ Sí | ✅ Sí |

**Notas:**
- Los mensajes registrados como externos se muestran con borde naranja y icono 📱
- El supervisor puede identificar qué mensajes fueron enviados fuera del sistema
- Para que las métricas sean precisas, los asesores deben registrar TODOS los mensajes externos

---

## 📝 Manual para Asesores

### Reglas de Uso

**1. Responder a Clientes:**
- **SIEMPRE** responder desde el panel del asesor
- Abre la conversación activa
- Escribe tu respuesta
- Haz clic en "Enviar"
- El sistema enviará automáticamente por WhatsApp

**2. Si usas WhatsApp Web/Celular:**
- Solo para consulta rápida
- Si respondes desde allí, **debes registrar el mensaje en el sistema**
- Haz clic en el botón naranja "📱 Registrar mensaje externo"
- Pega el mensaje que enviaste al cliente
- Haz clic en "Registrar Mensaje"
- Esto asegura que el sistema tenga el registro

**3. Diferencia entre mensajes:**
- **Mensaje desde el panel (azul):** El sistema lo envía automáticamente por WhatsApp
- **Mensaje externo (naranja con 📱):** Tú lo enviaste desde WhatsApp Web/Celular y lo registraste manualmente

**4. Para métricas de ventas:**
- Marca el estado de la conversación después de cada interacción
- Usa el selector de estado: "venta", "no desea", "volver a llamar", etc.
- Esto asegura que el ranking de ventas sea correcto

**5. Verificación:**
- Antes de cerrar el turno, verifica que todas las conversaciones tengan el estado correcto
- Revisa que las ventas estén marcadas como "venta"
- Si respondiste desde WhatsApp Web, asegúrate de haber registrado los mensajes
- Esto asegura que las métricas estén actualizadas

---

## 🔍 Auditoría y Supervisión

### Para Supervisores

**Para verificar que los asesores estén siguiendo la política:**

1. **Revisar el historial de conversaciones:**
   - Abre una conversación en el panel de supervisor
   - Verifica que haya continuidad en los mensajes
   - Si hay huecos, el asesor puede estar respondiendo fuera del sistema

2. **Verificar la consistencia:**
   - Compara la hora de los mensajes
   - Si hay patrones irregulares, investigar
   - Proporcionar retroalimentación al asesor

3. **Revisar métricas de ventas:**
   - Si las ventas son menores a las esperadas
   - Puede ser que los asesores no están registrando desde el panel
   - Reforzar la política de uso

---

## 💡 Implementación Futura

Si se requiere sincronización bidireccional completa en el futuro:

1. **Evaluar WhatsApp Business Plus:**
   - Probar si las notificaciones de mensajería resuelven el problema
   - Evaluar el costo vs beneficio

2. **Integración con CRM:**
   - Si el cliente usa un CRM (Zendesk, Salesforce, etc.)
   - Estos sistemas tienen integración bidireccional con WhatsApp
   - Considerar migrar el flujo a través del CRM

3. **Aplicación móvil para asesores:**
   - Crear una app móvil que se integre con el sistema
   - Permite responder desde el celular con sincronización automática
   - Requiere desarrollo adicional (React Native, Flutter, etc.)

---

## 📋 Checklist para Supervisores

- [ ] Capacitar a los asesores sobre la política de uso
- [ ] Verificar que las métricas de ventas son consistentes
- [ ] Revisar el historial de conversaciones periódicamente
- [ ] Proporcionar retroalimentación a asesores que no siguen la política
- [ ] Asegurar que todos los asesores tengan acceso al panel
- [ ] Monitorear si hay conversaciones sin respuesta reciente

---

## 🎯 Resumen Final

**Para que el sistema funcione completamente:**

1. ✅ **Webhook configurado** (con ngrok o servidor en nube)
2. ✅ **Asesores responden desde el panel** (no desde WhatsApp Web)
3. ✅ **Estados de conversación marcados correctamente**
4. ✅ **Política de uso comunicada y capacitada**

**El sistema actual está optimizado para este flujo.**

---

## 📺 Recursos

- [WhatsApp Business API Docs](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [WhatsApp Business Plus](https://business.facebook.com/wa/)

---

## ✅ Checklist de Implementación

- [x] Modelo de datos de campañas
- [x] Panel de gestión para superadmin
- [x] Endpoint webhook GET (verificación)
- [x] Endpoint webhook POST (recepción)
- [x] Servicio de envío de mensajes
- [x] Integración en respuestas de asesores
- [x] Validación de webhooks
- [x] Campo origen_mensaje en base de datos
- [x] Endpoint para registrar mensajes externos
- [x] Botón y modal en panel de asesor para mensajes externos
- [x] Indicador visual de mensajes externos en historial
- [x] Documentación de política de uso para asesores
- [ ] HTTPS en producción
- [ ] Plantillas de mensajes pre-aprobadas
- [ ] Sistema de reintentos
- [ ] Logs detallados de mensajes WhatsApp
