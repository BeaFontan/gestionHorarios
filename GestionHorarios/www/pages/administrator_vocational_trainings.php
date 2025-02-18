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
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <form method="post" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                <input type="text" id="buscar" placeholder="Buscar alumno" name="txtFindVocationalTraining">
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


                    echo "<div class='container-user'>";
                    echo "<div class='circle'></div>";
                    echo "<p>$name</p>";
                    echo "<p>$modality</p>";

                    if ($editVocationalTrainingId == $id) {
                        echo "
                        <form action='../functions/vocational_trainings/function_update_vocational_trainings.php' method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='text' name='txtName' value='$name' required><br>
                            <input type='text' name='txtCourse_code' value='$course_code' placeholder='Código ciclo' required><br>
                            <input type='text' name='txtAcronym' value='$acronym' placeholder='Siglas' ><br>
                            <input type='text' name='txtCourse_name' value='$course_name' placeholder='Nome' ><br>
                            <input type='text' name='txtModality' value='$modality' placeholder='Modalidade' ><br>
                            <input type='text' name='txtType' value='$type' placeholder='Tipo' required><br>

                            <button type='submit' name='btnSave'>Actualizar</button>
                        </form>
                        ";
                    } else {
                        echo "
                        <form method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='hidden' name='name' value='$name'>
                            <input type='hidden' name='course_code' value='$course_code'>
                            <input type='hidden' name='acronym' value='$acronym'>
                            <input type='hidden' name='course_name' value='$course_name'>
                            <input type='hidden' name='modality' value='$modality'>
                            <input type='hidden' name='type' value='$type'>
                        
                            <button type='submit' name='btnUpdate'>
                                <i class='fas fa-edit'></i> 
                            </button>
                        </form>
                        ";
                    }

                    // Botón "Borrar"
                    echo "
                    <form method='post' action='../functions/vocational_trainings/functiodelete_vocational_training.php'>
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

    <form action="administrator_create_vocational_training.php">
        <button name="btnCreateVocationalTraining" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_vocationalTraining.js"></script>

</body>

</html>