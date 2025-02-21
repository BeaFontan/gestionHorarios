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
    <title>Panel Estudiandte</title>
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
    <style>
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
/* Ocultar el checkbox pero mantener su funcionalidad */
input[type="checkbox"] {
    position: absolute;
    width: 50px;
    height: 50px;
    opacity: 0; /* Oculta el checkbox pero sigue siendo clickeable */
    cursor: pointer;
}

/* Hacer que el ícono actúe como checkbox */
.toggle-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    font-size: 25px;
    font-weight: bold;
    color: white;
    background-color: #468dae;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.5s ease-in-out, transform 0.2s ease-in-out;
    margin-top: 25px;
}

/* Cuando el checkbox está marcado, cambiar el color a rojo con transición */
input[type="checkbox"]:checked + .toggle-icon {
    background-color: #c12b2e;
    transform: scale(1); /* Efecto ligero de escala */
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
        <div class="container-rigth">
            <div class="mostrar-ciclos">
                <form action="guardar_ciclos.php" method="POST">
                    <?php 
                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): 
                        $_SESSION["ciclo"] = ['course_name' => $fila['course_name']];
                    ?>
                        <div class='container-user container-check'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                                </div>
                                <div class='user-texto'>
                                    <p class='texto-nombre'><strong><?= htmlspecialchars($fila['course_name']) ?></strong></p>
                                    <p class='texto-ciclo'><?= htmlspecialchars($fila['modality']) ?></p>
                                </div>
                                <!-- Checkbox oculto -->
                                <input type="checkbox" id="ciclo<?= $fila['id'] ?>" name="ciclos[]" value="<?= $fila['id'] ?>" onchange="toggleIcon(this)">
                                <!-- Label que funciona como checkbox -->
                                <label for="ciclo<?= $fila['id'] ?>" class="toggle-icon" id="icono<?= $fila['id'] ?>">+</label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <button type="submit" class="btnActualizar">Guardar</button>
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

</body>

</html>

