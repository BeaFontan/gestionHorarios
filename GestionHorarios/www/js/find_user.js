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
                        <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/user.png' class='pic' alt='Usuario img'>
                                </div>
                                <div class='user-texto'>
                                    <p class='texto-nombre'>${item.name} ${item.first_name} </p>
                                    <p class='texto-ciclo'>ciclo</p>
                                </div>

                        <div class='user-botones'>
                            <form method='post'>
                                <input type='hidden' name='id' value='${item.id}'>
                                <input type='hidden' name='name' value='${item.name}'>
                                <input type='hidden' name='first_name' value='${item.first_name}'>
                                <input type='hidden' name='second_name' value='${item.second_name}'>
                                <input type='hidden' name='email' value='${item.email}'>
                                <input type='hidden' name='telephone' value='${item.telephone}'>
                                <input type='hidden' name='dni' value='${item.dni}'>
                            
                                
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>
                            <form method='post' action='../functions/administrator/function_delete_user.php'>
                                <input type='hidden' name='id' value='${item.id}'>
                                <button type='submit' class='btn-delete' name='btnDelete'>    
                                    <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                    <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                </button>
                            </form>
                        </div>
                    </div>
                    </div>
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