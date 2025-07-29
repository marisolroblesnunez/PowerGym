/**
 * Este archivo JavaScript se encarga de la validación del lado del cliente para el formulario de restablecimiento de contraseña.
 * Su función principal es asegurar que las contraseñas ingresadas en los campos 'Nueva Contraseña'
 * y 'Confirmar Nueva Contraseña' coincidan antes de permitir que el formulario se envíe al servidor.
 */

// Añade un detector de eventos al formulario con el ID "formRestablecer".
// La función se ejecutará cuando el usuario intente enviar el formulario (evento "submit").
document.getElementById("formRestablecer").addEventListener("submit", function(e) {
    // Obtiene el valor del campo de la nueva contraseña.
    const nueva = document.getElementById("nueva_password").value;
    // Obtiene el valor del campo de confirmación de la contraseña.
    const confirmar = document.getElementById("confirmar_password").value;
    // Obtiene el elemento del párrafo donde se mostrarán los mensajes de error.
    const mensaje = document.getElementById("mensaje_cliente");

    // Compara los valores de los dos campos de contraseña.
    if (nueva !== confirmar) {
        // Si las contraseñas no coinciden, previene el envío del formulario al servidor.
        e.preventDefault(); 
        
        // Establece el texto del mensaje de error.
        mensaje.textContent = "Las contraseñas no coinciden.";
        // Muestra el elemento del mensaje de error, que por defecto está oculto.
        mensaje.style.display = 'block';
    } else {
        // Si las contraseñas coinciden, se asegura de que el mensaje de error esté oculto.
        mensaje.style.display = 'none';
    }
});