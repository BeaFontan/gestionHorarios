<?php
session_start();
include_once '../../functions/connection.php';


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}


$userId = $_SESSION['user']['id'];
$sqlUserCiclos = "SELECT vocational_training_id FROM users_vocational_trainings WHERE user_id = ?";
$stmtUserCiclos = $pdo->prepare($sqlUserCiclos);
$stmtUserCiclos->execute([$userId]);
$userCiclos = $stmtUserCiclos->fetchAll(PDO::FETCH_COLUMN);


$sql = "SELECT * FROM vocational_trainings";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclos</title>
    <link rel="icon" type="image/png" href="../../images/icono.png">
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>

    <style>
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
        }


        input[type="checkbox"]:checked+.toggle-icon {
            background-color: #c12b2e;
        }


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
    <div id="overlay" class="overlay"></div>
    <h2>Ciclos</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('../partials/container_left.php') ?>

        <div class="container-rigth">
            <input type="text" id="checkMenu" value="0" hidden>
            <button onclick="menu()" class='btn-menu-crear' style="margin-left: 1%;" name=''>
                <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
            </button>

            <form class="mostrar-ciclos" id="ciclos-form">
                <?php while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php $isChecked = in_array($fila['id'], $userCiclos) ? 'checked' : ''; ?>
                    <div class='container-user container-check'>
                        <div class='row'>
                            <div class='user-imagen-alumn'>
                                <img src='/images/ciclo.png' class='pic-alumn' alt='Módulo img'>
                            </div>
                            <div class='user-texto'>
                                <p class='texto-nombre'><?= htmlspecialchars($fila['course_name']) ?></p>
                                <p class='texto-ciclo'><?= htmlspecialchars($fila['modality']) ?></p>
                            </div>
                            <div class='user-botonesAdd'>
                                <input type="checkbox" id="ciclo<?= $fila['id'] ?>" name="ciclos[]" value="<?= $fila['id'] ?>" <?= $isChecked ?> onchange="toggleCiclo(this)">

                                <label for="ciclo<?= $fila['id'] ?>" class="toggle-icon" id="icono<?= $fila['id'] ?>">
                                    <?= $isChecked ? '-' : '+' ?>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </form>

        </div>
    </div>

    <script>
        function toggleCiclo(checkbox) {
            let cicloId = checkbox.value;
            let icon = document.getElementById("icono" + cicloId);


            let checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');


            if (checkedBoxes.length > 2) {
                checkbox.checked = false;
                return;
            }

            let action = checkbox.checked ? "add" : "remove";


            icon.textContent = checkbox.checked ? "-" : "+";


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
    <script src="../../js/menu.js"></script>

</body>

</html>