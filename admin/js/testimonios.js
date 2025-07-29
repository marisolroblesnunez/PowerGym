/**
 * Este archivo JavaScript gestiona la interfaz y la lógica para la administración de testimonios
 * en el panel de control. Se encarga de cargar los testimonios existentes, mostrarlos en una tabla,
 * y manejar las acciones de aprobar y eliminar testimonios a través de peticiones asíncronas (AJAX)
 * al controlador de testimonios en el servidor.
 */

// Se asegura de que el script se ejecute solo después de que todo el contenido del DOM esté cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Punto de entrada principal para la gestión de testimonios.
    iniciarGestionTestimonios();
});

/**
 * Inicializa la carga de testimonios y configura los manejadores de eventos.
 */
function iniciarGestionTestimonios() {
    cargarTestimonios(); // Llama a la función para cargar y mostrar los testimonios.
    configurarManejadoresEventos(); // Llama a la función para configurar los listeners de eventos.
}

/**
 * Carga los testimonios desde el controlador y los muestra en la tabla.
 */
async function cargarTestimonios() {
    const tablaBody = document.getElementById('tablaTestimonios'); // Obtiene el cuerpo de la tabla de testimonios.
    // Muestra un estado de carga mientras se obtienen los datos.
    tablaBody.innerHTML = '<tr><td colspan="6">Cargando testimonios...</td></tr>';

    try {
        // Realiza una petición GET a la API para obtener la lista de todos los testimonios para el administrador.
        const respuesta = await fetch('../controllers/testimonioController.php?accion=listarAdmin');
        const testimonios = await respuesta.json();
        
        if (testimonios.length > 0) {
            // Si hay datos, se limpia la tabla y se rellena.
            tablaBody.innerHTML = ''; // Limpia el contenido actual de la tabla.
            testimonios.forEach(testimonio => {
                const fila = document.createElement('tr'); // Crea una nueva fila para cada testimonio.
                // Rellena la fila con los datos del testimonio, escapando el contenido para prevenir XSS.
                fila.innerHTML = `
                    <td>${testimonio.id}</td>
                    <td>${escapeHTML(testimonio.nombre_usuario)}</td>
                    <td>${escapeHTML(testimonio.mensaje)}</td>
                    <td>${testimonio.fecha}</td>
                    <td>
                        <span class="estado ${testimonio.estado}">${testimonio.estado}</span>
                    </td>
                    <td class="acciones">
                        ${testimonio.estado === 'pendiente' ? `<button class="btn-aprobar" data-id="${testimonio.id}">Aprobar</button>` : ''}
                        <button class="btn-eliminar" data-id="${testimonio.id}">Eliminar</button>
                    </td>
                `;
                tablaBody.appendChild(fila); // Añade la fila a la tabla.
            });
        } else {
            // Si no hay testimonios, se muestra un mensaje informativo.
            tablaBody.innerHTML = '<tr><td colspan="6">No hay testimonios para mostrar.</td></tr>';
        }
    } catch (error) {
        // En caso de error en la petición, se muestra en la tabla y en la consola.
        tablaBody.innerHTML = '<tr><td colspan="6">Error al cargar los testimonios.</td></tr>';
        console.error('Error al cargar testimonios:', error);
    }
}

/**
 * Configura un único manejador de eventos en la tabla para delegar los clics de los botones (aprobar/eliminar).
 */
function configurarManejadoresEventos() {
    const tablaBody = document.getElementById('tablaTestimonios');
    // Utiliza la delegación de eventos para manejar los clics en los botones de la tabla.
    tablaBody.addEventListener('click', (evento) => {
        const boton = evento.target; // El elemento que originó el evento.
        const id = boton.dataset.id; // El ID del testimonio, almacenado en un atributo data-id.

        if (id) { // Si el elemento tiene un data-id.
            if (boton.classList.contains('btn-aprobar')) {
                aprobarTestimonio(id); // Llama a la función para aprobar.
            } else if (boton.classList.contains('btn-eliminar')) {
                eliminarTestimonio(id); // Llama a la función para eliminar.
            }
        }
    });
}

/**
 * Cambia el estado de un testimonio a 'aprobado' mediante una petición POST.
 * @param {number|string} id - El ID del testimonio a aprobar.
 */
async function aprobarTestimonio(id) {
    const datos = new FormData(); // Crea un objeto FormData para enviar los datos.
    datos.append('id', id);
    datos.append('estado', 'aprobado');

    try {
        const respuesta = await fetch('../controllers/testimonioController.php?accion=cambiarEstado', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.success) {
            mostrarNotificacion('Testimonio aprobado correctamente.', 'success');
            cargarTestimonios(); // Recarga la tabla para reflejar el cambio de estado.
        } else {
            mostrarNotificacion(resultado.mensaje || 'Error al aprobar el testimonio.', 'error');
        }
    } catch (error) {
        mostrarNotificacion('Error de conexión al intentar aprobar.', 'error');
        console.error('Error en aprobarTestimonio:', error);
    }
}

/**
 * Elimina un testimonio, pidiendo confirmación al usuario primero.
 * @param {number|string} id - El ID del testimonio a eliminar.
 */
async function eliminarTestimonio(id) {
    // Pide confirmación al usuario antes de realizar una acción destructiva.
    if (!confirm('¿Estás seguro de que quieres eliminar este testimonio?')) {
        return; // Si el usuario cancela, no se hace nada.
    }

    const datos = new FormData();
    datos.append('id', id);

    try {
        const respuesta = await fetch('../controllers/testimonioController.php?accion=eliminar', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.success) {
            mostrarNotificacion('Testimonio eliminado correctamente.', 'success');
            cargarTestimonios(); // Recarga la tabla para quitar el testimonio eliminado.
        } else {
            mostrarNotificacion(resultado.mensaje || 'Error al eliminar el testimonio.', 'error');
        }
    } catch (error) {
        mostrarNotificacion('Error de conexión al intentar eliminar.', 'error');
        console.error('Error en eliminarTestimonio:', error);
    }
}

/**
 * Muestra una notificación flotante en la parte superior de la página.
 * @param {string} mensaje - El mensaje a mostrar.
 * @param {string} tipo - El tipo de notificación ('success' o 'error').
 */
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.textContent = mensaje;
    document.body.appendChild(notificacion);

    // Hace visible la notificación.
    setTimeout(() => {
        notificacion.classList.add('visible');
    }, 10);

    // Oculta y elimina la notificación después de 3 segundos.
    setTimeout(() => {
        notificacion.classList.remove('visible');
        notificacion.addEventListener('transitionend', () => notificacion.remove());
    }, 3000);
}

/**
 * Escapa caracteres HTML de una cadena para prevenir ataques de Cross-Site Scripting (XSS).
 * @param {string} str - La cadena de texto a escapar.
 * @returns {string} - La cadena con los caracteres HTML escapados.
 */
function escapeHTML(str) {
    if (str === null || str === undefined) {
        return ''; // Devuelve una cadena vacía si la entrada es nula o indefinida.
    }
    // Crea un elemento de párrafo temporal para convertir el texto en nodos de texto seguros.
    const p = document.createElement('p');
    p.appendChild(document.createTextNode(str));
    return p.innerHTML; // Devuelve el contenido HTML seguro.
}