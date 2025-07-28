<?php
// Iniciar la sesión para acceder a las variables de sesión
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones - PowerGym</title>
    <link rel="stylesheet" href="cs/estilos.css">
    <style>
        .promociones-page {
            background-image: url('img/fondoGim.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }
        .promociones-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro semi-transparente */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(138, 43, 226, 0.3); /* Borde púrpura */
            text-align: center;
            color: #ffffff;
        }
        .promociones-container h2 {
            font-size: 3em;
            color: #9370db; /* Color púrpura */
            margin-bottom: 20px;
            text-shadow: 0 0 15px rgba(147, 112, 219, 0.7);
        }
        .promociones-container p {
            font-size: 1.2em;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .promo-card {
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.2), rgba(75, 0, 130, 0.2));
            border: 2px solid rgba(138, 43, 226, 0.5);
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }
        .promo-card::before {
            content: '✨';
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 2em;
            opacity: 0.6;
        }
        .promo-card::after {
            content: '🔥';
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 2em;
            opacity: 0.6;
        }
        .promo-card h3 {
            font-size: 2.5em;
            color: #8a2be2; /* Púrpura más oscuro */
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(138, 43, 226, 0.5);
        }
        .promo-card ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .promo-card ul li {
            font-size: 1.3em;
            margin-bottom: 10px;
            color: #e0e0e0;
        }
        .promo-card ul li strong {
            color: #ffffff;
        }
        .call-to-action {
            margin-top: 30px;
            font-size: 1.5em;
            font-weight: bold;
            color: #ba55d3; /* Púrpura claro */
        }
        .back-button-container {
            margin-top: 40px;
        }
        .back-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(45deg, #8a2be2, #9370db);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: bold;
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
<body class="promociones-page">

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

    <div class="promociones-container">
        <h2>¡Ofertas Exclusivas para Ti!</h2>
        <p>En PowerGym, siempre buscamos la manera de motivarte a alcanzar tus metas. ¡Aprovecha nuestras promociones especiales!</p>

        <div class="promo-card">
            <h3>☀️ ¡Este verano, ponte fuerte!</h3>
            <ul>
                <li><strong>3 meses por solo 89€</strong></li>
                <li>Acceso completo a todas las instalaciones.</li>
                <li>Clases ilimitadas (Spinning, Yoga, Zumba, BodyPump y más).</li>
                <li>Asesoramiento inicial con nuestros entrenadores.</li>
            </ul>
            <p class="call-to-action">¡No dejes pasar esta oportunidad! Válido hasta el 31 de agosto.</p>
        </div>

        <div class="promo-card">
            <h3>💪 Plan Amigo PowerGym</h3>
            <ul>
                <li>Trae a un amigo y ambos obtienen un <strong>15% de descuento</strong> en su próxima mensualidad.</li>
                <li>¡Entrenar juntos es más divertido y efectivo!</li>
            </ul>
            <p class="call-to-action">¡Comparte la experiencia PowerGym!</p>
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
                window.location.href = 'logout.php?origen=promociones';
            });
        });
    </script>
</body>
</html>