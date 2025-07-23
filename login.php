<?php
// Este archivo es el punto de entrada universal para el inicio de sesión de usuarios y administradores.
// Gestiona la autenticación, el registro de nuevas cuentas (solo para usuarios normales) y la recuperación de contraseña.
// Redirige a los usuarios a sus respectivas áreas (reservas para usuarios, panel de administración para administradores)
// basándose en su rol (`tipo`) almacenado en la base de datos.

if(session_status() == PHP_SESSION_NONE){
    session_start();
}
$url = '';//creo que seria index
if(!isset($_GET['action'])){
    //ir al index
    header('index.html');
}else{
    if($_GET['action'] == 'rese'){
        $url = "Location: escribir_reseña.php";
    }
    if($_GET['action'] == 'clases'){
        $url = "Location: reservas.php";
    }
}
// Si el usuario ya está logueado, redirigir a la página correcta según su tipo
if(isset($_SESSION['logueado']) && $_SESSION['logueado'] == true){
    if (isset($_SESSION['usuario']['tipo'])) {
        if ($_SESSION['usuario']['tipo'] == 1) { // Si es administrador
            header("Location: admin/index.php"); // Redirige al index del admin
        } else { // Si es usuario normal (tipo 0)
            header($url); // Redirige a la página de reservas del usuario
        }
    } else {
        // Si por alguna razón no se pudo determinar el tipo, redirigir a la página de usuario por defecto
        header($url);
    }
    exit();
}

// El resto de la lógica PHP para el login, registro y recuperación se maneja en controllers/usuarioController.php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PowerGym</title>
    <link rel="stylesheet" href="cs/estilos.css"> <!-- Estilos generales -->
    
<body class="login-page-body">
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form method="post" action="controllers/usuarioController.php">
            <input type="hidden" name="action" value="<?php echo isset($_GET['action']) ? htmlspecialchars($_GET['action']) : ''; ?>">
            <input type="email" name="email" required placeholder="Correo electrónico">
            <input type="password" name="password" required placeholder="Contraseña">
            <input type="submit" name="login" value="Iniciar Sesión">
        </form>
        
        <div class="olvido-password">
            <a class="abrir-modal-recuperar">Recuperar contraseña</a>
        </div>
        <div class="crear-cuenta">
            <a class="abrir-modal-registro">Crear cuenta nueva</a>
        </div>
        
        <?php
        if(isset($_SESSION['mensaje'])){
            echo "<div class='error'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <!-- Modales para recuperación y registro -->
        <div id="modalRecuperar" class="modal">
            <div class="modal-contenido">
                <span class="cerrarRecuperar">&times;</span>
                <h2>Recuperar contraseña</h2>
                <form method="POST" action="controllers/usuarioController.php">
                    <input type="email" name="email" required placeholder="Correo electrónico">
                    <input type="submit" name="recuperar" value="Recuperar Contraseña">
                </form>
            </div>
        </div>

        <div id="modalRegistro" class="modal">
            <div class="modal-contenido">
                <span class="cerrarRegistro">&times;</span>
                <h2>Registro Cuenta nueva</h2>
                <form method="POST" action="controllers/usuarioController.php">
                    <input type="email" name="email" required placeholder="Correo electrónico">
                    <input type="password" name="password" required placeholder="Contraseña">
                    <input type="submit" name="registro" value="Registrarse">
                </form>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script> <!-- Script JS para la interactividad de los modales -->
</body>
</html>