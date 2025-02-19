<?php
session_start();

include_once '../functions/connection.php';
include_once '../functions/administrator/find_vocational_trainings.php';
include_once '../functions/administrator/load_modules.php';



$sql = "SELECT * FROM vocational_trainings";
$stmt = $pdo->query($sql);

// Inicializamos variables
$editVocationalTrainingId = null;
$name = "";


// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editVocationalTrainingId = $_POST['id'];
    $name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $acronym = $_POST['acronym'];
    $course_name = $_POST['course_name'];
    $modality = $_POST['modality'];
    $type = $_POST['type'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <p style="color:red; align-items: center;"><?php echo $_SESSION['mensaxe'];
        unset($_SESSION['mensaxe']); ?></p>
    <?php endif; ?>

    <h2>Ciclos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <div class="container-left">
            <div class="circle">
                <img src="/images/user.png" class="pic" alt="">
            </div>
            <h3><?php echo $_SESSION['user']['name'] ?></h3>
            <p><?php echo $_SESSION['user']['rol'] ?></p>

            <ul>
                <li><a href="administrator_panel.php">ALUMNOS</a></li>
                <li><a href="administrator_vocational_trainings.php">CICLOS</a></li>
                <li><a href="administrator_modules.php">MODULOS</a></li>
                <li><a href="administrator_horarios.php">HORARIOS</a></li>
            </ul>
            <br>
            <a href="../functions/user/close_session.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b></a>
        </div>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                <form method="post" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar ciclo" name="txtFindVocationalTraining">
                </form>

                <!-- Botón de Filtros -->
                <button class="btnFiltrar" type="button" onclick="toggleFilters()"><i class="fa fa-filter" style="margin-right: 5px;" aria-hidden="true"></i> Filtros</button>
            </div>

            <!-- Contenedor de los filtros, inicialmente oculto -->
            <div id="filters" style="display:none;">
                <form id="filter-form">
                    <label for="ciclo">Selecciona Ciclo</label>
                    <select name="ciclo" id="ciclo" onchange="loadModulos(this.value)">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        if ($arrayVocationalTrainings) {
                            foreach ($arrayVocationalTrainings as $ciclo) {
                                echo "<option value='" . $ciclo['vocational_training_id'] . "'>" . $ciclo["course_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <label for="modulo">Selecciona Módulo</label>
                    <select name="modulo" id="modulo">
                        <option value="">Selecciona Módulo</option>
                    </select>
                </form>
            </div>
            <div class="mostrar-ciclos">
                <?php
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $_SESSION["ciclo"] = ['course_name' => $fila['course_name']];

                    $id = $fila['id'];
                    $name = $fila['course_name'];
                    $course_code = $fila['course_code'];
                    $acronym = $fila['acronym'];
                    $course_name = $fila['course_name'];
                    $modality = $fila['modality'];
                    $type = $fila['type'];

                    echo "<div class='container-user'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                                </div>
                                <div class='user-texto'>
                                    <p class='texto-nombre'>$name </p>
                                    <p class='texto-ciclo'>$modality</p>
                                </div>";

                    if ($editVocationalTrainingId == $id) {
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
                            <form action='../functions/vocational_trainings/function_update_vocational_trainings.php' method='post'>
                                <div class='row' style='margin-left: 50px;'>
                                    <input class='inputs-form' type='hidden' name='id' value='$id'>
                                    <input class='inputs-form' type='text' name='txtName' value='$name' required><br>
                                    <input class='inputs-form' type='text' name='txtCourse_code' value='$course_code' placeholder='Código ciclo' required><br>
                                    <input class='inputs-form' type='text' name='txtAcronym' value='$acronym' placeholder='Siglas' ><br>
                                </div>
                                <div class='row' style='margin-left: 50px;'>
                                    <input class='inputs-form' type='text' name='txtCourse_name' value='$course_name' placeholder='Nome' ><br>
                                    <input class='inputs-form' type='text' name='txtModality' value='$modality' placeholder='Modalidade' ><br>
                                    <input class='inputs-form' type='text' name='txtType' value='$type' placeholder='Tipo' required><br>
                                </div>

                                <div style='text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;'>
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
                                <input type='hidden' name='course_code' value='$course_code'>
                                <input type='hidden' name='acronym' value='$acronym'>
                                <input type='hidden' name='course_name' value='$course_name'>
                                <input type='hidden' name='modality' value='$modality'>
                                <input type='hidden' name='type' value='$type'>
                            
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
                        </div>
                        ";
                    }

                    // Botón "Borrar"
                    /*echo "
                    <form method='post' action='../functions/vocational_trainings/functiodelete_vocational_training.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' name='btnDelete'>    
                            <i class='fas fa-trash'></i> 
                        </button>
                    </form>";*/

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="administrator_create_vocational_training.php">
        <button name="btnCreateVocationalTraining" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_vocationalTraining.js"></script>

</body>

</html>