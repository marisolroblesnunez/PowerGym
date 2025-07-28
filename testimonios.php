<?php
session_start();
require_once __DIR__ . '/controllers/testimonioController.php';

$testimonioController = new TestimonioController();

// Procesar el envío del formulario si es un método POST
$resultado_envio = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado_envio = $testimonioController->procesarEnvioTestimonio();
}
$errores_envio = $resultado_envio['errores'] ?? [];
$mensaje_exito_envio = $resultado_envio['mensaje_exito'] ?? '';

// Obtener los testimonios visibles para mostrar
$testimonios_visibles = $testimonioController->obtenerTestimoniosParaWeb();

// Para mantener el valor en el formulario después de un error
$mensaje_val = $_POST['mensaje'] ?? '';
$is_logged_in = isset($_SESSION['usuario_id']);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonios - PowerGym</title>
    <link rel="stylesheet" href="cs/estilos.css">
    <style>
        /* === ESTILOS GENERALES === */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            color: #333;
            overflow-x: hidden; /* Prevenir scroll horizontal */
        }

        /* Fondo animado */
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #1a0e2e, #3b125f, #6a1b9a);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Contenedor principal */
        .page-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* === ESTILOS PARA LA SECCIÓN DE RESEÑAS === */
        .reseñas-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .reseñas-columna {
            flex: 2;
            min-width: 300px;
        }

        .formulario-columna {
            flex: 1;
            min-width: 300px;
        }

        .reseñas-layout h2 {
            font-size: 2em;
            color: #ffffff;
            border-bottom: 3px solid #8a2be2;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .testimonios-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .testimonio-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border-left: 5px solid #8a2be2;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .testimonio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .testimonio-card::before {
            content: '“';
            position: absolute;
            top: -10px;
            left: 15px;
            font-size: 5em;
            color: rgba(138, 43, 226, 0.1);
            z-index: 1;
        }

        .testimonio-card p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .testimonio-card .autor {
            display: block;
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-style: italic;
            color: #4b0082;
            position: relative;
            z-index: 2;
        }

        .form-testimonio {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease;
        }

        .form-testimonio:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Efecto de brillo (shine) */
        .form-testimonio::before {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 75%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: skewX(-25deg);
            transition: left 0.7s ease-in-out;
        }

        .form-testimonio:hover::before {
            left: 150%;
        }

        .form-testimonio h2 {
            text-align: center;
            margin-top: 0;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #eeeeee;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.2);
            color: #ffffff;
            border-radius: 8px;
            min-height: 120px;
            resize: vertical;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #8a2be2;
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.4);
        }

        .form-group button {
            width: 100%;
            background: linear-gradient(135deg, #8a2be2, #4b0082);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .form-group button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .form-testimonio .login-prompt p {
            text-align: center;
            font-size: 1.1em;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            color: #ffffff;
        }

        .form-testimonio .login-prompt a {
            color: #8a2be2;
            font-weight: bold;
            text-decoration: none;
        }

        .form-testimonio .login-prompt a:hover {
            text-decoration: underline;
        }

        .error-list, .success-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .error-list {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            list-style-type: none;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

    </style>
</head>
<body>
    <div class="background-animation"></div>
    <header class="header">
        <div class="logo">
            <a href="index.html" style="text-decoration: none; color: inherit;"><h1>PowerGym</h1></a>
            <p>Tu fuerza, nuestro compromiso</p>
        </div>
        <div class="header-buttons">
            <a href="login.php?action=login" id="login-button" class="login-btn">Iniciar Sesión</a>
            <div id="logged-in-section" style="display: none;">
                <a href="#" id="logout-btn" class="login-btn">Cerrar Sesión</a>
            </div>
            <span id="welcome-message" style="display: none;"></span>
        </div>
        <div id="page-message" class="hidden"></div>
    </header>
    <div class="page-container">
        <div class="reseñas-layout">
            <div class="reseñas-columna">
                <h2>Conocé lo que piensan otros usuarios sobre nuestras instalaciones, clases y atención.</h2>
                <?php if (empty($testimonios_visibles)): ?>
                    <p style="color: white;">Aún no hay reseñas publicadas. ¡Sé el primero en dejar una!</p>
                <?php else: ?>
                    <div class="testimonios-grid">
                        <?php foreach ($testimonios_visibles as $testimonio): ?>
                            <div class="testimonio-card">
                                <p>"<?php echo htmlspecialchars($testimonio['mensaje']); ?>"</p>
                                <div class="autor">- <?php echo htmlspecialchars($testimonio['nombre_usuario'] ?? 'Anónimo'); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formulario-columna">
                <div class="form-testimonio">
                    <h2>¿Ya entrenas con nosotros? ¡Cuentanos tu experiencia!</h2>

                    <?php if (!empty($errores_envio)): ?>
                        <ul class="error-list">
                            <?php foreach ($errores_envio as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($mensaje_exito_envio): ?>
                        <p class="success-message"><?php echo htmlspecialchars($mensaje_exito_envio); ?></p>
                    <?php endif; ?>

                    <?php if ($is_logged_in): ?>
                        <form action="testimonios.php" method="POST">
                            <div class="form-group">
                                <label for="mensaje">Tu Mensaje:</label>
                                <textarea id="mensaje" name="mensaje" required><?php echo htmlspecialchars($mensaje_val); ?></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit">Enviar Testimonio</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="login-prompt">
                            <a href="login.php?action=rese">Escribe aquí tu reseña</a><p>Necesitas registrarte para poder compartirla</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="js/funciones.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginButton = document.getElementById('login-button');
            const loggedInSection = document.getElementById('logged-in-section');
            const welcomeMessage = document.getElementById('welcome-message');
            const logoutButton = document.getElementById('logout-btn');

            // Ocultar ambos por defecto hasta que se determine el estado de la sesión
            loginButton.style.display = 'none';
            loggedInSection.style.display = 'none';

            fetch('api/verificar_sesion.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logueado) {
                        welcomeMessage.textContent = `¡Hola ${data.email}, esperamos que disfrutes tu experiencia!`;
                        loggedInSection.style.display = 'flex';
                        loginButton.style.display = 'none';
                        welcomeMessage.style.opacity = '1'; // Asegura que sea visible antes de desvanecerse

                        // Ocultar el mensaje de bienvenida después de 5 segundos con fade out
                        setTimeout(() => {
                            welcomeMessage.style.opacity = '0';
                            setTimeout(() => {
                                welcomeMessage.style.display = 'none';
                            }, 500); // Duración de la transición en CSS
                        }, 5000); // 5000 milisegundos = 5 segundos
                    } else {
                        loginButton.style.display = 'block';
                        loggedInSection.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error al verificar la sesión:', error);
                    // En caso de error, mostrar el botón de iniciar sesión por defecto
                    loginButton.style.display = 'block';
                    loggedInSection.style.display = 'none';
                });

            // Manejar el clic del botón de cerrar sesión
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = 'logout.php?origen=testimonios';
            });
        });
    </script>
</body>
</html>