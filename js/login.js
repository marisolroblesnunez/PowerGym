/*
* Este archivo gestiona la interactividad de las ventanas modales en la página de login.
* Se encarga de:
* - Abrir la modal de registro de nuevos usuarios.
* - Abrir la modal de recuperación de contraseña.
* - Cerrar las modales al hacer clic en el botón de cierre (X) o al hacer clic fuera del contenido de la modal.
*/
//crear referencias a las modales
const modalRegistro = document.getElementById('modalRegistro');
const modalRecuperar = document.getElementById('modalRecuperar');

//referencias a los enlaces que abren las modales
const btnRecuperar = document.querySelector('.abrir-modal-recuperar');
const btnRegistro = document.querySelector('.abrir-modal-registro');

//referencias al span que cierra la modal
const spanRegistro = document.querySelector('.cerrarRegistro');
const spanRecuperar = document.querySelector('.cerrarRecuperar');

//abrir la modal registro
btnRegistro.addEventListener('click', () => {
    modalRegistro.style.display = 'flex';
})

//cerrar la modal registro
spanRegistro.onclick = function(){
    modalRegistro.style.display = 'none';
}

btnRecuperar.addEventListener('click', () => {
    modalRecuperar.style.display = 'flex';

})

spanRecuperar.addEventListener('click', () => {
    modalRecuperar.style.display = 'none';
})

//cerrar modal cuando el usuario hace click fuera de la modal
window.onclick = function(event){
    if(event.target == modalRegistro){
        modalRegistro.style.display = 'none';
    }
    if(event.target == modalRecuperar){
        modalRecuperar.style.display = 'none';
    }
}