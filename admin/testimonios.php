<?php
// Este archivo proporciona la interfaz de administración para gestionar los testimonios de los usuarios.
// Permite a los administradores revisar, aprobar o eliminar testimonios antes de que sean visibles
// públicamente en el sitio web. Requiere autenticación de administrador para su acceso.
session_start();

// Si el usuario no está logueado, redirigir a la página de login
if (!isset($_SESSION['logueado']) || !$_SESSION['logueado'] || !isset($_SESSION['usuario']['tipo']) || $_SESSION['usuario']['tipo'] != 1) {
    header("Location: ../login.php"); // Redirige al login universal
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Testimonios - Admin</title>
    <!-- Enlace a la hoja de estilos principal del panel de administración -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Testimonios</h1>

        <p>Desde aquí puedes aprobar los testimonios pendientes para que sean visibles en la página pública o eliminar los que no sean apropiados.</p>

        <!-- La tabla se rellenará dinámicamente con JavaScript -->
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- El cuerpo de la tabla será poblado por testimonios.js -->
            <tbody id="tablaTestimonios">
                <!-- Ejemplo de estado de carga que será reemplazado -->
                <tr>
                    <td colspan="6">Cargando testimonios...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Inclusión de los archivos JavaScript necesarios -->
    <!-- funciones.js debe ir primero porque testimonios.js depende de él -->
    <script src="js/funciones.js"></script>
    <script src="js/testimonios.js"></script>
</body>
</html>