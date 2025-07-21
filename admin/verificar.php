<?php
// Este archivo se encarga de la verificación de la cuenta de usuario.
// Recibe un token de verificación a través de la URL y lo utiliza para confirmar
// la cuenta del usuario en la base de datos. Muestra un mensaje de éxito o error
// y redirige al usuario a la página de inicio de sesión.
include_once '../data/usuarioDB.php';
require_once '../config/database.php';

$database = new Database();
$usuarioDB = new UsuarioDB($database);

//comprobar si se ha recibido un token
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $resultado = $usuarioDB->verificarToken($token);
    $mensaje = $resultado['mensaje'];
}else{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de cuenta</title>
    <link rel="stylesheet" href="cs/login.css">
</head>
<body>
    <div class="container">
        <h1>Verificación de cuenta</h1>
        <p class="mensaje"><?php echo $mensaje; ?></p>
        <a href="index.php" class="volver">Ir a Iniciar Sesión</a>
    </div>
</body>
</html>