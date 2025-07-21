// Helper function to remove accents from a string
        function removeAccents(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }

        // Configuración del chatbot
        const chatbot = {
            // Base de conocimientos simple
            responses: {
                // Saludos
                [removeAccents('hola')]: ['¡Hola! 😊 Soy tu asistente de PowerGym. ¿En qué puedo ayudarte hoy?', '¡Hola! ¿Cómo estás? ¿Listo para entrenar?'],
                [removeAccents('buenos días')]: ['¡Buenos días! ☀️ ¡A por el entrenamiento! ¿En qué puedo ayudarte?'],
                [removeAccents('buenas tardes')]: ['¡Buenas tardes! 🌅 ¿Necesitas información sobre nuestras clases o instalaciones?'],
                [removeAccents('buenas noches')]: ['¡Buenas noches! 🌙 ¿Tienes alguna pregunta antes de tu próxima sesión?'],
                
                // Información del gimnasio
                [removeAccents('servicios')]: ['Ofrecemos una amplia variedad de clases (Yoga, Zumba, BodyPump, Pilates, Spinning), entrenamiento personalizado y acceso a nuestras instalaciones con equipos de última generación. ¿Qué te interesa más?'],
                [removeAccents('horarios')]: ['Nuestro gimnasio está abierto de lunes a viernes de 6:00 AM a 10:00 PM, y los sábados y domingos de 8:00 AM a 6:00 PM. ¡Te esperamos!'],
                [removeAccents('contacto')]: ['Puedes contactarnos en:\n📧 info@powergym.com\n📱 +34 987 654 321\n🔻 Av. Principal 45, Granada'],
                [removeAccents('clases')]: ['Tenemos clases de Yoga, Zumba, BodyPump, Pilates y Spinning. Puedes ver el horario completo en nuestra sección de "Clases" en la web, e incluso los entrenadores que las imparten. También puedes reservar tu clase en nuestra sección "mi actividad"'],
                [removeAccents('entrenadores')]: ['Contamos con un equipo de entrenadores certificados y con experiencia en diversas disciplinas. ¡Están listos para ayudarte a alcanzar tus metas!'],
                [removeAccents('precios')]: ['Ofrecemos diferentes planes de membresía. Te invitamos a visitar nuestra recepción o contactarnos para obtener información detallada sobre precios y promociones.'],
                [removeAccents('ubicacion')]: ['Estamos ubicados en Av. Principal 45, Granada. ¡Ven a conocernos!'],
                [removeAccents('prueba')]: ['¿Te gustaría una clase de prueba gratuita? ¡Contáctanos para agendarla!'],
                
                // Despedidas
                [removeAccents('adiós')]: ['¡Hasta luego! 👋 ¡Que tengas un excelente entrenamiento!'],
                [removeAccents('gracias')]: ['¡De nada! 😊 ¡A sudar se ha dicho!'],
                [removeAccents('bye')]: ['¡Bye! 👋 ¡Nos vemos en el gimnasio!']
            },
            
            // Palabras clave para detectar intenciones
            keywords: {
                saludos: [removeAccents('hola'), removeAccents('hi'), removeAccents('hey'), removeAccents('buenos días'), removeAccents('buenas tardes'), removeAccents('buenas noches')],
                servicios: [removeAccents('servicio'), removeAccents('servicios'), removeAccents('qué ofrecen'), removeAccents('actividades'), removeAccents('clases'), removeAccents('entrenamiento')],
                horarios: [removeAccents('horario'), removeAccents('horarios'), removeAccents('hora'), removeAccents('abierto'), removeAccents('cerrado'), removeAccents('cuándo abren'), removeAccents('cuándo cierran')],
                contacto: [removeAccents('contacto'), removeAccents('teléfono'), removeAccents('email'), removeAccents('dirección'), removeAccents('ubicación'), removeAccents('llamar'), removeAccents('dónde están')],
                clases: [removeAccents('clase'), removeAccents('clases'), removeAccents('yoga'), removeAccents('zumba'), removeAccents('bodypump'), removeAccents('pilates'), removeAccents('spinning'), removeAccents('actividad')],
                entrenadores: [removeAccents('entrenador'), removeAccents('entrenadores'), removeAccents('coach'), removeAccents('personal trainer')],
                precios: [removeAccents('precio'), removeAccents('precios'), removeAccents('costo'), removeAccents('tarifa'), removeAccents('cuánto cuesta'), removeAccents('membresía'), removeAccents('planes')],
                ubicacion: [removeAccents('ubicación'), removeAccents('dirección'), removeAccents('dónde están'), removeAccents('llegar')],
                prueba: [removeAccents('prueba'), removeAccents('clase gratis'), removeAccents('probar'), removeAccents('primera vez')],
                despedidas: [removeAccents('adiós'), removeAccents('bye'), removeAccents('chao'), removeAccents('nos vemos'), removeAccents('hasta luego')],
                agradecimientos: [removeAccents('gracias'), removeAccents('thank you'), removeAccents('muchas gracias'), removeAccents('te agradezco')]            }
        };

        // Referencias a elementos DOM
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendButton = document.getElementById('sendButton');
        const typingIndicator = document.getElementById('typingIndicator');

        // Event listeners
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        chatInput.addEventListener('input', function() {
            sendButton.disabled = this.value.trim() === '';
        });

        // Función principal para enviar mensajes
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Mostrar mensaje del usuario
            addMessage(message, 'user');
            chatInput.value = '';
            sendButton.disabled = true;

            // Mostrar indicador de escritura
            showTyping();

            // Procesar mensaje y obtener respuesta
            const response = await processMessage(message);
            
            // Simular tiempo de respuesta
            setTimeout(() => {
                hideTyping();
                addMessage(response, 'bot');
            }, 1000 + Math.random() * 1000);
        }

        // Función para respuestas rápidas
        function sendQuickMessage(message) {
            chatInput.value = message;
            sendMessage();
        }

        // Agregar mensaje al chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            
            const bubbleDiv = document.createElement('div');
            bubbleDiv.className = 'message-bubble';
            bubbleDiv.innerHTML = text.replace(/\n/g, '<br>');
            
            const timeDiv = document.createElement('div');
            timeDiv.className = 'message-time';
            timeDiv.textContent = new Date().toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            messageDiv.appendChild(bubbleDiv);
            messageDiv.appendChild(timeDiv);
            chatMessages.appendChild(messageDiv);
            
            // Scroll al final
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Mostrar indicador de escritura
        function showTyping() {
            typingIndicator.style.display = 'block';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Ocultar indicador de escritura
        function hideTyping() {
            typingIndicator.style.display = 'none';
        }

        // Procesamiento principal del mensaje
        async function processMessage(message) {
            const normalizedMessage = removeAccents(message.toLowerCase().trim());
            
            // 1. Buscar coincidencia exacta
            if (chatbot.responses[normalizedMessage]) {
                const responses = chatbot.responses[normalizedMessage];
                return responses[Math.floor(Math.random() * responses.length)];
            }
            
            // 2. Buscar por palabras clave
            for (const [intent, keywords] of Object.entries(chatbot.keywords)) {
                if (keywords.some(keyword => normalizedMessage.includes(keyword))) {
                    if (chatbot.responses[intent]) {
                        const responses = chatbot.responses[intent];
                        return responses[Math.floor(Math.random() * responses.length)];
                    }
                    // Si no hay respuesta específica, usar la primera palabra clave como key
                    const firstKeyword = keywords.find(keyword => normalizedMessage.includes(keyword));
                    if (chatbot.responses[firstKeyword]) {
                        const responses = chatbot.responses[firstKeyword];
                        return responses[Math.floor(Math.random() * responses.length)];
                    }
                }
            }
            
            // 3. Respuesta por defecto con sugerencias
            const suggestions = [
                '¿Te interesa conocer nuestros servicios?',
                '¿Quieres saber nuestros horarios?',
                '¿Necesitas información de contacto?',
                '¿Buscas información sobre precios?'
            ];
            
            const randomSuggestion = suggestions[Math.floor(Math.random() * suggestions.length)];
            
            return `Lo siento, no estoy seguro de cómo ayudarte con eso. ${randomSuggestion}`;
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            sendButton.disabled = true;
            chatInput.focus();
        });