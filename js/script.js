document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const menuItems = document.querySelectorAll('.menu-item');
    const submenus = document.querySelectorAll('.submenu');
    const welcomeMessage = document.querySelector('.welcome-message');
    const backButton = document.getElementById('back-button');
    const circleItems = document.querySelectorAll('.circle-item');
    const dynamicContent = document.getElementById('dynamic-content');
    const dynamicSections = document.querySelectorAll('.dynamic-section');

    // Estado actual
    let currentSubmenu = null;

    function hideAll() {
        submenus.forEach(submenu => submenu.classList.add('hidden'));
        dynamicSections.forEach(section => section.classList.add('hidden'));
        dynamicContent.classList.add('hidden');
        welcomeMessage.classList.remove('hidden');
        backButton.classList.add('hidden');
        currentSubmenu = null;
    }

    // Función para mostrar un submenú específico
    function showSubmenu(submenuId) {
        hideAll();
        const submenu = document.getElementById(submenuId);
        if (submenu) {
            submenu.classList.remove('hidden');
            welcomeMessage.classList.add('hidden');
            backButton.classList.remove('hidden');
            currentSubmenu = submenuId;
        }
    }

    function showDynamicContent(sectionId) {
        hideAll();
        const section = document.getElementById(sectionId);
        if (section) {
            dynamicContent.classList.remove('hidden');
            section.classList.remove('hidden');
            welcomeMessage.classList.add('hidden');
            backButton.classList.remove('hidden');
            currentSubmenu = sectionId;
        }
    }

    // Función para agregar efectos de sonido (simulado con vibración en móviles)
    function playClickEffect() {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }

    // Event listeners para los elementos del menú principal
    document.getElementById('mi-fitness').addEventListener('click', function() {
        playClickEffect();
        showSubmenu('fitness-submenu');
    });

    document.getElementById('mi-actividad').addEventListener('click', function() {
        playClickEffect();
        showSubmenu('actividad-submenu');
    });

    document.getElementById('mas').addEventListener('click', function() {
        playClickEffect();
        showSubmenu('mas-submenu');
    });

    // Event listener para el botón de volver
    backButton.addEventListener('click', function() {
        playClickEffect();
        hideAll();
    });

    // Event listeners para los círculos de opciones
    circleItems.forEach(item => {
        item.addEventListener('click', function() {
            playClickEffect();
            const option = this.getAttribute('data-option');
            handleCircleClick(option);
        });

        // Agregar efecto de hover con animación
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });

    // Función para manejar clics en círculos
    function handleCircleClick(option) {
        if (option === 'clases') {
            loadClases();
        } else if (option === 'entrenadores') {
            loadEntrenadores();
        } else if (option === 'reservas') {
            checkUserLoginAndLoadReservas();
        } else if (option === 'reseñas') {
            showDynamicContent('reseñas-content');
        } else {
            showNotification(`Has seleccionado: ${getOptionText(option)}`);
        }
        
        // Agregar animación de selección
        const clickedItem = document.querySelector(`[data-option="${option}"]`);
        if (clickedItem) {
            clickedItem.style.animation = 'none';
            setTimeout(() => {
                clickedItem.style.animation = 'pulse 0.5s ease-in-out';
            }, 10);
        }
    }

    // Función para verificar el estado de login y cargar las reservas
    function checkUserLoginAndLoadReservas() {
        fetch('api/index.php?resource=check_session')
            .then(response => response.json())
            .then(data => {
                if (data.logged_in) {
                    loadReservas();
                } else {
                    // Redirigir al login si no está logueado
                    window.location.href = 'login.php';
                }
            })
            .catch(error => {
                console.error('Error al verificar sesión:', error);
                showNotification('Error al verificar tu sesión. Inténtalo de nuevo.');
            });
    }

    // Función para cargar y mostrar las clases para reserva
    function loadReservas() {
        const reservasContent = document.getElementById('reservas-content');
        showDynamicContent('reservas-content');
        reservasContent.innerHTML = '<h3>Reserva tu Clase</h3><p>Cargando clases disponibles...</p>';

        fetch('api/index.php?resource=clases')
            .then(response => response.json())
            .then(data => {
                reservasContent.innerHTML = '<h3>Reserva tu Clase</h3>';
                if (data.length > 0) {
                    const clasesGrid = document.createElement('div');
                    clasesGrid.className = 'clases-grid'; // Reutilizamos los estilos de clases
                    
                    data.forEach(clase => {
                        const cupo_disponible = clase.cupo_maximo - clase.inscritos_actuales;
                        const claseCard = document.createElement('div');
                        claseCard.className = 'clase-card';
                        
                        claseCard.innerHTML = `
                            <img src="img/${clase.imagen_url || 'default-clase.jpg'}" alt="Imagen de ${clase.nombre}">
                            <div class="clase-card-body">
                                <h4>${clase.nombre}</h4>
                                <p class="clase-descripcion">${clase.descripcion}</p>
                                <div class="clase-detalles">
                                    <p><strong>Entrenador:</strong> ${clase.nombre_entrenador}</p>
                                    <p><strong>Horario:</strong> ${clase.dia_semana} a las ${clase.hora.substring(0, 5)}</p>
                                    <p><strong>Duración:</strong> ${clase.duracion_minutos} min</p>
                                    <p><strong>Cupo:</strong> ${cupo_disponible} / ${clase.cupo_maximo}</p>
                                </div>
                                <button class="btn-reservar" data-id-clase="${clase.id}" ${cupo_disponible <= 0 ? 'disabled' : ''}>
                                    ${cupo_disponible <= 0 ? 'Cupo Lleno' : 'Reservar'}
                                </button>
                            </div>
                        `;
                        clasesGrid.appendChild(claseCard);
                    });
                    reservasContent.appendChild(clasesGrid);

                    // Añadir event listeners a los botones de reservar
                    document.querySelectorAll('.btn-reservar').forEach(button => {
                        button.addEventListener('click', function() {
                            const idClase = this.getAttribute('data-id-clase');
                            reservarClase(idClase);
                        });
                    });

                } else {
                    reservasContent.innerHTML += '<p>No hay clases disponibles para reservar en este momento.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar las clases para reserva:', error);
                reservasContent.innerHTML += '<p>Hubo un error al cargar las clases. Inténtalo de nuevo más tarde.</p>';
            });
    }

    // Función para enviar la solicitud de reserva
    function reservarClase(idClase) {
        const formData = new FormData();
        formData.append('id_clase', idClase);

        fetch('api/index.php?resource=reservar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            showNotification(data.message);
            if (data.success) {
                // Si la reserva fue exitosa, recargar las clases para actualizar el cupo
                loadReservas();
            } else if (data.redirect) {
                // Si se requiere login, redirigir
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Error al reservar clase:', error);
            showNotification('Error de conexión al intentar reservar. Inténtalo de nuevo.');
        });
    }

    // Función para cargar y mostrar los entrenadores desde la API
    function loadEntrenadores() {
        const entrenadoresContent = document.getElementById('entrenadores-content');
        showDynamicContent('entrenadores-content');
        entrenadoresContent.innerHTML = '<h3>Nuestros Entrenadores</h3><p>Cargando entrenadores...</p>';

        fetch('api/index.php?resource=entrenadores')
            .then(response => response.json())
            .then(data => {
                entrenadoresContent.innerHTML = '<h3>Nuestros Entrenadores</h3>';
                if (data.length > 0) {
                    const entrenadoresGrid = document.createElement('div');
                    entrenadoresGrid.className = 'entrenadores-grid';
                    
                    data.forEach(entrenador => {
                        const entrenadorCard = document.createElement('div');
                        entrenadorCard.className = 'entrenador-card';
                        
                        entrenadorCard.innerHTML = `
                            <img src="img/${entrenador.foto_url || 'default-entrenador.jpg'}" alt="Foto de ${entrenador.nombre}">
                            <div class="entrenador-card-body">
                                <h4>${entrenador.nombre}</h4>
                                <p class="entrenador-especialidad">${entrenador.especialidad}</p>
                                <div class="entrenador-contacto">
                                    <p><strong>Email:</strong> ${entrenador.email}</p>
                                </div>
                            </div>
                        `;
                        entrenadoresGrid.appendChild(entrenadorCard);
                    });
                    entrenadoresContent.appendChild(entrenadoresGrid);
                } else {
                    entrenadoresContent.innerHTML += '<p>No hay entrenadores disponibles en este momento.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar los entrenadores:', error);
                entrenadoresContent.innerHTML += '<p>Hubo un error al cargar los entrenadores. Inténtalo de nuevo más tarde.</p>';
            });
    }

    // Función para cargar y mostrar las clases desde la API
    function loadClases() {
        const clasesContent = document.getElementById('clases-content');
        showDynamicContent('clases-content');
        clasesContent.innerHTML = '<h3>Nuestras Clases</h3><p>Cargando clases...</p>';

        fetch('api/index.php?resource=clases')
            .then(response => response.json())
            .then(data => {
                clasesContent.innerHTML = '<h3>Nuestras Clases</h3>';
                if (data.length > 0) {
                    const clasesGrid = document.createElement('div');
                    clasesGrid.className = 'clases-grid';
                    
                    data.forEach(clase => {
                        const claseCard = document.createElement('div');
                        claseCard.className = 'clase-card';
                        
                        claseCard.innerHTML = `
                            <img src="img/${clase.imagen_url || 'default-clase.jpg'}" alt="Imagen de ${clase.nombre}">
                            <div class="clase-card-body">
                                <h4>${clase.nombre}</h4>
                                <p class="clase-descripcion">${clase.descripcion}</p>
                                <div class="clase-detalles">
                                    <p><strong>Entrenador:</strong> ${clase.nombre_entrenador}</p>
                                    <p><strong>Horario:</strong> ${clase.dia_semana} a las ${clase.hora.substring(0, 5)}</p>
                                    <p><strong>Duración:</strong> ${clase.duracion_minutos} min</p>
                                    <p><strong>Cupo:</strong> ${clase.inscritos_actuales} / ${clase.cupo_maximo}</p>
                                </div>
                            </div>
                        `;
                        clasesGrid.appendChild(claseCard);
                    });
                    clasesContent.appendChild(clasesGrid);
                } else {
                    clasesContent.innerHTML += '<p>No hay clases disponibles en este momento.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar las clases:', error);
                clasesContent.innerHTML += '<p>Hubo un error al cargar las clases. Inténtalo de nuevo más tarde.</p>';
            });
    }

    // Función para obtener texto de la opción
    function getOptionText(option) {
        const texts = {
            'clases': 'Clases',
            'entrenadores': 'Entrenadores',
            'dieta': 'Dieta',
            'reservas': 'Reserva tus Clases',
            'reseñas': 'Reseñas',
            'mensaje': 'Deja tu Mensaje',
            'notificaciones': 'Notificaciones'
        };
        return texts[option] || option;
    }

    // Función para mostrar notificaciones
    function showNotification(message) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        
        // Estilos para la notificación
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #8a2be2, #4b0082);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: slideInRight 0.3s ease-out;
            font-weight: 600;
        `;

        // Agregar al DOM
        document.body.appendChild(notification);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Agregar animaciones CSS dinámicamente
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(300px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(300px);
            }
        }
        
        .notification {
            transition: all 0.3s ease;
        }
    `;
    document.head.appendChild(style);

    // Función para manejar el teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && currentSubmenu) {
            hideAll();
        }
    });

    // Agregar efecto de partículas en el fondo (opcional)
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 4px;
            height: 4px;
            background: rgba(138, 43, 226, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
            animation: particleFloat 3s linear infinite;
        `;
        
        // Posición aleatoria
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = '100%';
        
        document.body.appendChild(particle);
        
        // Remover después de la animación
        setTimeout(() => {
            if (document.body.contains(particle)) {
                document.body.removeChild(particle);
            }
        }, 3000);
    }

    // Agregar animación de partículas
    const particleStyle = document.createElement('style');
    particleStyle.textContent = `
        @keyframes particleFloat {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(particleStyle);

    // Crear partículas periódicamente
    setInterval(createParticle, 2000);

    // Inicialización
    console.log('PowerGym App inicializada correctamente');
});


