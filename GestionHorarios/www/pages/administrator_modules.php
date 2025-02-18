<?php
session_start();

include_once '../functions/connection.php';
//include_once '../functions/modules/find_modules.php';


$sql = "SELECT * FROM modules";
$stmt = $pdo->query($sql);

// Inicializamos variables
$editModules = null;
$name = "";


// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editModules = $_POST['id'];
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
    <h2>Módulos</h2>
    <div class="container">

        <?php
        if (isset($_SESSION['mensaxe'])) {
            "<p>" . $_SESSION['mensaxe'] . "</p>";
        }
        ?>
        <!-- Contenedor izquierdo -->
        <div class="container-left">
            <div class="circle"></div>
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
                    if ($course == "first") {
                        echo "<p>1º Ciclo formativo</p>";
                    } else {
                        echo "<p>2º Ciclo formativo</p>";
                    }

                    if ($editModules == $id) {
                        echo "
                        <form action='../functions/modules/function_update_modules.php' method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='text' name='txtModule_code' value='$module_code' required><br>
                            <input type='text' name='txtName' value='$name' placeholder='Código ciclo' required><br>
                    
                            <select name='selectCourse' required>
                                <option value='first' " . (isset($selectCourse) && $selectCourse == 'first' ? 'selected' : '') . ">Primeiro</option>
                                <option value='second' " . (isset($selectCourse) && $selectCourse == 'second' ? 'selected' : '') . ">Segundo</option>
                            </select>
                            
                            <input type='text' name='txtCourse_name' value='$name' placeholder='Nome'><br>
                            <input type='text' name='txtSessions_number' value='$sessions_number' placeholder='Modalidade'><br>
                    
                            <button type='submit' name='btnSave'>Actualizar</button>
                        </form>
                    ";
                    } else {
                        echo "
                        <form method='post'>
                            <input type='hidden' name='id' value='$id'>
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