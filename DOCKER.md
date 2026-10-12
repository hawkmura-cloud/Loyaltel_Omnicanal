# Docker Deployment - Loyaltel Contact Center

## 📋 Requisitos

- Docker y Docker Compose instalados
- Git configurado

## 🚀 Despliegue Rápido

### 1. Clonar el repositorio

```bash
git clone https://github.com/hawkmura-cloud/claro-chatbot.git
cd claro-chatbot
```

### 2. Construir y ejecutar

```bash
docker-compose up -d
```

### 3. Verificar servicios

- Backend: http://localhost:3000
- Frontend: http://localhost:80

---

##  Estructura de Servicios

| Servicio | Puerto | Descripción |
|----------|--------|-------------|
| mysql | 3306 | Base de datos MySQL |
| backend | 3000 | API Node.js |
| frontend | 80 | PHP Apache |

---

## 🔧 Comandos Útiles

```bash
# Iniciar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Ver logs de un servicio específico
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql

# Detener servicios
docker-compose down

# Reconstruir contenedores
docker-compose up -d --build

# Ver estado de contenedores
docker-compose ps

# Entrar en un contenedor
docker-compose exec backend sh
docker-compose exec mysql bash
```

---

## 🗄️ Base de Datos

### MySQL en Docker

La aplicación usa MySQL 8.4 en un contenedor Docker:

- **Usuario:** claro_app
- **Contraseña:** claro_app_2026
- **Base de datos:** claro_chatbot
- **Root password:** claro_root_2026

### Ejecutar migraciones

Si necesitas ejecutar migraciones:

```bash
docker-compose exec backend node migrate_db.js
docker-compose exec backend node migrate_auth.js
docker-compose exec backend node migrate_omnicanal.js
docker-compose exec backend node migrate_superadmin.js
docker-compose exec backend node migrate_whatsapp.js
docker-compose exec backend node migrate_externos.js
```

### Acceder a MySQL directamente

```bash
docker-compose exec mysql mysql -u claro_app -pclaro_app_2026 claro_chatbot
```

---

## 🔐 Supabase (Futuro)

Las credenciales de Supabase están guardadas en `backend/.env.production` como comentarios para una futura migración a PostgreSQL. Actualmente, la aplicación usa MySQL en Docker.

---

## ⚠️ Notas Importantes

1. **Base de datos:** MySQL está en contenedor Docker, datos persisten en volumen
2. **Variables de entorno:** Configuradas en docker-compose.yml y .env.production
3. **SSL:** Para producción, necesitarás configurar SSL/HTTPS con un proxy reverso (nginx/caddy)
4. **Superadmin:** Usuario: `superadmin`, Contraseña: `superadmin123`

---

## 🐛 Troubleshooting

### Contenedor no inicia

```bash
# Ver logs del contenedor
docker-compose logs backend
docker-compose logs frontend
docker-compose logs mysql
```

### Base de datos no conecta

```bash
# Verificar que MySQL está saludable
docker-compose ps mysql

# Reiniciar MySQL
docker-compose restart mysql
```

### Reinstalar desde cero

```bash
# Detener y eliminar todo
docker-compose down -v

# Reconstruir y subir
docker-compose up -d --build
```
