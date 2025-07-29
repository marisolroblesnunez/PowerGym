/**
 * Este archivo JavaScript maneja la funcionalidad de cerrar sesión en el panel de administración.
 * Escucha el evento de clic en el botón de "Cerrar Sesión", pide confirmación al usuario
 * y, si se confirma, realiza una petición al script `logout.php` para finalizar la sesión
 * y redirige al usuario a la página de inicio de sesión.
 */

// Añade un detector de eventos al elemento con el ID 'cerrarSesion'.
// Se activa cuando el usuario hace clic en dicho elemento.
document.getElementById('cerrarSesion').addEventListener('click', () => {
    
    // Muestra un cuadro de diálogo de confirmación al usuario.
    // La variable 'confirmado' será verdadera si el usuario hace clic en "Aceptar" y falsa si hace clic en "Cancelar".
    const confirmado = confirm("¿Seguro que quieres cerrar sesión?");

    // Comprueba si el usuario confirmó la acción.
    if(confirmado){
        // Realiza una petición asíncrona (fetch) al script 'logout.php' para destruir la sesión en el servidor.
        // Se utiliza el método POST, aunque en este caso no se envíen datos, es una buena práctica para acciones que modifican el estado.
        fetch('logout.php', {
            method: 'POST'
        })
        .then(() => {
            // Una vez que la petición de cierre de sesión se ha completado (independientemente de su resultado),
            // redirige al usuario a la página de inicio de sesión 'index.php'.
            window.location.href = 'index.php';
        })
        .catch(error => {
            // En caso de que haya un error con la petición fetch (ej. problema de red),
            // se muestra un mensaje en la consola y se redirige igualmente como medida de seguridad.
            console.error('Error al intentar cerrar la sesión:', error);
            window.location.href = 'index.php';
        });
    }
});