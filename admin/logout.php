<?php
// Este archivo se encarga de cerrar la sesión de un usuario en el panel de administración.
// Elimina todas las variables de sesión y destruye la sesión actual, asegurando que el usuario
// ya no esté autenticado.

session_start();
if(isset($_SESSION['logueado'])){

//borrar todos los datos de la sesion
session_unset();
//destruye la sesion
session_destroy();
}
//redireccionar a la pagina de inicio