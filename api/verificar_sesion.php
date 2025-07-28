<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
    // Asegurarse de que el email del usuario está en la sesión
    if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['email'])) {
        echo json_encode([
            'logueado' => true,
            'email' => $_SESSION['usuario']['email']
        ]);
    } else {
        // Caso de seguridad: logueado pero sin email en la sesión
        echo json_encode(['logueado' => false]);
    }
} else {
    echo json_encode(['logueado' => false]);
}
?>