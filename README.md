# PowerGym 💪

**PowerGym** es una aplicación web completa para la gestión integral de un gimnasio, que permite la administración de usuarios, testimonios, reservas de clases y más funcionalidades avanzadas.

## 🌟 Características Principales

### Para Usuarios
- 🔐 **Sistema de autenticación completo** (registro, login, recuperación de contraseña)
- 📧 **Verificación por email** con tokens seguros
- 🏃‍♀️ **Reservas de clases** en tiempo real
- 💬 **Sistema de testimonios** para compartir experiencias
- 📱 **Interfaz responsive** y moderna
- 🎨 **Animaciones y efectos visuales** con GSAP

### Para Administradores(Plan de futuro)
- 👥 **Gestión de usuarios** y permisos
- 📝 **Administración de testimonios** (aprobar/rechazar)
- 🗓️ **Gestión de clases** y horarios
- 👨‍🏫 **Administración de entrenadores**
- 📊 **Panel de control centralizado**

### Características Técnicas
- 🔒 **Seguridad avanzada** con prepared statements
- 📧 **Sistema de correos** (PHPMailer para producción, simulado para desarrollo)
- 🌐 **API REST** para funcionalidades AJAX
- 🎭 **Detección automática de entorno** (local/producción)
- 🛡️ **Protección contra inyección SQL** y XSS

## 🏗️ Estructura del Proyecto

```
PowerGym/
├── 📁 api/                     # API REST endpoints
│   ├── index.php              # Router principal de la API
│   └── verificar_sesion.php   # Verificación de sesión
├── 📁 config/                 # Configuración
│   ├── .htaccess             # Protección del directorio
│   ├── config.php            # Configuración general
│   └── database.php          # Conexión a la base de datos
├── 📁 controllers/           # Lógica de negocio
│   ├── claseController.php   # Gestión de clases
│   ├── inscripcionController.php # Gestión de inscripciones
│   ├── testimonioController.php # Gestión de testimonios
│   └── usuarioController.php # Gestión de usuarios
├── 📁 data/                  # Capa de acceso a datos
│   ├── claseDB.php          # Operaciones de clases
│   ├── entrenadorDB.php     # Operaciones de entrenadores
│   ├── inscripcionclaseDB.php # Operaciones de inscripciones
│   ├── testimonioDB.php     # Operaciones de testimonios
│   ├── usuarioDB.php        # Operaciones de usuarios
│   ├── enviarCorreos.php    # Sistema de correos
│   └── PHPMailer/           # Librería PHPMailer
├── 📁 admin/                # Panel de administración
├── 📁 public/               # Recursos estáticos
│   ├── css/                 # Hojas de estilo
│   ├── js/                  # Scripts JavaScript
│   └── img/                 # Imágenes
├── 📁 views/                # Vistas HTML/PHP
├── 📁 documentacion/        # Documentación del proyecto
├── login.php                # Página de login universal
├── logout.php               # Cerrar sesión
├── reservas.php             # Reserva de clases
├── testimonios.php          # Página de testimonios
└── index.html               # Página principal
```

## 🚀 Instalación y Configuración

### Requisitos del Sistema
- **PHP 7.4** o superior
- **MySQL/MariaDB** 5.7+
- **Apache Server** (recomendado WAMP/XAMPP)
- **Extensiones PHP**: mysqli, openssl, mbstring

