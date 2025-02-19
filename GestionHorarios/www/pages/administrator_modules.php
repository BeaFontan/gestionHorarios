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

// Inicializamos variables
$editModules = null;
$name = "";


// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editModules = $_POST['id'];
    $professor_id = $_POST["professor_id"];
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
</head>

<body>
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <p style="color:red; align-items: center;"><?php echo $_SESSION['mensaxe'];
                                                    unset($_SESSION['mensaxe']); ?></p>
    <?php endif; ?>

    <h2>Módulos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <form method="post" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                <input type="text" id="buscar" placeholder="Buscar módulo" name="txtFindModules">
            </form>

            <!-- Botón de Filtros -->
            <button type="button" onclick="toggleFilters()">Filtros</button>

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

                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id = $fila['id'];
                    $professor_id = $fila['professor_id'];
                    $vocational_training_id = $fila['vocational_training_id'];
                    $module_code = $fila['module_code'];
                    $name = $fila['name'];
                    $course = $fila['course'];
                    $sessions_number = $fila['sessions_number'];
                    $course_name = "";

                    echo "<div class='container-user'>";
                    echo "<div class='circle'></div>";
                    echo "<p>$name</p>";

                    if (!empty($arrayProfessors)) {
                        foreach ($arrayProfessors as $professor) {
                            if ($professor["id"] === $professor_id) {
                                if ($course == "first") {

                                    echo '<p>'.$professor["name"]. ' '. $professor["first_name"].' - 1º Ciclo formativo</p>';
                                } else {
                                    echo '<p>'.$professor["name"]. ' '. $professor["first_name"].' - 2º Ciclo formativo</p>';
                                }
                            }
                        }
                    } 

                    if ($editModules == $id) {
                        echo "
                        <form action='../functions/modules/function_update_modules.php' method='post'>
                            <input type='hidden' name='id' value='$id'>

                            <select name='selectProfessor' id='selectProfessor' required onchange='updateHiddenProfessor()'>";
                            if (!empty($arrayProfessors)) {
                                foreach ($arrayProfessors as $professor) {
                                    echo "<option value='{$professor['id']}'>" . htmlspecialchars($professor['name'] . ' ' . $professor['first_name']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay profesores disponibles</option>";
                            }
                            echo "</select>

                            <input type='text' name='txtModule_code' value='$module_code' required><br>
                    
                            <input type='text' name='txtModule_name' value='$name' placeholder='Nome'><br>

                            <select name='selectCourse' >
                                <option value='first' " . (isset($selectCourse) && $selectCourse == 'first' ? 'selected' : '') . ">Primeiro</option>
                                <option value='second' " . (isset($selectCourse) && $selectCourse == 'second' ? 'selected' : '') . ">Segundo</option>
                            </select>

                            <input type='text' name='txtSessions_number' value='$sessions_number' placeholder='Modalidade'><br>
                    
                            <button type='submit' name='btnSave'>Actualizar</button>
                        </form>
                        ";
                    } else {
                        echo "
                        <form method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='hidden' name='professor_id' id='professor_id'>
                            <input type='hidden' name='module_code' value='$module_code'>
                            <input type='hidden' name='name' value='$name'>
                            <input type='hidden' name='selectCourse' value='" . (isset($selectCourse) ? $selectCourse : '') . "'>  <!-- Asegúrate de pasar el valor aquí -->
                            <input type='hidden' name='sessions_number' value='$sessions_number'>
                    
                            <button type='submit' name='btnUpdate'>
                                <i class='fas fa-edit'></i>
                            </button>
                        </form>
                    ";
                    }

                    // Botón "Borrar"
                    echo "
                    <form method='post' action='../functions/modules/function_delete_modules.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' name='btnDelete'>    
                            <i class='fas fa-trash'></i> 
                        </button>
                    </form>";

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
    </script>

    <script src="../js/find_modules.js"></script>
</body>

</html>