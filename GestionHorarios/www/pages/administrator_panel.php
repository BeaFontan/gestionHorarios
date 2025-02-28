<?php
session_start();

include_once '../functions/connection.php';
include_once '../functions/administrator/find_vocational_trainings.php';
include_once '../functions/administrator/load_modules.php';

$arrayVocationalTrainings = findVocationalTrainings($pdo);
$arrayModules = loadModules($pdo);

$sql = "SELECT * FROM users 
        WHERE rol LIKE 'student'";
$stmt = $pdo->query($sql);



$editUserId = null;
$name = "";
$firstName = "";
$secondName = "";

if (isset($_POST["btnUpdate"])) {
    $editUserId = $_POST["id"];
    $name = $_POST["name"];
    $firstName = $_POST["first_name"];
    $secondName = $_POST["second_name"];
    $phone = $_POST["telephone"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
  
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <input type="text" id="checkMenu" value="0" hidden>
    <button onclick="menu()" class='btn-menu' name=''>    
        <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
    </button>
    
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <div class="tooltip-container">
            <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
        </div>
        <?php unset($_SESSION['mensaxe']); ?>
    <?php endif; ?>

    <h2>Alumnos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div class="container-buscador">
                <form method="post" style="all:initial; width: 100%;" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar alumno" name="txtFindUser">
                </form>
                <button id="filter-button" class="btnFiltrar" onclick="toggleFilters()">Filtro</button>
            </div>

             <div id="filters" style="display:none;">
                <form id="filter-form">
                    <select name="ciclo" class="drpCiclo" id="ciclo" onchange="loadModulos(this.value)">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        if ($arrayVocationalTrainings) {
                            foreach ($arrayVocationalTrainings as $ciclo) {
                                echo "<option value='" . $ciclo['id'] . "'>" . $ciclo["course_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </form>
            </div>


            <div class="mostrar-users">
                <?php
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $fila['id'];
                    $name = $fila['name'];
                    $firstName = $fila['first_name'];
                    $secondName = $fila['second_name'];
                    $telephone = $fila['telephone'];
                    $dni = $fila['dni'];
                    $email = $fila['email'];

                    echo "<div class='container-user'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/user.png' class='pic' alt='Usuario img'>
                                </div>
                              <div class='user-texto'>
                                    <p class='texto-nombre'>$name $firstName $secondName</p>
                                    <p class='texto-ciclo'><strong>Dni: </strong>$dni</p>
                                    <p class='texto-ciclo' style='font-size: 14px;'><strong>Email:</strong> $email </strong>- <strong>Teléfono:</strong> $telephone</p>

                                </div>";

                        if ($editUserId == $id) {
                        echo "
                                <div class='user-botones'>
                                    <form method='post' action='../functions/administrator/function_delete_user.php'>
                                        <input type='hidden' name='id' value='$id'>
                                        <button type='submit' class='btn-delete' name='btnDelete'>    
                                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <div class='user-editar'>
                            <form action='../functions/administrator/function_update_user.php' style='all:initial; width: 100%;' method='post'>
                                <div class='row-edit' style='margin-left: 3.5%;'>
                                    <input type='hidden' class='inputs-form' name='id' value='$id'>
                                    <input type='text' class='inputs-form' name='txtName' value='$name' maxlength='50' required><br>
                                    <input type='text' class='inputs-form' name='txtFirstName' value='$firstName' placeholder='Primeiro Apelido'  maxlength='50' required><br>
                                    <input type='text' class='inputs-form' name='txtSecondName' value='$secondName' placeholder='Segundo Apelido' maxlength='50'><br>
                                </div>
                                <div class='row-edit' style='margin-left: 3.5%;'>
                                    <input type='number' class='inputs-form' name='txtTelephone' value='$telephone' placeholder='Teléfono' ><br>
                                    <input type='email' class='inputs-form' name='txtEmail' value='$email' placeholder='Email' maxlength='100' ><br>
                                    <input type='text' class='inputs-form' name='txtDNI' value='$dni' placeholder='DNI' maxlength='9' required><br>
                                </div>

                                <div class='row-guardar'>
                                    <button type='submit' class='btnActualizar' name='btnSave'>Actualizar</button>
                                </div>
                            </form>
                        </div>
                        ";
                        } else {
                        echo "
                        <div class='user-botones'>
                            <form method='post'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='first_name' value='$firstName'>
                                <input type='hidden' name='second_name' value='$secondName'>
                                <input type='hidden' name='email' value='$email'>
                                <input type='hidden' name='telephone' value='$telephone'>
                                <input type='hidden' name='dni' value='$dni'>
                            
                                
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>
                            <form method='post' action='../functions/administrator/function_delete_user.php'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' class='btn-delete' name='btnDelete'>    
                                    <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                    <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                </button>
                            </form>
                        </div>
                    </div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="create_user.php">
        <button name="btnCreateUser" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_user.js"></script>
    <script src="../js/selector_menu.js"></script>
    <script src="../js/menu.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mantener visible el filtro si se activó
        document.getElementById("filters").style.display = "none";

        // Capturar el evento de cambio en el select
        document.getElementById("ciclo").addEventListener("change", function () {
            let cicloId = this.value;
            let formData = new FormData();
            formData.append("ciclo", cicloId);

            fetch("../functions/administrator/get_students.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector(".mostrar-users").innerHTML = data;
                document.getElementById("filters").style.display = "block";
            })
            .catch(error => console.error("Error:", error));
        });
    });

    function toggleFilters() {
    var filtersDiv = document.getElementById("filters");
    var currentDisplay = window.getComputedStyle(filtersDiv).display;

    if (currentDisplay === "none") {
        filtersDiv.style.display = "block";
    } else {
        filtersDiv.style.display = "none";
    }
}
</script>

</body>

</html>