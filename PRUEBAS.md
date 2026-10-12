# Pruebas Automatizadas - Claro Chatbot

## 📋 Resumen de Pruebas

Batería de pruebas automatizadas implementada con **Jest** y **Supertest**.

### Resultado: ✅ **11/11 PASADAS**

## 🧪 Pruebas Implementadas

| # | Prueba | Resultado | Descripción |
|---|--------|-----------|-------------|
| 1 | Cliente solicita un asesor | ✅ PASS | Verifica que se asigne o ingrese a la cola correctamente |
| 2 | No hay asesores disponibles | ✅ PASS | Confirma que se conserva el orden de espera |
| 3 | Se libera un asesor | ✅ PASS | Comprueba que recibe la siguiente conversación de la cola |
| 4 | Concurrencia - dos asesores mismo caso | ✅ PASS | Verifica que solo una asignación sea válida (protección contra carrera) |
| 5 | Asesor accede a conversación ajena | ✅ PASS | Confirma que devuelve HTTP 403 |
| 6 | JWT vencido | ✅ PASS | Verifica que el acceso es rechazado |
| 7 | Mensajes repetidos no duplican | ✅ PASS | Confirma que no se crean asignaciones duplicadas |
| 8 | Persistencia tras reinicio | ✅ PASS | Verifica que los datos se conservan en MySQL |
| 9 | Login correcto | ✅ PASS | Confirma que devuelve token JWT válido |
| 10 | Login incorrecto | ✅ PASS | Verifica que devuelve error 401 |
| 11 | Endpoint sin token | ✅ PASS | Confirma que devuelve error 401 |

---

## 🚀 Ejecutar Pruebas

```bash
cd backend
npm test
```

### Comandos adicionales:

```bash
# Ejecutar en modo watch (se reejecutan al cambiar código)
npm run test:watch

# Ejecutar con reporte de cobertura
npm run test:coverage
```

---

## 📁 Estructura de Archivos

```
backend/
├── __tests__/
│   ├── setup.js           # Configuración de base de datos de pruebas
│   └── integration.test.js  # Pruebas de integración
├── .env.test               # Variables de entorno para pruebas
├── .env.example            # Plantilla de variables de entorno
└── package.json             # Scripts de test configurados
```

---

## 🔒 Notas Importantes

### Base de Datos de Pruebas
- Las pruebas usan la base de datos `claro_chatbot` (la misma que producción)
- **IMPORTANTES**: Las pruebas limpian todos los datos antes y después de ejecutar
- No se recomienda ejecutar pruebas en un entorno de producción con datos reales

### Datos de Prueba
Las pruebas crean automáticamente:
- 1 supervisor (usuario: `supervisor`, pass: `sup123`)
- 2 asesores (usuario: `asesor1`/`asesor2`, pass: `asesor1123`/`asesor2123`)
- 1 cliente de prueba

Todos estos datos se eliminan después de cada prueba.

---

## 📊 Cobertura

Los siguientes aspectos están cubiertos por las pruebas:

- ✅ **Asignación de asesores**
- ✅ **Gestión de cola de espera**
- ✅ **Concurrencia y condiciones de carrera**
- ✅ **Autorización y permisos por rol**
- ✅ **Validación de JWT (expiración, firma)**
- ✅ **Integridad de datos (no duplicación)**
- ✅ **Persistencia de datos en MySQL**
- ✅ **Autenticación (login/logout)**
- ✅ **Protección de endpoints sin autenticación**

---

## ⚠️ Limitaciones

### Prueba de SSE
La prueba de recuperación de conexión SSE no se incluye porque Supertest no maneja bien conexiones de larga duración (EventSource). Esta funcionalidad se prueba manualmente en el frontend.

### Entorno de Pruebas
Actualmente las pruebas usan la misma base de datos que producción. Para un entorno de producción real, se recomienda:
- Crear una base de datos separada para pruebas
- Usar contenedores Docker para aislar el entorno de pruebas
- Implementar CI/CD con pipelines de pruebas automatizadas

---

## 🎯 Próximos Pasos Recomendados

1. **Crear base de datos de pruebas separada**
2. **Implementar pruebas unitarias para servicios individuales**
3. **Agregar pruebas E2E con Cypress o Playwright**
4. **Integrar pruebas en pipeline CI/CD (GitHub Actions, GitLab CI)**
5. **Agregar reportes de cobertura de código**

---

## 📝 Comandos Útiles

```bash
# Verificar qué pruebas fallan con salida detallada
npm test --verbose

# Ejecutar solo una prueba específica
npm test -t "JWT vencido"

# Ver cobertura de código
npm run test:coverage
```

---

**Fecha de creación:** 2026-10-09
**Framework:** Jest + Supertest
**Estado:** ✅ Todas las pruebas pasando
