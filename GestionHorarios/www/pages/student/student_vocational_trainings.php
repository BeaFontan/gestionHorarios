<?php
session_start();
include_once '../../functions/connection.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Obtener los ciclos que tiene el usuario
$userId = $_SESSION['user']['id'];
$sqlUserCiclos = "SELECT vocational_training_id FROM users_vocational_trainings WHERE user_id = ?";
$stmtUserCiclos = $pdo->prepare($sqlUserCiclos);
$stmtUserCiclos->execute([$userId]);
$userCiclos = $stmtUserCiclos->fetchAll(PDO::FETCH_COLUMN); // Obtener solo los IDs de los ciclos

// Obtener todos los ciclos disponibles
$sql = "SELECT * FROM vocational_trainings";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Estudiante</title>
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">

    <style>
        /* Estilos del icono que actúa como checkbox */
        .toggle-icon {
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

        /* Cambia el color cuando está marcado */
        input[type="checkbox"]:checked+.toggle-icon {
            background-color: #c12b2e;
        }

        /* Ocultar el checkbox real */
        input[type="checkbox"] {
            position: absolute;
            width: 50px;
            height: 50px;
            opacity: 0;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>Ciclos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('../partials/container_left.php') ?>


        <div class="container-rigth">
            <div class="mostrar-ciclos">
                <form id="ciclos-form">
                    <?php while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php $isChecked = in_array($fila['id'], $userCiclos) ? 'checked' : ''; ?>
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
                                <input type="checkbox" id="ciclo<?= $fila['id'] ?>" name="ciclos[]" value="<?= $fila['id'] ?>" <?= $isChecked ?> onchange="toggleCiclo(this)">
                                <!-- Label que funciona como checkbox -->
                                <label for="ciclo<?= $fila['id'] ?>" class="toggle-icon" id="icono<?= $fila['id'] ?>">
                                    <?= $isChecked ? '-' : '+' ?>
                                </label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleCiclo(checkbox) {
            let cicloId = checkbox.value;
            let icon = document.getElementById("icono" + cicloId);

            // Obtener el número actual de checkboxes seleccionados
            let checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');

            // Si ya hay 2 seleccionados y se intenta seleccionar otro, impedirlo
            if (checkedBoxes.length > 2) {
                checkbox.checked = false; // Desmarca el intento
                return;
            }

            let action = checkbox.checked ? "add" : "remove";

            // Cambiar el icono de + a -
            icon.textContent = checkbox.checked ? "-" : "+";

            // Enviar petición AJAX al servidor
            let formData = new FormData();
            formData.append("ciclo_id", cicloId);
            formData.append("action", action);

            fetch("../../functions/Students/function_save_students_vocational_trainings.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
    <script src="../../js/selector_menu.js"></script>

  
</body>

</html>