/*
* Este archivo se encarga de la interactividad del widget del chatbot.
* Su principal función es mostrar u ocultar el contenedor del chatbot
* cuando el usuario hace clic en la burbuja flotante.
*/
document.addEventListener('DOMContentLoaded', () => {
    const chatbotBubble = document.getElementById('chatbot-bubble');
    const chatbotContainer = document.getElementById('chatbot-widget-container');

    if (chatbotBubble && chatbotContainer) {
        chatbotBubble.addEventListener('click', () => {
            chatbotContainer.classList.toggle('hidden');
            // Opcional: añadir una clase para animar la aparición/desaparición
            if (!chatbotContainer.classList.contains('hidden')) {
                chatbotContainer.style.display = 'flex';
            } else {
                chatbotContainer.style.display = 'none';
            }
        });
    }
});
