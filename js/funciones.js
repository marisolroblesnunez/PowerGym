/*
* Este archivo contiene funciones JavaScript de propósito general utilizadas en varias partes del sitio.
* - showMessage: Muestra notificaciones emergentes (éxito, error) en la página.
* - Manejo del Logout: Verifica si el usuario tiene una sesión activa antes de redirigir al script de logout,
* previniendo errores y mostrando un mensaje si no hay sesión iniciada.
* - Mensaje de Logout Exitoso: Muestra un mensaje de confirmación cuando el usuario es redirigido
* a la página principal después de cerrar sesión.
*/
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout-btn');
    const messageContainer = document.getElementById('page-message');

    // Función para mostrar mensajes
    function showMessage(message, type) {
        if (messageContainer) {
            messageContainer.textContent = message;
            messageContainer.className = 'page-message '; // Limpiar clases previas
            messageContainer.classList.add(type, 'show');

            // Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                messageContainer.classList.remove('show');
            }, 3000);
        }
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();

            fetch('api/verificar_sesion.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logueado) {
                        window.location.href = 'logout.php?origen=index';
                    } else {
                        showMessage('¡Para cerrar sesión antes tienes que iniciar sesión!', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error al verificar la sesión:', error);
                    showMessage('Ocurrió un error al intentar cerrar la sesión. Por favor, inténtalo de nuevo.', 'error');
                });
        });
    }

    // Mostrar mensaje de logout si está en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('mensaje') && urlParams.get('mensaje') === 'logout_success') {
        showMessage('Nos vemos luego, tu sesión se ha cerrado correctamente.', 'success');
        // Limpiar la URL para que el mensaje no se muestre de nuevo si se recarga la página
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});