document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout-btn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();

            fetch('api/verificar_sesion.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logueado) {
                        window.location.href = 'logout.php';
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
});