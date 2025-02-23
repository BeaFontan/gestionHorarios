document.addEventListener("DOMContentLoaded", function () {
    const cicloSelect = document.getElementById("ciclo");
    const cursoSelect = document.getElementById("curso");
    const selectElements = document.querySelectorAll('select[name^="modules"]');

    // ✅ Activar las opciones de curso solo cuando se selecciona un ciclo
    cicloSelect.addEventListener("change", function () {
        if (cicloSelect.value) {
            cursoSelect.disabled = false;
            cursoSelect.querySelectorAll("option").forEach(option => {
                if (option.value) {
                    option.removeAttribute("disabled");
                }
            });
        } else {
            cursoSelect.disabled = true;
            cursoSelect.querySelectorAll("option").forEach(option => {
                if (option.value) {
                    option.setAttribute("disabled", "true");
                }
            });
        }
        actualizarModulos();
    });

    cursoSelect.addEventListener("change", actualizarModulos);

    function actualizarModulos() {
        let ciclo = cicloSelect.value;
        let curso = cursoSelect.value;

        if (ciclo && curso) {
            let formData = new FormData();
            formData.append("ciclo", ciclo);
            formData.append("curso", curso);

            fetch("../functions/modules/get_modules.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let selectsModulo = document.querySelectorAll(".dropdownModulo select");
                selectsModulo.forEach(select => {
                    select.innerHTML = '<option value="">Selecciona Módulo</option>';
                    data.forEach(modulo => {
                        let option = document.createElement("option");
                        option.value = modulo.id;
                        option.textContent = modulo.name;
                        option.setAttribute("data-color", modulo.color);
                        option.setAttribute("data-max-sessions", modulo.sessions_number);
                        select.appendChild(option);
                    });
                });
            })
            .catch(error => console.error("Error al obtener módulos:", error));
        }
    }

    // ✅ Validación del número máximo de sesiones y aplicación de color
    selectElements.forEach(select => {
        select.addEventListener("change", function () {
            const selectedModule = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const td = this.closest("td");

            if (selectedModule) {
                const maxSessions = parseInt(selectedOption.getAttribute("data-max-sessions")) || 0;
                const color = selectedOption.getAttribute("data-color") || "";

                // Aplicar color correctamente
                if (color) {
                    td.style.backgroundColor = color;
                    this.style.backgroundColor = color;
                    this.style.color = "white";
                } else {
                    td.style.backgroundColor = "";
                    this.style.backgroundColor = "";
                    this.style.color = "black";
                }

                // Contar cuántas veces está seleccionado en la tabla
                const allSelects = document.querySelectorAll('select[name^="modules"]');
                let count = 0;
                allSelects.forEach(s => {
                    if (s.value === selectedModule) {
                        count++;
                    }
                });

                if (count > maxSessions) {
                    alert(`Este módulo solo puede asignarse a ${maxSessions} sesiones.`);
                    this.value = "";
                    td.style.backgroundColor = "";
                    this.style.backgroundColor = "";
                    this.style.color = "black";
                }
            } else {
                // Restaurar color si se deselecciona
                td.style.backgroundColor = "";
                this.style.backgroundColor = "";
                this.style.color = "black";
            }
        });
    });

    // ✅ Resaltar el enlace activo en el menú lateral
    const menuLinks = document.querySelectorAll(".container-left ul li a");
    const currentPage = window.location.pathname.split("/").pop().split("?")[0];

    menuLinks.forEach(link => {
        const linkPage = link.getAttribute("href").split("/").pop().split("?")[0];
        if (linkPage === currentPage) {
            link.classList.add("active");
        }
        link.addEventListener("click", () => {
            menuLinks.forEach(l => l.classList.remove("active"));
            link.classList.add("active");
        });
    });

    // ✅ Capturar el evento 'input' en el campo de búsqueda
    document.getElementById("buscar").addEventListener("input", function (event) {
        let query = this.value.trim();

        if (query.length > 0) {
            event.preventDefault();
            let formData = new FormData();
            formData.append("txtFindModules", query);

            fetch("../functions/modules/find_modules.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                let resultadosDiv = document.querySelector(".mostrar-modulos");
                resultadosDiv.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(item => {
                        let div = document.createElement("div");
                        div.classList.add("container-user");
                        div.innerHTML = `<div class='row'>
                                            <div class='user-imagen'>
                                                <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                                            </div>
                                            <div class='user-texto'>
                                                <p class='texto-nombre'>${item.name} (${item.module_code})</p>
                                                <p class='texto-ciclo'>${item.course === "first" ? "1º Ciclo Formativo" : "2º Ciclo Formativo"}</p>
                                            </div>
                                        </div>`;
                        resultadosDiv.appendChild(div);
                    });
                } else {
                    resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
                }
            })
            .catch(error => console.error("Error al obtener los resultados:", error));
        }
    });
});
