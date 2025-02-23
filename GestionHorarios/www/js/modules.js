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

                // Si este módulo estaba asignado previamente, lo preseleccionamos
                if (mod.id == assignedModId) {
                    option.selected = true;
                    select.style.backgroundColor = mod.color; // Aplicar color
                }

                select.appendChild(option);
            });

            // Guardamos el valor antiguo
            select.dataset.oldValue = assignedModId;

            // Aumentamos el contador de uso del módulo asignado
            if (assignedModId) {
                usage[assignedModId]++;
            }
        });

        // Añadir listeners a los selects para gestionar cambios
        attachesSelectListeners();

        // Aplicar los colores
        aplicarColores();
    })
    .catch(error => {
        console.error("Error al obtener módulos:", error);
    });
}

// Aplicar el color de fondo al select según la opción seleccionada
function aplicarColores() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        const color = select.options[select.selectedIndex].getAttribute('data-color');
        
        // Pintar el fondo del select
        select.style.backgroundColor = color || '';

        // 🔥 También pintar el fondo de la celda <td> que contiene el select
        const td = select.closest('td'); // Encuentra el <td> más cercano
        if (td) {
            td.style.backgroundColor = color || '';
        }
    });
}


// Agregar listeners a los selects para controlar la validación de límite
function attachesSelectListeners() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        select.removeEventListener('change', onSelectChange); // Eliminar cualquier listener previo
        select.addEventListener('change', onSelectChange);
    });
}

// Lógica para manejar cambios en los selects
function onSelectChange(event) {
    const select = event.target;
    const newValue = select.value;            // Nuevo módulo seleccionado
    const oldValue = select.dataset.oldValue; // Módulo que tenía antes

    // Decrementar uso del módulo anterior
    if (oldValue) {
        usage[oldValue]--;
        if (usage[oldValue] < 0) usage[oldValue] = 0; // Seguridad
    }

    // Incrementar uso del nuevo módulo
    if (newValue) {
        usage[newValue]++;

        // Verificar si supera el límite permitido
        const maxSessions = modulesMap[newValue].sessions_number;
        if (usage[newValue] > maxSessions) {
            alert(`Has superado el número máximo de sesiones para el módulo: ${modulesMap[newValue].name} (máximo: ${maxSessions})`);
            
            // Revertir al valor anterior
            usage[newValue]--;
            select.value = oldValue || ""; // Si no había oldValue, lo dejamos vacío
        } else {
            select.dataset.oldValue = newValue;
        }
    } else {
        select.dataset.oldValue = "";
    }

    // Aplicar color al nuevo valor seleccionado
    aplicarColores();
}

document.addEventListener("DOMContentLoaded", () => {
    const cicloSelect = document.getElementById("ciclo");
    const cursoSelect = document.getElementById("curso");

    const cicloHidden = document.getElementById("cicloHidden");
    const cursoHidden = document.getElementById("cursoHidden");

    // Función para actualizar los valores ocultos y cargar los módulos
    function actualizarCicloCurso() {
        cicloHidden.value = cicloSelect.value;
        cursoHidden.value = cursoSelect.value;
        actualizarModulos(); // 🔹 Asegurar que los módulos se vuelvan a cargar
    }

    if (cicloSelect && cursoSelect) {
        cicloSelect.addEventListener("change", actualizarCicloCurso);
        cursoSelect.addEventListener("change", actualizarCicloCurso);
    }

    actualizarModulos(); // 🔹 Cargar módulos al inicio
});
