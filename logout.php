<?php
session_start();

// Destruir todas las variables de sesión.
$_SESSION = array();

// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión.
session_destroy();

// Comprobar el origen y redirigir
if (isset($_GET['origen']) && $_GET['origen'] === 'index') {
    header("Location: index.html?mensaje=logout_success");
} else {
    header("Location: index.html");
}
exit();
?>