document.getElementById("buscar").addEventListener("input", function (event) {
  let query = this.value.trim();
  let formData = new FormData();

  if (query.length > 0) {
    formData.append("txtFindModules", query);
  }

  fetch("../functions/modules/find_modules.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      let resultadosDiv = document.querySelector(".mostrar-modulos");
      resultadosDiv.innerHTML = "";

      if (data.length > 0) {
        data.forEach((item) => {
          let courseText = item.course === "first" ? "1º" : "2º";

          let div = document.createElement("div");
          div.classList.add("container-user");
          div.innerHTML = `
              <div class='row'>
                  <div class='user-imagen'>
                    <img src='/images/asignatura.png' class='pic' alt='Asignatura img'>
                  </div>
                  <div class='user-texto'>
                      <p class='texto-nombre'>${item.name} 
                        <span class='color_circle' style='background-color: ${item.color}; display: inline-block; width: 15px; height: 15px; border-radius: 50%; margin-left: 5px;'></span>
                      </p>
                      <p class='texto-ciclo'>${item.professor_name} ${item.professor_first_name} - ${courseText} ${item.course_name}</p>
                      <p class='texto-ciclo' style='font-size: 12px;'>${item.module_code} (${item.module_acronym}) - <strong>Clase:</strong> ${item.classroom} - <strong>Nº sesiones:</strong> ${item.sessions_number}</p>
                  </div>

                  <div class='user-botones'>
                      <form method='post'>
                          <input type='hidden' name='id' value='${item.id}'>
                          <input type='hidden' name='professor_id' value='${item.professor_id}'> 
                          <input type='hidden' name='vocational_training_id' value='${item.vocational_training_id}'>
                          <input type='hidden' name='module_code' value='${item.module_code}'>
                          <input type='hidden' name='module_acronym' value='${item.module_acronym}'>
                          <input type='hidden' name='name' value='${item.name}'>
                          <input type='hidden' name='selectCourse' value='${item.course}'>
                          <input type='hidden' name='sessions_number' value='${item.sessions_number}'>
                          <input type='hidden' name='classroom' value='${item.classroom}'>
                          <input type='hidden' name='color' value='${item.color}'>

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
          resultadosDiv.appendChild(div);
        });
      } else {
        resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
      }
    })
    .catch((error) => console.error("Error al obtener los resultados:", error));
});
