# PowerGym

PowerGym es una aplicación web para la gestión de un gimnasio, permitiendo la administración de usuarios, testimonios, reservas y más.

## Características principales

- Registro y gestión de usuarios
- Envío y administración de testimonios de clientes
- Panel de administración para gestionar visibilidad y eliminación de testimonios
- Sistema de reservas y control de asistencia
- Interfaz web amigable y segura

## Estructura del proyecto

- `/controllers` — Controladores de la lógica de negocio (ej. `testimonioController.php`)
- `/data` — Acceso y gestión de datos en la base de datos
- `/config` — Configuración de la base de datos y parámetros globales
- `/documentacion` — Documentación técnica y de usuario
- `/public` — Archivos públicos y recursos estáticos (CSS, JS, imágenes)
- `/views` — Vistas y plantillas HTML/PHP

## Instalación

1. Clona el repositorio en tu servidor local.
2. Configura la base de datos en `/config/database.php`.
3. Asegúrate de tener [WAMP](https://www.wampserver.com/) instalado y funcionando.
4. Coloca el proyecto en la carpeta `www` de WAMP.
5. Accede a la aplicación desde tu navegador en `http://localhost/PowerGym`.

## Uso

- Los usuarios pueden registrarse, iniciar sesión y dejar testimonios.
- Los administradores pueden gestionar los testimonios y usuarios desde el panel de administración.

## Requisitos

- PHP 7.4 o superior
- MySQL/MariaDB
- Servidor Apache (recomendado WAMP)

## Documentación

Consulta la carpeta `/documentacion` para manuales de usuario y documentación técnica.

## Licencia

Este proyecto es de uso interno y educativo. Para uso comercial, consulta con