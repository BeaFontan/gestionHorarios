document.getElementById("buscar").addEventListener("input", function (event) {
  let query = this.value.trim();
  let formData = new FormData();

  if (query.length > 0) {
    formData.append("txtFindVocationalTraining", query);
  }

  fetch("../functions/vocational_trainings/find_vocational_training.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      let resultadosDiv = document.querySelector(".mostrar-ciclos");
      resultadosDiv.innerHTML = "";

      if (data.length > 0) {
        data.forEach((item) => {
          let typeTransform = item.type === "higher" ? "Superior" : "Medio";
          let modalityTransform =
            item.modality === "ordinary"
              ? "Ordinario"
              : item.modality === "modular"
              ? "Modular"
              : "Dual";

          let div = document.createElement("div");
          div.classList.add("container-user");
          div.innerHTML = `
              <div class='row'>
                <div class='user-imagen'>
                  <img src='/images/ciclo.png' class='pic' alt='Ciclo img'>
                </div>
                <div class='user-texto'>
                  <p class='texto-nombre'>${item.course_name}</p>
                  <p class='texto-ciclo'>${item.course_code} - ${modalityTransform} - ${typeTransform}</p>
                </div>

                <div class='user-botones'>
                  <form method='post'>
                    <input type='hidden' name='id' value='${item.id}'>
                    <input type='hidden' name='course_code' value='${item.course_code}'>
                    <input type='hidden' name='course_name' value='${item.course_name}'>
                    <input type='hidden' name='modality' value='${item.modality}'>
                    <input type='hidden' name='type' value='${item.type}'>
                        
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
            `;
          resultadosDiv.appendChild(div);
        });
      } else {
        resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
      }
    })
    .catch((error) => console.error("Error al obtener los resultados:", error));
});
