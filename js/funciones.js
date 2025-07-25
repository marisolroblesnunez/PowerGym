document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout-btn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();

            fetch('api/verificar_sesion.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logueado) {
                        window.location.href = 'logout.php?origen=index';
                    } else {
                        alert('¡Para cerrar sesion antes tienes que iniciar sesion!');
                    }
                })
                .catch(error => {
                    console.error('Error al verificar la sesión:', error);
                    alert('Ocurrió un error al intentar cerrar la sesión. Por favor, inténtalo de nuevo.');
                });
        });
    }

    // Mostrar mensaje de logout si está en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('mensaje') && urlParams.get('mensaje') === 'logout_success') {
        alert('Nos vemos luego, tu sesión se ha cerrado correctamente.');
        // Limpiar la URL para que el mensaje no se muestre de nuevo si se recarga la página
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
