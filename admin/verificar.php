<?php
// Este archivo maneja la lógica para verificar la cuenta de un nuevo usuario a través de un token.

// Incluye los archivos necesarios para la conexión a la base de datos y las operaciones de usuario.
include_once '../data/usuarioDB.php'; // Contiene la clase UsuarioDB para interactuar con la tabla de usuarios.
require_once '../config/database.php'; // Contiene la clase Database para la conexión.

// Crea instancias de las clases de base de datos.
$database = new Database(); // Crea el objeto de conexión.
$usuarioDB = new UsuarioDB($database); // Crea el objeto de acceso a datos de usuario.

// Comprueba si se ha proporcionado un token en la URL (a través del método GET).
if(isset($_GET['token'])){
    $token = $_GET['token']; // Obtiene el token de la URL.
    $resultado = $usuarioDB->verificarToken($token); // Llama al método para verificar el token en la base de datos.
    $mensaje = $resultado['mensaje']; // Almacena el mensaje de resultado (éxito o error) para mostrarlo al usuario.
}else{
    // Si no se proporciona ningún token, redirige al usuario a la página de inicio de sesión del administrador.
    header("Location: index.php");
    exit(); // Termina la ejecución del script.
}
?>

<!DOCTYPE html> <!-- Define el tipo de documento como HTML5. -->
<html lang="es"> <!-- El idioma del documento es español. -->
<head>
    <meta charset="UTF-8"> <!-- Especifica la codificación de caracteres. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Asegura la correcta visualización en dispositivos móviles. -->
    <title>Verificación de cuenta</title> <!-- Título de la página. -->
    <link rel="stylesheet" href="../cs/estilos.css"> <!-- Enlaza la hoja de estilos. -->
</head>
<body class="verification-page-body"> <!-- Cuerpo de la página con una clase específica para estilos. -->
    <div class="container"> <!-- Contenedor principal para centrar el contenido. -->
        <h1>Verificación de cuenta</h1> <!-- Título principal de la página. -->
        <!-- Muestra el mensaje de resultado (éxito o error) obtenido del proceso de verificación. -->
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <!-- Enlace para que el usuario regrese a la página de inicio de sesión. -->
        <a href="index.php" class="volver">Ir a Iniciar Sesión</a>
    </div>
</body>
</html>