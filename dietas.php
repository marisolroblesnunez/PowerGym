<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietas Saludables - PowerGym</title>
    <link rel="stylesheet" href="cs/estilos.css">
    <style>
        .dietas-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7); /* Fondo oscuro semi-transparente */
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(138, 43, 226, 0.3); /* Borde púrpura */
            text-align: center;
            color: #ffffff;
        }
        .dietas-container h2 {
            color: #9370db; /* Color púrpura */
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 0 10px rgba(147, 112, 219, 0.5);
        }
        .dieta-dia {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid rgba(138, 43, 226, 0.4); /* Borde púrpura */
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05); /* Fondo casi transparente */
            text-align: left;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .dieta-dia h3 {
            color: #8a2be2; /* Púrpura más oscuro */
            margin-bottom: 15px;
            font-size: 1.8em;
            border-bottom: 2px solid #8a2be2;
            padding-bottom: 10px;
        }
        .dieta-dia ul {
            list-style: none;
            padding: 0;
        }
        .dieta-dia ul li {
            margin-bottom: 10px;
            font-size: 1.1em;
            line-height: 1.6;
            color: #cccccc;
        }
        .dieta-dia ul li strong {
            color: #ffffff;
        }
        .back-button-container {
            margin-top: 30px;
        }
        .back-button {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(45deg, #8a2be2, #9370db);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1em;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .back-button:hover {
            background: linear-gradient(45deg, #9370db, #8a2be2);
            box-shadow: 0 8px 25px rgba(147, 112, 219, 0.4);
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="reservas-page">

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

    <div class="dietas-container">
        <h2>Dietas Saludables para la Semana</h2>
        
        <div class="dieta-dia">
            <h3>Lunes</h3>
            <ul>
                <li><strong>Desayuno:</strong> Avena con frutas del bosque y un puñado de nueces.</li>
                <li><strong>Almuerzo:</strong> Ensalada de quinoa con vegetales asados (brócoli, pimientos, calabacín) y pollo a la parrilla.</li>
                <li><strong>Cena:</strong> Salmón al horno con espárragos y batata.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Martes</h3>
            <ul>
                <li><strong>Desayuno:</strong> Tostadas integrales con aguacate y huevo pochado.</li>
                <li><strong>Almuerzo:</strong> Sopa de lentejas con verduras y una rebanada de pan integral.</li>
                <li><strong>Cena:</strong> Tacos de lechuga con carne molida magra, frijoles negros y salsa fresca.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Miércoles</h3>
            <ul>
                <li><strong>Desayuno:</strong> Batido de proteínas con espinacas, plátano y leche de almendras.</li>
                <li><strong>Almuerzo:</strong> Arroz integral con tofu salteado y brócoli.</li>
                <li><strong>Cena:</strong> Pechuga de pavo a la plancha con ensalada mixta y aderezo ligero.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Jueves</h3>
            <ul>
                <li><strong>Desayuno:</strong> Yogur griego con granola casera y frutas frescas.</li>
                <li><strong>Almuerzo:</strong> Paella de verduras y pollo (con arroz integral).</li>
                <li><strong>Cena:</strong> Ensalada de tomate y cebolla.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Viernes</h3>
            <ul>
                <li><strong>Desayuno:</strong> Tortilla de claras con champiñones y espinacas.</li>
                <li><strong>Almuerzo:</strong> Ensalada de atún (en agua) con aguacate, tomate y lechuga.</li>
                <li><strong>Cena:</strong> Pizza casera con base de coliflor, salsa de tomate natural, vegetales y queso bajo en grasa.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Sábado</h3>
            <ul>
                <li><strong>Desayuno:</strong> Tostadas de pan integral con tomate rallado, jamón serrano (magro) y un chorrito de aceite de oliva virgen extra.</li>
                <li><strong>Almuerzo:</strong> Gazpacho andaluz y sardinas a la plancha con pimientos asados.</li>
                <li><strong>Cena:</strong> Brochetas de pollo y vegetales a la parrilla con cuscús integral.</li>
            </ul>
        </div>

        <div class="dieta-dia">
            <h3>Domingo</h3>
            <ul>
                <li><strong>Desayuno:</strong> Porridge de avena con leche (o bebida vegetal), canela y una pieza de fruta de temporada.</li>
                <li><strong>Almuerzo:</strong> Pollo asado con patatas al vapor y ensalada verde.</li>
                <li><strong>Cena:</strong> Sopa de verduras casera con un trozo de pan integral.</li>
            </ul>
        </div>

        <div class="back-button-container">
            <a href="index.html" class="back-button">Volver al Inicio</a>
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
                window.location.href = 'logout.php?origen=dietas';
            });
        });
    </script>
</body>
</html>