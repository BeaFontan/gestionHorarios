<?php
session_start();

include_once '../functions/connection.php';
//include_once '../functions/modules/find_modules.php';

$sql = "SELECT * FROM modules";
$stmt = $pdo->query($sql);

function getProfessors($pdo)
{
    try {
        $query = $pdo->query("Select * from professors");
        $query->execute();
        return $query->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro listando profesores" . $e->getMessage();
    }
}


function getVocationalTrainings($pdo)
{
    try {
        $query = $pdo->query("Select * from vocational_trainings");
        $query->execute();
        return $query->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro listando Ciblos" . $e->getMessage();
    }
}

// Inicializamos variables
$editModules = null;
$name = "";


// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editModules = $_POST['id'];
    $professor_id = $_POST['professor_id'];
    $vocational_training_id = $_POST['vocational_training_id'];
    $module_code = $_POST['module_code'];
    $name = $_POST['name'];
    $selectCourse = $_POST['selectCourse'];
    $sessions_number = $_POST['sessions_number'];
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

    <style>


</style>
</head>

<body>
<?php if (isset($_SESSION['mensaxe'])): ?>
    <div class="tooltip-container">
        <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
    </div>
    <?php unset($_SESSION['mensaxe']); ?>
<?php endif; ?>

    <h2>Módulos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                <form method="post" style="all:initial;" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar módulo" name="txtFindModules">
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

            <div class="mostrar-modulos">
                <?php
                $arrayProfessors = getProfessors($pdo);

                $arrayVocationalTrainings = getVocationalTrainings($pdo);

                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id = $fila['id'];
                    $professor_id = $fila['professor_id'];
                    $vocational_training_id = $fila['vocational_training_id'];
                    $module_code = $fila['module_code'];
                    $name = $fila['name'];
                    $course = $fila['course'];
                    $sessions_number = $fila['sessions_number'];
                    $course_name = "";

                    echo "<div class='container-user'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/asignatura.png' class='pic' alt='Asignatura img'>
                                </div>
                                <div class='user-texto'>
                                    <p class='texto-nombre'>$name </p>";

                    if (!empty($arrayProfessors)) {
                        foreach ($arrayProfessors as $professor) {
                            foreach ($arrayVocationalTrainings as $vocationalTrining) {
                                if ($professor["id"] === $professor_id && $vocationalTrining["id"] === $vocational_training_id) {
                                    if ($course == "first" ) {
    
                                        echo "<p class='texto-ciclo'>".$professor["name"]. ' '. $professor["first_name"].' - 1º '.$vocationalTrining["course_name"].'</p>';
                                    } else {
                                        echo "<p class='texto-ciclo'>".$professor["name"]. ' '. $professor["first_name"].' - 2º ' .$vocationalTrining["course_name"].'</p>';
                                    }
                                }
                            }
                        }
                    }

                    echo "</div>";

                    if ($editModules == $id) {
                        echo "
                                <div class='user-botones'>
                                    <form method='post' action='../functions/modules/function_delete_modules.php'>
                                        <input type='hidden' name='id' value='$id'>
                                        <button type='submit' class='btn-delete' name='btnDelete'>    
                                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <div class='user-editar'>
                            <form action='../functions/modules/function_update_modules.php' style='all:initial; width: 100%;' method='post'>
                                <input type='hidden' name='id' value='$id'>

                                <div class='row-edit' style='margin-left: 3.5%;'>
                                    <input class='inputs-form' type='text' name='txtModule_code' value='$module_code' required><br>
                                    <input class='inputs-form' type='text' name='txtModule_name' value='$name' placeholder='Nome'><br>
                                    <input class='inputs-form' type='text' name='txtSessions_number' value='$sessions_number' placeholder='Modalidade'><br>
                                </div>
                            
                                <div class='row-edit' style='margin-left: 3.5%;'>
                                    <select class='inputs-form-select' name='selectProfessor' id='selectProfessor' required onchange='updateHiddenProfessor()'>";
                                        if (!empty($arrayProfessors)) {
                                            foreach ($arrayProfessors as $professor) {
                                                echo "<option value='{$professor['id']}'>" . htmlspecialchars($professor['name'] . ' ' . $professor['first_name']) . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No hay profesores disponibles</option>";
                                        }
                                    echo "</select>

                                    <select class='inputs-form-select' name='selectVocationalTraining' id='selectVocationalTraining' required onchange='updateHiddenVocationalTrining()'>";
                                        if (!empty($arrayVocationalTrainings)) {
                                            foreach ($arrayVocationalTrainings as $vocationalTrining) {
                                                echo "<option value='{$vocationalTrining['id']}'>" . htmlspecialchars($vocationalTrining['course_name']) . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No hay ciclos disponibles</option>";
                                        }
                                    echo "</select>

                                    <select class='inputs-form-select' name='selectCourse' >
                                        <option value='first' " . (isset($selectCourse) && $selectCourse == 'first' ? 'selected' : '') . ">Primeiro</option>
                                        <option value='second' " . (isset($selectCourse) && $selectCourse == 'second' ? 'selected' : '') . ">Segundo</option>
                                    </select>
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
                                <input type='hidden' name='professor_id' id='professor_id'>
                                <input type='hidden' name='vocational_training_id' id='vocational_training_id'>
                                <input type='hidden' name='module_code' value='$module_code'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='selectCourse' value='" . (isset($selectCourse) ? $selectCourse : '') . "'>  <!-- Asegúrate de pasar el valor aquí -->
                                <input type='hidden' name='sessions_number' value='$sessions_number'>
                        
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>

                            <form method='post' action='../functions/modules/function_delete_modules.php'>
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
                    <form method='post' action='../functions/modules/function_delete_modules.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' class='btn-delete' name='btnDelete'>    
                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                        </button>
                    </form>";*/

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="administrator_create_module.php">
        <button name="btnFormCreateModule" class="btnCreateUser">+</button>
    </form>

    <script>
        function updateHiddenProfessor() {
            let select = document.getElementById("selectProfessor");
            let hiddenInput = document.getElementById("professor_id");

            hiddenInput.value = select.value;
        }

        function updateHiddenVocationalTrining() {
            let select = document.getElementById("selectVocationalTraining");
            let hiddenInput = document.getElementById("vocational_training_id");

            hiddenInput.value = select.value;
        }
    </script>

    <script src="../js/find_modules.js"></script>
    <script src="../js/selector_menu.js"></script>
</body>

</html>