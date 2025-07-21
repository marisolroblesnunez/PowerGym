
/**
 * Este archivo JavaScript se encarga de la validación del formulario de restablecimiento de contraseña.
 * Su función principal es asegurar que las contraseñas ingresadas en los campos 'Nueva Contraseña'
 * y 'Confirmar Nueva Contraseña' coincidan antes de que el formulario sea enviado al servidor.
 * Muestra un mensaje de error si las contraseñas no coinciden.
 */

document.getElementById("formRestablecer").addEventListener("submit", function(e) {
    const nueva = document.getElementById("nueva_password").value;
    const confirmar = document.getElementById("confirmar_password").value;
    const mensaje = document.getElementById("mensaje_cliente");

    if (nueva !== confirmar) {
        e.preventDefault(); // Evita que se envíe el formulario
        mensaje.textContent = "Las contraseñas no coinciden.";
        mensaje.style.display = 'block';
   
}

});
