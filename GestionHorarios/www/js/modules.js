document.addEventListener("DOMContentLoaded", function() {
    /* ================================
       1. Slider en vista móvil
    ================================= */
    const container = document.querySelector(".timetable-mobile-container");
    if (container) {
        const slides = document.querySelectorAll(".timetable-mobile-day");
        const dotsContainer = document.querySelector(".dots");
        if (dotsContainer) {
            let currentSlide = 0;

            // Crear puntos de navegación
            slides.forEach((slide, index) => {
                const dot = document.createElement("span");
                dot.classList.add("dot");
                if (index === 0) dot.classList.add("active");
                dot.addEventListener("click", () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll(".dot");

            function goToSlide(index) {
                currentSlide = index;
                container.style.transform = `translateX(-${index * 100}vw)`;
                dots.forEach((dot, i) => {
                    dot.classList.toggle("active", i === index);
                });
            }

            // Swipe táctil
            let startX = 0;
            container.addEventListener("touchstart", e => {
                startX = e.touches[0].clientX;
            });
            container.addEventListener("touchend", e => {
                let endX = e.changedTouches[0].clientX;
                if (startX - endX > 50 && currentSlide < slides.length - 1) {
                    goToSlide(currentSlide + 1);
                } else if (endX - startX > 50 && currentSlide > 0) {
                    goToSlide(currentSlide - 1);
                }
            });

            goToSlide(0); // Iniciar en la primera tarjeta (Lunes)
        }
    }

    /* ================================
       2. Mapeo y actualización de módulos
    ================================= */
    let modulesMap = {};
    let usage = {};

    // Función para obtener módulos y asignaciones desde la BD
    function actualizarModulos() {
        console.log("Ejecutando actualizarModulos()...");

        const ciclo = document.getElementById('ciclo').value;
        const curso = document.getElementById('curso').value;

        if (!ciclo || !curso) {
            console.log("Ciclo o curso no seleccionados. No se ejecuta la actualización.");
            return;
        }

        const formData = new FormData();
        formData.append('ciclo', ciclo);
        formData.append('curso', curso);

        fetch('../functions/modules/get_modules.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data);

            if (data.error) {
                console.error("Error al obtener módulos:", data.error);
                return;
            }

            // Mapeo de módulos disponibles
            modulesMap = {};
            data.modules.forEach(mod => {
                modulesMap[mod.id] = {
                    name: mod.name,
                    color: mod.color,
                    sessions_number: parseInt(mod.sessions_number, 10) || 0
                };
            });

            // Inicialización del uso de módulos
            usage = {};
            Object.keys(modulesMap).forEach(modId => {
                usage[modId] = 0;
            });

            // Llenar los selects de la tabla con los módulos disponibles
            const selects = document.querySelectorAll('select[name^="modules["]');
            selects.forEach(select => {
                const sessionId = select.name.match(/modules\[(\d+)\]/)[1];
                const assignedModId = data.assignedModules[sessionId] || "";

                // Limpiar el select antes de agregar nuevas opciones
                select.innerHTML = "";

                // Opción por defecto
                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = "Selecciona Módulo";
                select.appendChild(defaultOption);

                // Agregar opciones de módulos disponibles
                data.modules.forEach(mod => {
                    const option = document.createElement('option');
                    option.value = mod.id;
                    option.textContent = mod.name;
                    option.setAttribute('data-color', mod.color);
                    option.setAttribute('data-max-sessions', mod.sessions_number);

                    // Preseleccionar si estaba asignado
                    if (mod.id == assignedModId) {
                        option.selected = true;
                        select.style.backgroundColor = mod.color;
                    }
                    select.appendChild(option);
                });

                // Guardar el valor antiguo
                select.dataset.oldValue = assignedModId;

                // Incrementar contador de uso si ya estaba asignado
                if (assignedModId) {
                    usage[assignedModId]++;
                }
            });

            // Añadir listeners a los selects para gestionar cambios
            attachesSelectListeners();
            // Aplicar colores
            aplicarColores();
        })
        .catch(error => {
            console.error("Error al obtener módulos:", error);
        });
    }

    // Función para aplicar color de fondo al select según la opción seleccionada
    function aplicarColores() {
        document.querySelectorAll('select[name^="modules["]').forEach(select => {
            const color = select.options[select.selectedIndex].getAttribute('data-color');
            select.style.backgroundColor = color || '';

            const td = select.closest('td');
            if (td) {
                td.style.backgroundColor = color || '';
            }
        });
    }

    // Agregar listeners a los selects para controlar la validación de límite
    function attachesSelectListeners() {
        document.querySelectorAll('select[name^="modules["]').forEach(select => {
            select.removeEventListener('change', onSelectChange);
            select.addEventListener('change', onSelectChange);
        });
    }

    // Lógica para manejar cambios en los selects
    function onSelectChange(event) {
        const select = event.target;
        const newValue = select.value;
        const oldValue = select.dataset.oldValue;

        if (oldValue) {
            usage[oldValue]--;
            if (usage[oldValue] < 0) usage[oldValue] = 0;
        }

        if (newValue) {
            usage[newValue]++;
            const maxSessions = modulesMap[newValue].sessions_number;
            if (usage[newValue] > maxSessions) {
                alert(`Has superado el número máximo de sesiones para el módulo: ${modulesMap[newValue].name} (máximo: ${maxSessions})`);
                usage[newValue]--;
                select.value = oldValue || "";
            } else {
                select.dataset.oldValue = newValue;
            }
        } else {
            select.dataset.oldValue = "";
        }
        aplicarColores();
    }

    /* ================================
       3. Sincronización de selects de ciclo y curso
    ================================= */
    // Vista de escritorio
    const cicloSelect = document.getElementById("ciclo");
    const cursoSelect = document.getElementById("curso");
    const cicloHidden = document.getElementById("cicloHidden");
    const cursoHidden = document.getElementById("cursoHidden");

    function actualizarCicloCurso() {
        cicloHidden.value = cicloSelect.value;
        cursoHidden.value = cursoSelect.value;
        actualizarModulos();
    }

    if (cicloSelect && cursoSelect) {
        cicloSelect.addEventListener("change", actualizarCicloCurso);
        cursoSelect.addEventListener("change", actualizarCicloCurso);
    }

    actualizarModulos();

    // Vista móvil: sincronizar selects de filtro
    const cicloMobile = document.getElementById("cicloMobile");
    const cursoMobile = document.getElementById("cursoMobile");
    const cicloHiddenMobile = document.getElementById("cicloHiddenMobile");
    const cursoHiddenMobile = document.getElementById("cursoHiddenMobile");

    if (cicloMobile && cursoMobile && cicloHiddenMobile && cursoHiddenMobile) {
        cicloMobile.addEventListener("change", () => {
            cicloHiddenMobile.value = cicloMobile.value;
            document.getElementById("filter-form-mobile").submit(); 
        });
        cursoMobile.addEventListener("change", () => {
            cursoHiddenMobile.value = cursoMobile.value;
            document.getElementById("filter-form-mobile").submit();
        });
    }
});
