<?php
// Este archivo PHP es la página principal para la gestión de reservas de clases por parte de los usuarios.
// Requiere que el usuario esté autenticado para acceder. Muestra las clases disponibles
// y permite a los usuarios reservar su cupo. La información de las clases se obtiene
// a través del `ClaseController` y la interacción con las reservas se maneja
// principalmente con JavaScript (`js/reservas.js`).

// Iniciar la sesión para acceder a las variables de sesión
session_start();

// 1. PROTEGER LA PÁGINA
// Si el usuario no está logueado, redirigirlo a la página de login.
if (!isset($_SESSION['logueado']) || !$_SESSION['logueado']) {
    header('Location: login.php');
    exit(); // Detener la ejecución del script
}

// 2. OBTENER DATOS DINÁMICOS
// Incluir el controlador de clases para obtener la lista de clases.
require_once __DIR__ . '/controllers/claseController.php';

// Crear una instancia del controlador y obtener los datos de las clases.
$claseController = new ClaseController();
$datos = $claseController->mostrarClases();
$clases = $datos['clases']; // Array con todas las clases

// Obtener el email del usuario de la sesión para el mensaje de bienvenida.
$email_usuario = $_SESSION['usuario']['email'] ?? 'Usuario';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Clases - PowerGym</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        /* Estilos específicos para la página de reservas con la nueva paleta */
        body.reservas-page {
            background-image: url('img/fondoGim.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }
        .reservas-container {
            max-width: 90%;
            margin: 2rem auto;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.7); /* Fondo más oscuro */
            border-radius: 15px;
            text-align: center;
            border: 1px solid #6a0dad; /* Borde morado */
        }
        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .welcome-header h1 {
            font-size: 2.5rem;
            color: #9370DB; /* Tono de morado medio */
        }
        .header-buttons {
            display: flex;
            gap: 1rem;
        }
        .logout-btn, .home-btn {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .logout-btn {
            background-color: #8A2BE2; /* Morado */
        }
        .logout-btn:hover {
            background-color: #6a0dad; /* Morado más oscuro */
        }
        .home-btn {
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.4), rgba(75, 0, 130, 0.4));
            border: 1px solid rgba(138, 43, 226, 0.5);
            position: relative;
            overflow: hidden;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
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
        .clases-carrusel {
            display: flex;
            flex-wrap: wrap; /* Permite que las tarjetas se ajusten a la siguiente línea */
            justify-content: center; /* Centra las tarjetas */
            gap: 20px; /* Mantiene el espacio entre tarjetas */
        }
        .clase-card-reserva {
            flex: 1 1 220px; /* Base de 220px, pueden crecer y encogerse */
            max-width: 250px; /* Ancho máximo para evitar que se estiren demasiado */
            background-color: rgba(255, 255, 255, 0.05); /* Casi transparente */
            border: 1px solid #8A2BE2;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: left;
        }
        .clase-card-reserva h3 {
            margin-top: 0;
            color: #9370DB; /* Morado medio */
        }
        .clase-card-reserva p {
            margin: 0.5rem 0;
        }
        .reservar-btn {
            width: 100%;
            padding: 10px;
            margin-top: 1rem;
            background-color: #6a0dad; /* Morado oscuro */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .reservar-btn:hover {
            background-color: #4B0082; /* Morado índigo */
        }
        .reservar-btn.lleno {
            background-color: #444;
            border-color: #555;
            color: #aaa;
            cursor: not-allowed;
        }
        #confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Permite hacer clic a través del contenedor */
            overflow: hidden;
            z-index: 9999; /* Asegura que esté por encima de todo */
        }
        .confetti-particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #fff; /* Color por defecto, se cambiará con JS */
            border-radius: 50%; /* Forma circular por defecto, se puede cambiar */
        }
    </style>
</head>
<body class="reservas-page">

    <div class="reservas-container">
        <div class="welcome-header">
            <h1>¡Bienvenido, Gracias por confiar en nosotros! <br>Cada entrenamiento cuenta. ¡Sigue así! <?php echo htmlspecialchars($email_usuario); ?>!</h1>
            <div class="header-buttons">
                <a href="index.html" class="home-btn">PowerGym</a>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </div>
        
        <h2>No pierdas la oportunidad. ¡Apúntate YA!</h2>

        <div id="clases-container" class="clases-carrusel">
            <!-- Las clases se cargarán aquí dinámicamente con JavaScript -->
        </div>
        <div id="confetti-container"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="js/reservas.js"></script>
</body>
</html>