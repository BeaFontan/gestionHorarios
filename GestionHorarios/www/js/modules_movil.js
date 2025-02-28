
let modulesMap = {};

let usage = {};


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


        modulesMap = {};
        data.modules.forEach(mod => {
            modulesMap[mod.id] = {
                name: mod.name,
                color: mod.color,
                sessions_number: parseInt(mod.sessions_number, 10) || 0
            };
        });


        usage = {};
        Object.keys(modulesMap).forEach(modId => {
            usage[modId] = 0;
        });


        const selects = document.querySelectorAll('select[name^="modules["]');
        selects.forEach(select => {
            const sessionIdMatch = select.name.match(/modules\[(\d+)\]/);
            const sessionId = sessionIdMatch ? sessionIdMatch[1] : null;
            const assignedModId = (data.assignedModules && data.assignedModules[sessionId]) ? data.assignedModules[sessionId] : "";


            select.innerHTML = "";


            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Selecciona Módulo";
            select.appendChild(defaultOption);


            data.modules.forEach(mod => {
                const option = document.createElement('option');
                option.value = mod.id;
                option.textContent = mod.name;
                option.setAttribute('data-color', mod.color);
                option.setAttribute('data-max-sessions', mod.sessions_number);


                if (mod.id == assignedModId) {
                    option.selected = true;
                    select.style.backgroundColor = mod.color;
                }
                select.appendChild(option);
            });


            select.dataset.oldValue = assignedModId;


            if (assignedModId) {
                usage[assignedModId]++;
            }
        });


        attachesSelectListeners();


        aplicarColores();


        if (typeof swiper !== 'undefined' && swiper.update) {
            swiper.update();
        }
    })
    .catch(error => {
        console.error("Error al obtener módulos:", error);
    });
}


function aplicarColores() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        const color = select.options[select.selectedIndex] ? select.options[select.selectedIndex].getAttribute('data-color') : '';

        select.style.backgroundColor = color || '';


        const container = select.closest('td') || select.closest('.session');
        if (container) {
            container.style.backgroundColor = color || '';
        }
    });
}


function attachesSelectListeners() {
    document.querySelectorAll('select[name^="modules["]').forEach(select => {
        select.removeEventListener('change', onSelectChange); 
        select.addEventListener('change', onSelectChange);
    });
}


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
            // Revertir al valor anterior
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


document.addEventListener("DOMContentLoaded", () => {
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
});
