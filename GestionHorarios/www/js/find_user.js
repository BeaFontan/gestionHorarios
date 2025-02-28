document.getElementById("buscar").addEventListener("input", function(event) {
  let query = this.value.trim(); 
  let formData = new FormData();

  if (query.length > 0) {
      formData.append("txtFindUser", query);
  }

  fetch("../functions/administrator/function_panel_administrator.php", {
      method: "POST",
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      let resultadosDiv = document.querySelector(".mostrar-users");
      resultadosDiv.innerHTML = ""; 
      if (data.length > 0) {
          data.forEach(item => {
              let div = document.createElement("div");
              div.classList.add("container-user");


              let fullName = `${item.name} ${item.first_name}`;
              if (item.second_name) {
                  fullName += ` ${item.second_name}`;
              }

              div.innerHTML = `
                  <div class='row'>
                      <div class='user-imagen'>
                          <img src='/images/user.png' class='pic' alt='Usuario img'>
                      </div>
                      <div class='user-texto'>
                          <p class='texto-nombre'>${fullName}</p>
                          <p class='texto-ciclo'><strong>DNI:</strong> ${item.dni}</p>
                          <p class='texto-ciclo' style='font-size: 14px;'>
                              <strong>Email:</strong> ${item.email} - <strong>Teléfono:</strong> ${item.telephone}
                          </p>
                      </div>
                      
                      <div class='user-botones'>
                          <form method='post'>
                              <input type='hidden' name='id' value='${item.id}'>
                              <input type='hidden' name='name' value='${item.name}'>
                              <input type='hidden' name='first_name' value='${item.first_name}'>
                              <input type='hidden' name='second_name' value='${item.second_name || ''}'>
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
              `;
              resultadosDiv.appendChild(div);
          });
      } else {
          resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
      }
  })
  .catch(error => console.error("Error al obtener los resultados:", error));
});


document.getElementById("buscar").addEventListener("blur", function () {
  if (this.value.trim() === "") {
      fetch("../functions/administrator/function_panel_administrator.php", {
          method: "POST",
          body: new FormData(),
      })
      .then(response => response.json())
      .then(data => {
          let resultadosDiv = document.querySelector(".mostrar-users");
          resultadosDiv.innerHTML = "";

          if (data.length > 0) {
              data.forEach(item => {
                  let div = document.createElement("div");
                  div.classList.add("container-user");

                  let fullName = `${item.name} ${item.first_name}`;
                  if (item.second_name) {
                      fullName += ` ${item.second_name}`;
                  }

                  div.innerHTML = `
                      <div class='row'>
                          <div class='user-imagen'>
                              <img src='/images/user.png' class='pic' alt='Usuario img'>
                          </div>
                          <div class='user-texto'>
                              <p class='texto-nombre'>${fullName}</p>
                              <p class='texto-ciclo'><strong>DNI:</strong> ${item.dni}</p>
                              <p class='texto-ciclo' style='font-size: 14px;'>
                                  <strong>Email:</strong> ${item.email} - <strong>Teléfono:</strong> ${item.telephone}
                              </p>
                          </div>

                          <div class='user-botones'>
                              <form method='post'>
                                  <input type='hidden' name='id' value='${item.id}'>
                                  <input type='hidden' name='name' value='${item.name}'>
                                  <input type='hidden' name='first_name' value='${item.first_name}'>
                                  <input type='hidden' name='second_name' value='${item.second_name || ''}'>
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
                  `;
                  resultadosDiv.appendChild(div);
              });
          } else {
              resultadosDiv.innerHTML = "<p>No se encontraron resultados.</p>";
          }
      })
      .catch(error => console.error("Error al obtener los resultados:", error));
  }
});
