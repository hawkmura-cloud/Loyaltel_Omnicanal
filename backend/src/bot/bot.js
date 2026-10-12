const estados = {
    INICIO: "inicio",
    MENU: "menu",
    INTERNET: "internet",
    INTERNET_TV: "internet_tv",
    PROMOCIONES: "promociones",
    CONTRATAR: "contratar",
    ASESOR: "asesor",
    DISTRITO: "distrito",
    DIRECCION: "direccion",
    NOMBRE: "nombre",
    TELEFONO: "telefono",
    FINALIZADO: "finalizado"
};

function procesarMensaje(mensaje, estado = estados.INICIO) {
    const texto = mensaje.trim().toLowerCase();

    // Inicio
    if (
        estado === estados.INICIO ||
        texto === "hola" ||
        texto === "buenas" ||
        texto === "buenos dias" ||
        texto === "buenas tardes"
    ) {
        return {
            estado: estados.MENU,
            respuesta: `👋 ¡Hola! Bienvenido(a) a Claro.

🏠 Estamos para ayudarte con nuestros servicios para el hogar.

¿Qué servicio estás buscando?

1️⃣ Internet Hogar
2️⃣ Internet + TV
3️⃣ Consultar promociones
4️⃣ Quiero contratar
5️⃣ Hablar con un asesor`
        };
    }

    // Menú principal
    if (estado === estados.MENU) {

        switch (texto) {

            case "1":
            case "internet":
                return {
                    estado: estados.DISTRITO,
                    respuesta: `📡 ¡Excelente!

Para ayudarte con Internet Hogar necesitamos verificar la disponibilidad en tu zona.

📍 ¿En qué distrito te encuentras?`
                };

            case "2":
            case "internet + tv":
            case "internet tv":
                return {
                    estado: estados.DISTRITO,
                    respuesta: `📺📡 ¡Excelente!

Podemos ayudarte con Internet + TV.

📍 ¿En qué distrito te encuentras?`
                };

            case "3":
            case "promociones":
            case "promo":
                return {
                    estado: estados.DISTRITO,
                    respuesta: `🎁 ¡Claro!

Las promociones pueden variar según el servicio y la ubicación.

📍 ¿En qué distrito te encuentras?`
                };

            case "4":
            case "contratar":
                return {
                    estado: estados.DISTRITO,
                    respuesta: `🎉 ¡Perfecto!

Estamos listos para ayudarte con tu contratación.

📍 Primero, ¿en qué distrito te encuentras?`
                };

            case "5":
            case "asesor":
                return {
                    estado: estados.ASESOR,
                    respuesta: `👨‍💼 Claro.

Vamos a registrar tu solicitud para que un asesor comercial pueda continuar contigo.

👤 ¿Cuál es tu nombre?`
                };

            default:
                return {
                    estado: estados.MENU,
                    respuesta: `No pude identificar tu opción. 😊

Por favor responde con:

1️⃣ Internet Hogar
2️⃣ Internet + TV
3️⃣ Promociones
4️⃣ Quiero contratar
5️⃣ Hablar con un asesor`
                };
        }
    }

    // Distrito
    if (estado === estados.DISTRITO) {
        return {
            estado: estados.DIRECCION,
            respuesta: `Perfecto 👍

📍 Distrito registrado.

Ahora indícanos tu dirección completa.

Ejemplo:
Av. Universitaria 1234, Los Olivos`
        };
    }

    // Dirección
    if (estado === estados.DIRECCION) {
        return {
            estado: estados.NOMBRE,
            respuesta: `Gracias. 😊

👤 ¿Cuál es tu nombre completo?`
        };
    }

    // Nombre
    if (estado === estados.NOMBRE) {
        return {
            estado: estados.TELEFONO,
            respuesta: `Mucho gusto, ${mensaje.trim()}.

📱 Para finalizar, indícanos tu número de celular.`
        };
    }

    // Teléfono
    if (estado === estados.TELEFONO) {
        return {
            estado: estados.FINALIZADO,
            respuesta: `✅ ¡Listo!

Hemos registrado tus datos.

Un asesor comercial continuará contigo para brindarte información sobre los servicios disponibles para tu hogar. 📲

¡Gracias por comunicarte con Claro! ❤️`
        };
    }

    // Asesor
    if (estado === estados.ASESOR) {
        return {
            estado: estados.TELEFONO,
            respuesta: `Perfecto, ${mensaje.trim()}.

📱 Ahora indícanos tu número de celular para que un asesor pueda comunicarse contigo.`
        };
    }

    return {
        estado: estados.MENU,
        respuesta: `👋 ¿En qué podemos ayudarte?

1️⃣ Internet Hogar
2️⃣ Internet + TV
3️⃣ Promociones
4️⃣ Quiero contratar
5️⃣ Hablar con un asesor`
    };
}

module.exports = {
    estados,
    procesarMensaje
};