### Instalación Paso a Paso

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/PowerGym.git
   cd PowerGym
   ```

2. **Configurar el servidor local**
   - Instalar [WAMP](https://www.wampserver.com/) o [XAMPP](https://www.apachefriends.org/)
   - Copiar el proyecto a la carpeta `www` (WAMP) o `htdocs` (XAMPP)

3. **Configurar la base de datos**
   ```sql
   CREATE DATABASE gimnasio;
   -- Ejecutar el script SQL de la base de datos (ubicado en /documentacion)
   ```

4. **Configurar credenciales**
   - Abrir `config/config.php`
   - Las credenciales locales ya están configuradas para WAMP por defecto
   - Para producción, modificar las constantes de la base de datos

5. **Acceder a la aplicación**
   ```
   http://localhost/PowerGym
   ```

## ⚙️ Configuración de Entornos

### Desarrollo Local
```php
// Configuración automática para localhost
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gimnasio');
define('USAR_EMAIL_REAL', false); // Correos simulados
```

### Producción
```php
// Configuración para servidor en la nube
define('DB_HOST', 'tu-host-produccion');
define('DB_USER', 'tu-usuario-bd');
define('DB_PASS', 'tu-password-bd');
define('DB_NAME', 'tu-base-datos');
define('USAR_EMAIL_REAL', true); // Correos reales via SMTP
```

## 🔧 Funcionalidades Principales

### Sistema de Usuarios
- **Registro** con verificación por email
- **Login seguro** con hash de passwords
- **Recuperación de contraseña** via token
- **Roles** (Usuario/Administrador)

### Gestión de Clases
- **Visualización** de clases disponibles
- **Reservas en tiempo real** con control de cupos
- **Información detallada** (entrenador, horario, descripción)
- **Estado de inscripción** por usuario

### API REST
```javascript
// Endpoints disponibles
GET  /api/?resource=clases        # Obtener clases
GET  /api/?resource=entrenadores  # Obtener entrenadores
POST /api/?resource=reservar      # Reservar clase
GET  /api/?resource=check_session # Verificar sesión
```

### Sistema de Testimonios
- **Envío** de testimonios por usuarios registrados
- **Moderación** por administradores
- **Visualización pública** de testimonios aprobados

## 🛡️ Seguridad Implementada

- ✅ **Prepared Statements** para prevenir inyección SQL
- ✅ **Hash de contraseñas** con `password_hash()`
- ✅ **Tokens seguros** para verificación y recuperación
- ✅ **Sanitización** de datos de entrada
- ✅ **Protección de directorios** sensibles
- ✅ **Validación** de sesiones y permisos

## 🎨 Tecnologías Utilizadas

### Backend
- **PHP** - Lógica del servidor
- **MySQL** - Base de datos
- **PHPMailer** - Envío de correos

### Frontend
- **HTML5/CSS3** - Estructura y estilos
- **JavaScript ES6+** - Interactividad
- **GSAP** - Animaciones avanzadas
- **Fetch API** - Comunicación AJAX

### Herramientas
- **Apache** - Servidor web
- **Git** - Control de versiones

## 📚 Uso de la Aplicación

### Para Usuarios
1. **Registrarse** en la plataforma
2. **Verificar email** haciendo clic en el enlace recibido
3. **Iniciar sesión** con credenciales
4. **Explorar clases** disponibles
5. **Reservar cupos** en las clases deseadas
6. **Dejar testimonios** sobre la experiencia

### Para Administradores
1. **Acceder** al panel de administración
2. **Gestionar usuarios** y permisos
3. **Administrar clases** y horarios
4. **Aprobar/rechazar testimonios**
5. **Gestionar entrenadores**

## 🐛 Depuración

### Logs de Correos (Desarrollo)
Los correos simulados se guardan en:
```
data/correos_simulados.log
```

### Logs de Errores PHP
```
error_log('Mensaje de debug', 0);
```

### Modo Debug de PHPMailer
```php
define('DEBUG_MAIL', true); // En config.php
```

## 🤝 Contribuir

1. Fork del repositorio
2. Crear rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## 📄 Licencia

Este proyecto es de uso **interno y educativo**. Para uso comercial, consultar con el desarrollador.

## 📞 Soporte

Para soporte técnico o consultas:
- 📧 Email: info@powergym.com
- 📱 Teléfono: +34 XXX XXX XXX
- 🌐 Web: www.powergym.com

## 🚧 Roadmap

### Próximas Funcionalidades
- [ ] **App móvil** nativa
- [ ] **Sistema de pagos** integrado
- [ ] **Métricas y analytics** avanzados
- [ ] **Notificaciones push**
- [ ] **Integración con dispositivos** fitness
- [ ] **Sistema de gamificación**

---

**PowerGym** - *Tu fuerza, nuestro compromiso* 💪