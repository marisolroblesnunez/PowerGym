<?php
// Este archivo gestiona el proceso de restablecimiento de contraseña de un usuario.

// Incluye los archivos necesarios para la base de datos y la lógica de usuario.
include_once '../data/usuarioDB.php';
require_once '../config/database.php';

// Inicializa la conexión a la base de datos y la clase de acceso a datos de usuario.
$database = new Database();
$usuariobd = new UsuarioDB($database);

// Verifica si se ha proporcionado un token de restablecimiento en la URL.
if(isset($_GET['token'])){
    $token = $_GET['token']; // Almacena el token.

    // Comprueba si el formulario ha sido enviado (método POST) y si se han proporcionado las contraseñas.
    if($_SERVER['REQUEST_METHOD'] == "POST" 
    && isset($_POST['nueva_password'])
    && isset($_POST['confirmar_password'])){
        // Llama al método para restablecer la contraseña con el token y la nueva contraseña.
        $resultado = $usuariobd->restablecerPassword($token, $_POST['nueva_password']);
        // Guarda el mensaje de resultado para mostrarlo en la página.
        $mensaje = $resultado['mensaje'];
    }
}else{
    // Si no hay token en la URL, redirige al usuario a la página de inicio de sesión.
    header("Location: login.php");
    exit(); // Termina la ejecución del script.
}
?>

<!DOCTYPE html> <!-- Define el tipo de documento como HTML5. -->
<html lang="es"> <!-- El idioma del documento es español. -->
<head>
    <meta charset="UTF-8"> <!-- Especifica la codificación de caracteres. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Asegura la correcta visualización en dispositivos móviles. -->
    <title>Restablecer Contraseña</title> <!-- Título de la página. -->
    <link rel="stylesheet" href="../cs/estilos.css"> <!-- Enlaza la hoja de estilos. -->
</head>
<body class="login-page-body"> <!-- Cuerpo de la página con una clase específica para estilos. -->
    <div class="container"> <!-- Contenedor principal para centrar el contenido. -->
    <h1>Restablecer Contraseña</h1> <!-- Título principal. -->
    <?php
        // Si la variable $mensaje no está vacía, significa que el formulario se ha procesado.
        if(!empty($mensaje)): ?>
        <!-- Muestra el mensaje de resultado (éxito o error). -->
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php 
        // Si el restablecimiento fue exitoso, muestra un enlace para ir a iniciar sesión.
        if(isset($resultado['success']) && $resultado['success']): ?>
            <a href="../login.php" class="volver">Ir a Iniciar Sesión</a>
        <?php endif; 
        // Si la variable $mensaje está vacía, muestra el formulario de restablecimiento.
        else: 
        ?>
        <!-- Formulario para que el usuario ingrese su nueva contraseña. -->
        <form method="POST" id="formRestablecer">
            <input type="password" name="nueva_password" id="nueva_password" required placeholder="Nueva Contraseña">
            <input type="password" name="confirmar_password" id="confirmar_password" required placeholder="Confirmar Nueva Contraseña">
            <input type="submit" value="Restablecer Contraseña">
            <!-- Párrafo para mostrar mensajes de error del lado del cliente (JavaScript). -->
            <p class="error" id="mensaje_cliente" style="display: none;"></p>
        </form>
        <?php endif; // Fin del bloque condicional. ?>
  </div>
 <!-- Incluye el script de JavaScript para la validación del formulario en el lado del cliente. -->
 <script src="js/restablecer.js"></script>
</body>
</html>