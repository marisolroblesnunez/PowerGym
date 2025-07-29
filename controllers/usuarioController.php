<?php
// Este archivo actúa como un controlador para todas las acciones relacionadas con el usuario, como registro, inicio de sesión y recuperación de contraseña.

// Inicia la sesión si no hay una activa para poder usar variables de sesión.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir las clases necesarias
require_once '../config/database.php'; // Incluye la configuración de la base de datos.
require_once '../data/usuarioDB.php'; // Incluye la clase de acceso a datos de usuario.

$database = new Database(); // Crea una nueva instancia de la base de datos.
$usuariobd = new UsuarioDB($database); // Crea una instancia de UsuarioDB, pasándole la conexión.

// Función para redirigir al usuario con un mensaje de éxito o error.
function redirigirConMensaje($url, $success, $mensaje){
    
    //almacena el resultado en la sesion
    $_SESSION['success'] = $success; // Almacena el estado de éxito (true/false) en la sesión.
    $_SESSION['mensaje'] = $mensaje; // Almacena el mensaje a mostrar en la sesión.

    //realiza la redirección
    header("location: $url"); // Redirige al usuario a la URL especificada.
    exit(); // Termina la ejecución del script para asegurar que la redirección se complete.
}

// Maneja el registro de un nuevo usuario.
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['registro'])){
    $email = $_POST['email']; // Obtiene el email del formulario.
    $password = $_POST['password']; // Obtiene la contraseña del formulario.

    $resultado = $usuariobd->registrarUsuario($email, $password); // Llama al método para registrar al usuario.

    // Redirige al login con el mensaje de resultado.
    redirigirConMensaje('../login.php', $resultado['success'], $resultado['mensaje']);
}

// Maneja el inicio de sesión.
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
    $email = $_POST['email']; // Obtiene el email del formulario.
    $password = $_POST['password']; // Obtiene la contraseña del formulario.
    
    $resultado = $usuariobd->verificarCredenciales($email, $password); // Verifica las credenciales del usuario.
    $_SESSION['logueado'] = $resultado['success']; // Establece el estado de 'logueado' en la sesión.

    if($resultado['success']){ // Si el inicio de sesión fue exitoso.
        $_SESSION['usuario'] = $resultado['usuario']; // Guarda toda la información del usuario en la sesión.
        $_SESSION['user_id'] = $resultado['usuario']['id']; // Guarda específicamente el ID del usuario en la sesión.
        $_SESSION['usuario']['tipo'] = $resultado['usuario']['tipo']; // Guarda el tipo de usuario (rol) en la sesión.
        
        // Redirigir según el tipo de usuario.
        if ($_SESSION['usuario']['tipo'] == 1) { // Si es administrador.
            $ruta = '../admin/testimonios.php'; // La ruta de redirección es al panel de administrador.
        } else { // Si es usuario normal (tipo 0).
            if (isset($_POST['action']) && $_POST['action'] == 'rese') { // Si la acción es escribir una reseña.
                $ruta = '../escribir_reseña.php'; // Redirige a la página de escribir reseña.
            } else if (isset($_POST['action']) && $_POST['action'] == 'clases') { // Si la acción es ver clases.
                $ruta = '../reservas.php'; // Redirige a la página de reservas.
            } else { // Para cualquier otro caso.
                $ruta = '../index.html'; // Redirige a la página de inicio.
            }
        }
    }else{ // Si el inicio de sesión falló.
        $ruta = '../login.php'; // La ruta de redirección es de vuelta al login.
    }
    // Redirige a la ruta determinada con el mensaje de resultado.
    redirigirConMensaje($ruta, $resultado['success'], $resultado['mensaje']);
}

// Maneja la recuperación de contraseña.
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['recuperar'])){
    $email = $_POST['email']; // Obtiene el email del formulario.

    $resultado = $usuariobd->recuperarPassword($email); // Llama al método para iniciar la recuperación.
    // Redirige al login con el mensaje de resultado.
    redirigirConMensaje('../login.php', $resultado['success'], $resultado['mensaje']);
}