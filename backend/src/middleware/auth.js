const jwt = require('jsonwebtoken');

const JWT_SECRET = process.env.JWT_SECRET || (() => {
    console.warn('⚠️ JWT_SECRET no configurado en .env, usando valor por defecto (NO SEGURO)');
    return 'claro_chatbot_secret_key_2026_CHANGE_IN_PRODUCTION';
})();

const JWT_EXPIRES_IN = process.env.JWT_EXPIRES_IN || '8h';

// ======================================================
// VERIFICAR TOKEN JWT
// ======================================================

function authenticateToken(req, res, next) {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Bearer TOKEN

    if (!token) {
        return res.status(401).json({
            ok: false,
            error: 'No se proporcionó token de autenticación'
        });
    }

    jwt.verify(token, JWT_SECRET, (err, user) => {
        if (err) {
            return res.status(403).json({
                ok: false,
                error: 'Token inválido o expirado'
            });
        }

        req.user = user;
        next();
    });
}

// ======================================================
// VERIFICAR ROL DE SUPERVISOR
// ======================================================

function requireSupervisor(req, res, next) {
    if (req.user.rol !== 'supervisor' && req.user.rol !== 'super_admin') {
        return res.status(403).json({
            ok: false,
            error: 'Se requiere rol de supervisor'
        });
    }
    next();
}

// ======================================================
// VERIFICAR ROL DE SUPER ADMINISTRADOR
// ======================================================

function requireSuperAdmin(req, res, next) {
    if (req.user.rol !== 'super_admin') {
        return res.status(403).json({
            ok: false,
            error: 'Se requiere rol de super administrador'
        });
    }
    next();
}

// ======================================================
// VERIFICAR QUE EL USUARIO ES EL ASESOR ESPECIFICADO
// ======================================================

function requireAsesorOrSelf(req, res, next) {
    const asesorId = Number(req.params.id);

    // Si es supervisor o super_admin, tiene acceso
    if (req.user.rol === 'supervisor' || req.user.rol === 'super_admin') {
        return next();
    }

    // Si es asesor, solo puede acceder a sus propios datos
    if (req.user.rol === 'asesor') {
        if (req.user.asesorId !== asesorId) {
            return res.status(403).json({
                ok: false,
                error: 'No tienes permiso para acceder a estos datos'
            });
        }
        return next();
    }

    return res.status(403).json({
        ok: false,
        error: 'Rol no autorizado'
    });
}

module.exports = {
    authenticateToken,
    requireSupervisor,
    requireSuperAdmin,
    requireAsesorOrSelf
};
