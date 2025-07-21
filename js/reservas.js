document.addEventListener('DOMContentLoaded', function() {
    const clasesContainer = document.getElementById('clases-container');

    // 1. Cargar clases desde la API
    cargarClases();

    function cargarClases() {
        fetch('api/index.php?resource=clases')
            .then(response => response.json())
            .then(clases => {
                mostrarClases(clases);
            })
            .catch(error => {
                console.error('Error al cargar las clases:', error);
                clasesContainer.innerHTML = '<p>Error al cargar las clases. Inténtalo de nuevo más tarde.</p>';
            });
    }

    // 2. Mostrar las clases en el DOM
    function mostrarClases(clases) {
        clasesContainer.innerHTML = ''; // Limpiar contenedor
        clases.forEach(clase => {
            const claseCard = document.createElement('div');
            claseCard.className = 'clase-card-reserva';
            // Añadimos un data-attribute para identificar la tarjeta fácilmente
            claseCard.dataset.claseId = clase.id;

            const cupoLleno = parseInt(clase.inscritos_actuales) >= parseInt(clase.cupo_maximo);
            const yaInscrito = parseInt(clase.usuario_inscrito) === 1;

            let botonHTML;
            if (yaInscrito) {
                botonHTML = `<button class="reservar-btn lleno" disabled>Reservado</button>`;
            } else if (cupoLleno) {
                botonHTML = `<button class="reservar-btn lleno" disabled>Cupo Lleno</button>`;
            } else {
                botonHTML = `<button class="reservar-btn" data-clase-id="${clase.id}">Reservar</button>`;
            }

            claseCard.innerHTML = `
                <h3>${clase.nombre}</h3>
                <p><strong>Día:</strong> ${clase.dia_semana}</p>
                <p><strong>Hora:</strong> ${clase.hora}</p>
                <p><strong>Entrenador:</strong> ${clase.nombre_entrenador}</p>
                <p>
                    <strong>Cupo:</strong> 
                    <span id="inscritos-${clase.id}">${clase.inscritos_actuales}</span> / ${clase.cupo_maximo}
                </p>
                ${botonHTML}
            `;
            clasesContainer.appendChild(claseCard);
        });

        // 3. Añadir listeners a los botones después de crear las tarjetas
        configurarBotonesReserva();
        
        // 4. Aplicar animación GSAP a las nuevas tarjetas
        animarTarjetas();
    }

    function configurarBotonesReserva() {
        const botonesReservar = document.querySelectorAll('.reservar-btn:not([disabled])');
        botonesReservar.forEach(boton => {
            boton.addEventListener('click', function() {
                const claseId = this.getAttribute('data-clase-id');
                reservarClase(claseId, this);
            });
        });
    }

    // 5. Lógica para reservar una clase
    function reservarClase(claseId, boton) {
        boton.disabled = true;
        boton.textContent = 'Procesando...';

        fetch('api/index.php?resource=reservar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_clase: claseId })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(result => {
            alert(result.body.message);

            if (result.status === 200) { // Éxito
                // **ACCIÓN CLAVE: Actualización instantánea**
                const inscritosSpan = document.getElementById(`inscritos-${claseId}`);
                if (inscritosSpan) {
                    let inscritosActuales = parseInt(inscritosSpan.textContent);
                    inscritosSpan.textContent = inscritosActuales + 1;
                }
                
                boton.textContent = 'Reservado';
                boton.classList.add('lleno');
                // El botón ya está deshabilitado, así que no hace falta volver a deshabilitarlo.
            } else {
                // Si falla, revertir el botón para que el usuario pueda intentarlo de nuevo si es pertinente
                boton.disabled = false;
                boton.textContent = 'Reservar';
            }
        })
        .catch(error => {
            console.error('Error en la petición de reserva:', error);
            alert('Ocurrió un error. Por favor, inténtalo de nuevo.');
            boton.disabled = false;
            boton.textContent = 'Reservar';
        });
    }

    // 6. Animaciones
    function animarTarjetas() {
        gsap.from(".clase-card-reserva", {
            opacity: 0,
            y: 100,
            scale: 0.8,
            rotationZ: -10,
            duration: 1.2,
            stagger: 0.2,
            ease: "back.out(1.7)"
        });
    }

    // Animación inicial del contenedor y confeti
    gsap.from(".reservas-container", {
        opacity: 0,
        y: 200,
        scale: 0.9,
        duration: 3.5,
        delay: 0.3,
        ease: "bounce.out",
        onComplete: createConfetti
    });

    function createConfetti() {
        const confettiContainer = document.getElementById('confetti-container');
        if (!confettiContainer) return;
        const colors = ["#9370DB", "#8A2BE2", "#6a0dad", "#4B0082", "#FFFFFF", "#FFD700"];

        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.classList.add('confetti-particle');
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            
            const startX = Math.random() * window.innerWidth;
            const startY = Math.random() * window.innerHeight;
            particle.style.left = `${startX}px`;
            particle.style.top = `${startY}px`;

            confettiContainer.appendChild(particle);

            gsap.to(particle, {
                x: Math.random() * 400 - 200,
                y: Math.random() * 400 - 200,
                rotation: Math.random() * 360,
                scale: Math.random() * 0.5 + 0.5,
                opacity: 0,
                duration: Math.random() * 2 + 1,
                ease: "power1.out",
                onComplete: () => particle.remove()
            });
        }
    }
});