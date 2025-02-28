<?php
session_start();

include_once '../functions/connection.php';
include_once '../functions/administrator/find_vocational_trainings.php';
include_once '../functions/administrator/load_modules.php';

$sql = "SELECT * FROM vocational_trainings";
$stmt = $pdo->query($sql);

// Inicializamos variables
$editVocationalTrainingId = null;
$course_name = "";
$course_code = "";
$modality = "";
$type = "";

// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editVocationalTrainingId = $_POST['id'];
    $course_code = $_POST['course_code'];
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
    <div id="overlay" class="overlay"></div>

    <?php if (isset($_SESSION['mensaxe'])): ?>
        <div class="tooltip-container">
            <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
        </div>
        <?php unset($_SESSION['mensaxe']); ?>
    <?php endif; ?>

    <h2>Ciclos</h2>

    <div class="container">
        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php'); ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div class="container-buscador">
                <input type="text" id="checkMenu" value="0" hidden>
                <button onclick="menu()" class='btn-menu'>    
                    <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
                </button>
                <form method="post" style="all:initial; width: 100%;" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar ciclo" name="txtFindVocationalTraining">
                </form>
            </div>

            <div class="mostrar-ciclos">
                <?php while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                    $_SESSION["ciclo"] = ['course_name' => $fila['course_name']];

                    $id = $fila['id'];
                    $course_name = $fila['course_name'];
                    $course_code = $fila['course_code'];
                    $modality = $fila['modality'];
                    $type = $fila['type'];

                    $typeTransform = ($type === 'higher') ? "Superior" : "Medio";
                    $modalityTransform = ($modality === 'ordinary') ? "Ordinario" : (($modality === 'modular') ? "Modular" : "Dual");
                    ?>

                    <div class='container-user'>
                        <div class='row'>
                            <div class='user-imagen'>
                                <img src='/images/ciclo.png' class='pic' alt='Ciclo img'>
                            </div>
                            <div class='user-texto'>
                                <p class='texto-nombre'><?php echo $course_name; ?></p>
                                <p class='texto-ciclo'><?php echo "$course_code - $modalityTransform - $typeTransform"; ?></p>
                            </div>

                            <?php if ($editVocationalTrainingId == $id): ?>
                                <div class='user-botones'>
                                    <form method='post' action='../functions/administrator/function_delete_user.php'>
                                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                                        <button type='submit' class='btn-delete' name='btnDelete'>    
                                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class='user-editar'>
                                <form action='../functions/vocational_trainings/function_update_vocational_trainings.php' style='all:initial; width: 100%;' method='post'>
                                    <div class='row-edit'>
                                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                                        <input class='inputs-form-2' type='text' name='txtCourse_code' value='<?php echo $course_code; ?>' placeholder='Código ciclo' required><br>
                                        <input class='inputs-form-2' type='text' name='txtCourse_name' value='<?php echo $course_name; ?>' placeholder='Nombre'><br>
                                    </div>

                                    <div class='row-edit'>
                        
                                        <select class='inputs-form-select-2' name="selectModality" id="selectModality" required>
                                            <option value="ordinary" <?= ($modality == 'ordinary') ? 'selected' : ''; ?>>Ordinario</option>
                                            <option value="modular" <?= ($modality == 'modular') ? 'selected' : ''; ?>>Modular</option>
                                            <option value="dual" <?= ($modality == 'dual') ? 'selected' : ''; ?>>Dual</option>
                                        </select>

                            
                                        <select class='inputs-form-select-2' name="selectType" id="selectType" required>
                                            <option value="medium" <?= ($type == 'medium') ? 'selected' : ''; ?>>Medio</option>
                                            <option value="higher" <?= ($type == 'higher') ? 'selected' : ''; ?>>Superior</option>
                                        </select>
                                    </div>

                                    <div class='row-guardar'>
                                        <button type='submit' class='btnActualizar' name='btnSave'>Actualizar</button>
                                    </div>
                                </form>
                            </div>

                            <?php else: ?>
                                <div class='user-botones'>
                                    <form method='post'>
                                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                                        <input type='hidden' name='course_code' value='<?php echo $course_code; ?>'>
                                        <input type='hidden' name='course_name' value='<?php echo $course_name; ?>'>
                                        <input type='hidden' name='modality' value='<?php echo $modality; ?>'>
                                        <input type='hidden' name='type' value='<?php echo $type; ?>'>

                                        <button type='submit' class='btn' name='btnUpdate'>
                                            <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                            <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                        </button>
                                    </form>
                                    <form method='post' action='../functions/vocational_trainings/function_delete_vocational_training.php'>
                                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                                        <button type='submit' class='btn-delete' name='btnDelete'>    
                                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <form action="administrator_create_vocational_training.php">
        <button name="btnCreateVocationalTraining" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_vocationalTraining.js"></script>
    <script src="../js/selector_menu.js"></script>
    <script src="../js/menu.js"></script>

</body>
</html>
