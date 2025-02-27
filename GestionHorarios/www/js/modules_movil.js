// Mapeo de módulos disponibles
let modulesMap = {};
// Control de cuántas sesiones están asignadas a cada módulo
let usage = {};

// Función para obtener módulos y asignaciones desde la base de datos
function actualizarModulos() {
    console.log("Ejecutando actualizarModulos()...");

    const ciclo = document.getElementById('ciclo').value;
    const curso = document.getElementById('curso').value;

    if (!ciclo || !curso) return;

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

        // Crear el mapeo de módulos disponibles
        modulesMap = {};
        data.modules.forEach(mod => {
            modulesMap[mod.id] = {
                name: mod.name,
                color: mod.color,
                sessions_number: parseInt(mod.sessions_number, 10) || 0
            };
        });

        // Inicializar el contador de uso para cada módulo
        usage = {};
        Object.keys(modulesMap).forEach(modId => {
            usage[modId] = 0;
        });

        // Actualizar los selects que se encuentran en cada slide (vista móvil)
        const selects = document.querySelectorAll('select[name^="modules["]');
        selects.forEach(select => {
            const sessionIdMatch = select.name.match(/modules\[(\d+)\]/);
            const sessionId = sessionIdMatch ? sessionIdMatch[1] : null;
            const assignedModId = (data.assignedModules && data.assignedModules[sessionId]) ? data.assignedModules[sessionId] : "";

            // Limpiar el select antes de agregar nuevas opciones
            select.innerHTML = "";

            // Opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Selecciona Módulo";
            select.appendChild(defaultOption);

            // Agregar cada opción con sus atributos y color
            data.modules.forEach(mod => {
                const option = document.createElement('option');
                option.value = mod.id;
                option.textContent = mod.name;
                option.setAttribute('data-color', mod.color);
                option.setAttribute('data-max-sessions', mod.sessions_number);

                // Si este módulo estaba asignado previamente, lo preseleccionamos
                if (mod.id == assignedModId) {
                    option.selected = true;
                    select.style.backgroundColor = mod.color;
                }
                select.appendChild(option);
            });

            // Guardar el valor anterior para luego comparar en cambios
            select.dataset.oldValue = assignedModId;

            // Incrementar contador si había un módulo asignado
            if (assignedModId) {
                usage[assignedModId]++;
            }
        });

        // Añadir los listeners a los selects para controlar los cambios
        attachesSelectListeners();

        // Aplicar el color de fondo a cada select y su contenedor
        aplicarColores();

        // Si usas un slider (por ejemplo, Swiper), actualiza su instancia para reflejar los cambios
        if (typeof swiper !== 'undefined' && swiper.update) {
            swiper.update();
        }
    })
    .catch(error => {
        console.error("Error al obtener módulos:", error);
    });
}

// Función para aplicar el color de fondo al select según la opción seleccionada
function aplicarColores() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        const color = select.options[select.selectedIndex] ? select.options[select.selectedIndex].getAttribute('data-color') : '';
        // Aplicar color al select
        select.style.backgroundColor = color || '';

        // Si el select está contenido en un elemento (por ejemplo, una tarjeta o contenedor en la slide), aplicarle el color también
        const container = select.closest('td') || select.closest('.session');
        if (container) {
            container.style.backgroundColor = color || '';
        }
    });
}

// Función para añadir los listeners a cada select
function attachesSelectListeners() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        select.removeEventListener('change', onSelectChange); // Remover listeners previos
        select.addEventListener('change', onSelectChange);
    });
}

// Lógica para manejar el cambio en los selects y validar el límite de sesiones por módulo
function onSelectChange(event) {
    const select = event.target;
    const newValue = select.value;            // Nuevo módulo seleccionado
    const oldValue = select.dataset.oldValue;   // Módulo que tenía asignado previamente

    // Decrementar el contador del módulo anterior (si lo hubiera)
    if (oldValue) {
        usage[oldValue]--;
        if (usage[oldValue] < 0) usage[oldValue] = 0; // Por seguridad
    }

    // Incrementar el contador del nuevo módulo
    if (newValue) {
        usage[newValue]++;
        const maxSessions = modulesMap[newValue].sessions_number;
        if (usage[newValue] > maxSessions) {
            alert(`Has superado el número máximo de sesiones para el módulo: ${modulesMap[newValue].name} (máximo: ${maxSessions})`);
            // Revertir al valor anterior
            usage[newValue]--;
            select.value = oldValue || "";
        } else {
            select.dataset.oldValue = newValue;
        }
    } else {
        select.dataset.oldValue = "";
    }

    // Reaplicar colores luego del cambio
    aplicarColores();
}

// Inicialización al cargar la página (o cuando se use el slider en la vista móvil)
document.addEventListener("DOMContentLoaded", () => {
    const cicloSelect = document.getElementById("ciclo");
    const cursoSelect = document.getElementById("curso");

    const cicloHidden = document.getElementById("cicloHidden");
    const cursoHidden = document.getElementById("cursoHidden");

    // Actualizar los valores ocultos y recargar módulos al cambiar ciclo o curso
    function actualizarCicloCurso() {
        cicloHidden.value = cicloSelect.value;
        cursoHidden.value = cursoSelect.value;
        actualizarModulos();
    }

    if (cicloSelect && cursoSelect) {
        cicloSelect.addEventListener("change", actualizarCicloCurso);
        cursoSelect.addEventListener("change", actualizarCicloCurso);
    }

    // Cargar los módulos al inicio
    actualizarModulos();
});
