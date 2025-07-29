<?php
// Este script PHP se utiliza como un endpoint de API para verificar el estado de la sesión de un usuario.

// Comprueba si ya existe una sesión activa; si no, la inicia.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establece la cabecera de la respuesta HTTP para indicar que el contenido es de tipo JSON.
header('Content-Type: application/json');

// Comprueba si la variable de sesión 'logueado' está establecida y es verdadera.
if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    // Si el usuario está logueado, se asegura de que la información del usuario (específicamente el email) esté en la sesión.
    if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['email'])) {
        // Si todo es correcto, devuelve un objeto JSON indicando que el usuario está logueado y proporciona su email.
        echo json_encode([
            'logueado' => true,
            'email' => $_SESSION['usuario']['email']
        ]);
    } else {
        // Como medida de seguridad, si 'logueado' es true pero no hay datos de email, se considera como no logueado.
        echo json_encode(['logueado' => false]);
    }
} else {
    // Si la variable de sesión 'logueado' no está establecida o no es verdadera, devuelve un JSON indicando que el usuario no está logueado.
    echo json_encode(['logueado' => false]);
}
?>