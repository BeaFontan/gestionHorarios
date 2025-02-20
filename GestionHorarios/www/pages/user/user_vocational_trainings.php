<?php
session_start();

include_once '../../functions/connection.php';
include_once '../../functions/administrator/find_vocational_trainings.php';
include_once '../../functions/administrator/load_modules.php';



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
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .ciclo-card {
            display: flex;
            align-items: center;
            width: 400px;
            padding: 10px;
            border: 2px solid #0088cc;
            border-radius: 10px;
            position: relative;
            background-color: white;
            justify-content: space-between;
        }

        .ciclo-card:hover {
            background-color: #f0f8ff;
        }

        .ciclo-card img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .ciclo-card .texto {
            flex-grow: 1;
        }

        .ciclo-card .texto p {
            margin: 0;
        }

        /* Ocultar el checkbox nativo */
        .ciclo-card input {
            display: none;
        }

        /* Estilo del icono de + y - */
        .toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            font-size: 20px;
            font-weight: bold;
            color: white;
            background-color: green;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Cuando el checkbox está marcado, cambia el color a rojo */
        .ciclo-card input:checked + .toggle-icon {
            background-color: red;
        }

        .save-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #0088cc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .save-btn:hover {
            background-color: #005f99;
        }
    </style>
</head>

<body>
<?php if (isset($_SESSION['mensaxe'])): ?>
    <div class="tooltip-container">
        <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
    </div>
    <?php unset($_SESSION['mensaxe']); ?>
<?php endif; ?>

    <h2>Ciclos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('../partials/container_left.php') ?>

        <!-- Contenedor derecho -->
     
        <div class="container-right">
            <div class="mostrar-ciclos">

                <form action="guardar_ciclos.php" method="POST">
                    <?php 
                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): 
                        $_SESSION["ciclo"] = ['course_name' => $fila['course_name']];
                    ?>
                        <div class="ciclo-card">
                            <img src="/images/asignatura.png" alt="Icono">
                            <div class="texto">
                                <p><strong><?= htmlspecialchars($fila['course_name']) ?></strong></p>
                                <p><?= htmlspecialchars($fila['modality']) ?></p>
                            </div>
                            <!-- Checkbox oculto -->
                            <input type="checkbox" id="ciclo<?= $fila['id'] ?>" name="ciclos[]" value="<?= $fila['id'] ?>" onchange="toggleIcon(this)">
                            <!-- Label que funciona como checkbox -->
                            <label for="ciclo<?= $fila['id'] ?>" class="toggle-icon" id="icono<?= $fila['id'] ?>">+</label>
                        </div>
                    <?php endwhile; ?>
                    <button type="submit" class="save-btn">Guardar</button>
                </form>
             </div>  
        </div>
    </div>
    
<script>
    function toggleIcon(checkbox) {
        let checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');

        if (checkedBoxes.length > 2) {
            checkbox.checked = false; // Desmarca el último intento
            return;
        }

        let icon = document.getElementById("icono" + checkbox.id.replace("ciclo", ""));
        icon.textContent = checkbox.checked ? "-" : "+";
    }
</script>

            </div>
            
        </div>
    </div>

    <form action="administrator_create_vocational_training.php">
        <button name="btnCreateVocationalTraining" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_vocationalTraining.js"></script>
    <script src="../js/selector_menu.js"></script>

</body>

</html>

