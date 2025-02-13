// Capturamos el evento 'input' en el campo de búsqueda
document.getElementById("buscar").addEventListener("input", function(event) {
    let query = this.value.trim(); // Obtenemos el valor ingresado en el campo de búsqueda
  
    if (query.length > 0) {
        event.preventDefault(); // Evitamos que el formulario se envíe de manera tradicional
  
        // Creamos un objeto FormData para enviar la búsqueda por POST
        let formData = new FormData();
        formData.append("txtFindUser", query); // Agregamos el texto de búsqueda
  
        fetch("../functions/administrator/function_panel_administrator.php", {
            method: "POST",
            body: formData // Enviamos los datos del formulario
        })
        .then(response => response.json()) // Esperamos una respuesta JSON
        .then(data => {
            let resultadosDiv = document.querySelector(".mostrar-users"); // Seleccionamos el contenedor de resultados
            resultadosDiv.innerHTML = ""; // Limpiamos los resultados anteriores
  
            if (data.length > 0) {
                // Si se encontraron resultados, los mostramos
                data.forEach(item => {
                    let div = document.createElement("div");
                    div.classList.add("container-user");
                    div.innerHTML = `
                        <div class="circle"></div>
                        <p>${item.name} ${item.first_name}</p>
                        <p>ciclo</p>
                        <form>
                            <button name="btnUpdate">editar</button>
                            <button name="btnDelete">borrar</button>
                        </form>
                    `;
                    resultadosDiv.appendChild(div); // Agregamos el div con el resultado
                });
            } else {
                // Si no se encuentran resultados, mostramos un mensaje
                resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
            }
        })
        .catch(error => console.error('Error al obtener los resultados:', error));
    } else {
        // Si el campo de búsqueda está vacío, limpiamos los resultados
        document.querySelector(".mostrar-users").innerHTML = "";
    }
  });