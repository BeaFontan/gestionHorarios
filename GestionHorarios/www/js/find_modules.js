// Capturamos el evento 'input' en el campo de búsqueda
document.getElementById("buscar").addEventListener("input", function (event) {
  let query = this.value.trim(); // Obtenemos el valor ingresado en el campo de búsqueda

  if (query.length > 0) {
    event.preventDefault(); // Evitamos que el formulario se envíe de manera tradicional

    // Creamos un objeto FormData para enviar la búsqueda por POST
    let formData = new FormData();
    formData.append("txtFindModules", query); // Agregamos el texto de búsqueda

    fetch("../functions/modules/find_modules.php", {
      method: "POST",
      body: formData, // Enviamos los datos del formulario
    })
      .then((response) => response.json()) // Esperamos una respuesta JSON
      .then((data) => {
        let resultadosDiv = document.querySelector(".mostrar-modulos"); // Seleccionamos el contenedor de resultados
        resultadosDiv.innerHTML = ""; // Limpiamos los resultados anteriores

        if (data.length > 0) {
          // Si se encontraron resultados, los mostramos
          data.forEach((item) => {
            let div = document.createElement("div");
            div.classList.add("container-user");
            div.innerHTML = `
                <div class='row'>
                    <div class='user-imagen'>
                      <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                    </div>
                    <div class='user-texto'>
                        <p class='texto-nombre'>${item.name} (${item.module_code})</p>
                        <p class='texto-ciclo'>${item.course === "first" ? "1º Ciclo Formativo" : "2º Ciclo Formativo"}</p>
                    </div>

                    <div class='user-botones'>
                        <form method='post'>
                            <input type='hidden' name='id' value='${item.id}'>
                            <input type='hidden' name='module_code' value='${item.module_code}'>
                            <input type='hidden' name='name' value='${item.name}'>
                            <input type='hidden' name='selectCourse' value='${item.course}'>
                            <input type='hidden' name='sessions_number' value='${item.sessions_number}'>
                            
                            <button type='submit' class='btn' name='btnUpdate'>
                                <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                            </button>
                        </form>
                        <form method='post' action='../functions/modules/function_delete_modules.php'>
                            <input type='hidden' name='id' value='${item.id}'>
                            <button type='submit' class='btn-delete' name='btnDelete'>    
                                <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                            </button>
                        </form>
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
      .catch((error) => console.error("Error al obtener los resultados:", error));
  } else {
    // Si el campo de búsqueda está vacío, realizamos una solicitud para obtener todos los módulos
    fetch("../functions/modules/find_modules.php", {
      method: "POST",
      body: new FormData(), // Enviamos un FormData vacío para obtener todos los registros
    })
      .then((response) => response.json()) // Esperamos una respuesta JSON
      .then((data) => {
        let resultadosDiv = document.querySelector(".mostrar-modulos"); // Seleccionamos el contenedor de resultados
        resultadosDiv.innerHTML = ""; // Limpiamos los resultados anteriores

        if (data.length > 0) {
          // Si se encontraron resultados, los mostramos
          data.forEach((item) => {
            let div = document.createElement("div");
            div.classList.add("container-user");
            div.innerHTML = `
                <div class='row'>
                    <div class='user-imagen'>
                      <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                    </div>
                    <div class='user-texto'>
                        <p class='texto-nombre'>${item.name} (${item.module_code})</p>
                        <p class='texto-ciclo'>${item.course === "first" ? "1º Ciclo Formativo" : "2º Ciclo Formativo"}</p>
                    </div>

                    <div class='user-botones'>
                        <form method='post'>
                            <input type='hidden' name='id' value='${item.id}'>
                            <input type='hidden' name='module_code' value='${item.module_code}'>
                            <input type='hidden' name='name' value='${item.name}'>
                            <input type='hidden' name='selectCourse' value='${item.course}'>
                            <input type='hidden' name='sessions_number' value='${item.sessions_number}'>
                            
                            <button type='submit' class='btn' name='btnUpdate'>
                                <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                            </button>
                        </form>
                        <form method='post' action='../functions/modules/function_delete_modules.php'>
                            <input type='hidden' name='id' value='${item.id}'>
                            <button type='submit' class='btn-delete' name='btnDelete'>    
                                <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                            </button>
                        </form>
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
      .catch((error) => console.error("Error al obtener los resultados:", error));
  }
});
