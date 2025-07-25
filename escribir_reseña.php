<?php
session_start();
require_once __DIR__ . '/controllers/testimonioController.php';

$testimonioController = new TestimonioController();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$resultado_envio = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado_envio = $testimonioController->procesarEnvioTestimonio();
}
$errores_envio = $resultado_envio['errores'] ?? [];
$mensaje_exito_envio = $resultado_envio['mensaje_exito'] ?? '';

$mensaje_val = $_POST['mensaje'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escribir Reseña - PowerGym</title>
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
            max-width: 800px; /* Ajustado para la página de formulario */
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Encabezado de la página */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .page-header h1 {
            font-size: 2.5em;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            margin: 0;
        }

        .header-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-volver, .home-btn {
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-volver {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-volver:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .home-btn {
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.4), rgba(75, 0, 130, 0.4));
            border-color: rgba(138, 43, 226, 0.5);
            position: relative;
            overflow: hidden;
        }
        .home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.4);
            border-color: rgba(138, 43, 226, 0.9);
        }
        .home-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s;
        }
        .home-btn:hover::before {
            left: 100%;
        }

        .btn-volver {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Estilos del formulario */
        .form-testimonio h2 {
            text-align: center;
            margin-top: 0;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
            margin-bottom: 30px;
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
            min-height: 150px;
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

        .logout-button-form {
            display: inline-block;
            float: right; /* Alinea el botón a la derecha */
            margin-top: 15px; /* Espacio respecto al formulario */
            background: linear-gradient(135deg, #8a2be2, #4b0082);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .logout-button-form:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
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
    <div class="page-container">
        <header class="page-header">
            <h1>Escribe tu Reseña</h1>
            <div class="header-buttons">
                <a href="index.html" class="home-btn">PowerGym</a>
                <a href="testimonios.php" class="btn-volver">← Volver a Reseñas</a>
            </div>
        </header>

        <div class="form-testimonio">
            <h2>¡Tu opinión nos importa mucho!</h2>

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

            <form action="escribir_reseña.php" method="POST">
                <div class="form-group">
                    <label for="mensaje">Tu Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" required><?php echo htmlspecialchars($mensaje_val); ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Enviar Reseña</button>
                </div>
            </form>
            <a href="logout.php" class="logout-button-form">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>