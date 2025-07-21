<?php
// Este archivo PHP es la página de inicio principal del sitio web del gimnasio, pero la e renombrado como "pruebadeindex.php para que cuando la suba al servidor por si acaso index.html me da problemas, puedo utilizar esta, cambiandole el nombre a index.php y poniendole los estilos que necesite.
// Actúa como un punto de entrada que carga datos dinámicos, como clases y testimonios,
// desde los controladores PHP correspondientes (`ClaseController` y `TestimonioController`).
// Combina la lógica de backend para la obtención de datos con la presentación HTML,
// mostrando información actualizada a los visitantes.
require_once __DIR__ . '/controllers/claseController.php';
require_once __DIR__ . '/controllers/testimonioController.php';

$claseController = new ClaseController();
$datosClases = $claseController->mostrarClases();
$clases = $datosClases['clases'];

$testimonioController = new TestimonioController();
$testimonios = $testimonioController->obtenerTestimoniosParaWeb();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerGym - Tu Gimnasio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="background-animation"></div>
    
    <header class="header">
        <div class="logo">
            <h1>PowerGym</h1>
            <p>Tu fuerza, nuestro compromiso</p>
        </div>
    </header>

    <main class="main-container">
        <nav class="main-menu">
            <button class="menu-item" id="mi-fitness">
                <div class="menu-icon">💪</div>
                <span>Mi Fitness</span>
            </button>
            <button class="menu-item" id="mi-actividad">
                <div class="menu-icon">🏃</div>
                <span>Mi Actividad</span>
            </button>
            <button class="menu-item" id="mas">
                <div class="menu-icon">⚙️</div>
                <span>Más</span>
            </button>
        </nav>

        <section class="content-area">
            <div class="welcome-message">
                <h2>Bienvenido a PowerGym</h2>
                <p>Selecciona una opción del menú para comenzar</p>
            </div>

            <!-- Mi Fitness Content -->
            <div class="submenu hidden" id="fitness-submenu">
                <h3>Mi Fitness</h3>
                <div class="circle-menu">
                    <div class="circle-item" data-option="clases">
                        <div class="circle-icon">🏋️</div>
                        <span>Clases</span>
                    </div>
                    <div class="circle-item" data-option="entrenadores">
                        <div class="circle-icon">👨‍🏫</div>
                        <span>Entrenadores</span>
                    </div>
                    <div class="circle-item" data-option="dieta">
                        <div class="circle-icon">🥗</div>
                        <span>Dieta</span>
                    </div>
                </div>
            </div>

            <!-- Mi Actividad Content -->
            <div class="submenu hidden" id="actividad-submenu">
                <h3>Mi Actividad</h3>
                <div class="circle-menu">
                    <div class="circle-item single-circle" data-option="reservas">
                        <div class="circle-icon">📅</div>
                        <span>Reserva tus Clases</span>
                    </div>
                </div>
            </div>

            <!-- Más Content -->
            <div class="submenu hidden" id="mas-submenu">
                <h3>Más Opciones</h3>
                <div class="circle-menu">
                    <div class="circle-item" data-option="reseñas">
                        <div class="circle-icon">⭐</div>
                        <span>Reseñas</span>
                    </div>
                    <div class="circle-item" data-option="mensaje">
                        <div class="circle-icon">✉️</div>
                        <span>Deja tu Mensaje</span>
                    </div>
                    <div class="circle-item" data-option="notificaciones">
                        <div class="circle-icon">🔔</div>
                        <span>Notificaciones</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Content Area -->
            <div id="dynamic-content" class="hidden">
                <!-- Clases will be loaded here -->
                <div id="clases-content" class="dynamic-section hidden">
                    <h3>Nuestras Clases</h3>
                    <div class="clases-grid">
                        <?php foreach ($clases as $clase): ?>
                            <div class="clase-card">
                                <h4><?php echo htmlspecialchars($clase['nombre_clase']); ?></h4>
                                <p><?php echo htmlspecialchars($clase['descripcion']); ?></p>
                                <p><strong>Entrenador:</strong> <?php echo htmlspecialchars($clase['nombre_entrenador']); ?></p>
                                <p><strong>Día:</strong> <?php echo htmlspecialchars($clase['dia_semana']); ?></p>
                                <p><strong>Hora:</strong> <?php echo htmlspecialchars($clase['hora_inicio']); ?> - <?php echo htmlspecialchars($clase['hora_fin']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Testimonios will be loaded here -->
                <div id="reseñas-content" class="dynamic-section hidden">
                    <h3>Testimonios de nuestros miembros</h3>
                    <div class="testimonios-grid">
                        <?php foreach ($testimonios as $testimonio): ?>
                            <div class="testimonio-card">
                                <p>"<?php echo htmlspecialchars($testimonio['mensaje']); ?>"</p>
                                <span>- <?php echo htmlspecialchars($testimonio['nombre_usuario']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <button class="back-button hidden" id="back-button">
            <span>← Volver</span>
        </button>
    </main>

    <footer class="footer">
        <p>&copy; 2024 PowerGym. Todos los derechos reservados.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
