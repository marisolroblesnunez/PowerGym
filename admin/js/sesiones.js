/**
 * Este archivo JavaScript maneja la funcionalidad de cerrar sesión en el panel de administración.
 * Escucha el evento de clic en el botón de "Cerrar Sesión", pide confirmación al usuario
 * y, si se confirma, realiza una petición al script `logout.php` para finalizar la sesión
 * y redirige al usuario a la página de inicio de sesión.
 */
document.getElementById('cerrarSesion').addEventListener('click', () =>{   ////que cuando haaga click en el booton de cerrarsesion me haga:
    const confirmado = confirm("¿Seguro que quieres cerrar sesion?"); /////confirm es un alert que te deja poner si o no
    if(confirmado){
        fetch('logout.php', {
            method: 'POST'
        })
        .then(() => {
            window.location.href = 'login.php'
        })
    }
    
})