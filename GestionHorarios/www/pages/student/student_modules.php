<?php
session_start();
include_once '../../functions/connection.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];

// Obtener módulos asignados al usuario
$sqlModulosAsignados = "SELECT module_id FROM users_modules WHERE user_id = ?";
$stmtModulosAsignados = $pdo->prepare($sqlModulosAsignados);
$stmtModulosAsignados->execute([$userId]);
$modulosAsignados = $stmtModulosAsignados->fetchAll(PDO::FETCH_COLUMN, 0);

// Obtener los módulos disponibles
$sqlModulos = "SELECT m.*, vt.course_name AS ciclo_nombre, p.name AS professor_name, p.first_name AS professor_first_name
               FROM modules m
               JOIN vocational_trainings vt ON vt.id = m.vocational_training_id
               JOIN professors p ON p.id = m.professor_id
               JOIN users_vocational_trainings uvt ON uvt.vocational_training_id = vt.id
               WHERE uvt.user_id = ?
               ORDER BY vt.course_name, m.name";

$stmtModulos = $pdo->prepare($sqlModulos);
$stmtModulos->execute([$userId]);
$modulos = $stmtModulos->fetchAll(PDO::FETCH_ASSOC);

$modulosPorCiclo = [];
foreach ($modulos as $modulo) {
    $cicloNombre = $modulo['ciclo_nombre'];
    $modulosPorCiclo[$cicloNombre][] = $modulo;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulos</title>
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
        input[type="checkbox"]:checked + .toggle-icon {
            background-color: #c12b2e;
        }
        input[type="checkbox"] {
            position: absolute;
            width: 50px;
            height: 50px;
            opacity: 0;
            cursor: pointer;
        }
        .color_circle {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h2>Módulos</h2>
    <div class="container">
        <?php include_once('../partials/container_left.php') ?>
        <div class="container-rigth">
            <div class="mostrar-modulos">
                <?php if (!empty($modulosPorCiclo)) : ?>
                    <?php foreach ($modulosPorCiclo as $ciclo => $modulosCiclo) : ?>
                        <div class="ciclo-header">
                            <p class='texto-nombre'><strong><?= htmlspecialchars($ciclo) ?></strong></p>
                        </div>
                        <br>
                        <?php foreach ($modulosCiclo as $modulo) : ?>
                            <div class='container-user container-check'>
                                <div class='row'>
                                    <div class='user-imagen'>
                                        <img src='/images/asignatura.png' class='pic' alt='Módulo img'>
                                    </div>
                                    <div class='user-texto'>
                                        <p class='texto-nombre'><?= htmlspecialchars($modulo['name']) ?>
                                            <span class='color_circle' style='background-color: <?= htmlspecialchars($modulo['color']) ?>;'></span>
                                        </p>
                                        <p class='texto-ciclo'>
                                            <?= htmlspecialchars($modulo['professor_name'] . ' ' . $modulo['professor_first_name']) ?> -
                                            <?= $modulo['course'] === 'first' ? '1º' : '2º' ?> <?= htmlspecialchars($modulo['ciclo_nombre']) ?>
                                        </p>
                                        <p class='texto-ciclo' style='font-size: 12px;'>
                                            <?= htmlspecialchars($modulo['module_code']) ?> -
                                            <strong>Clase:</strong> <?= htmlspecialchars($modulo['classroom']) ?> -
                                            <strong>Nº sesiones:</strong> <?= htmlspecialchars($modulo['sessions_number']) ?>
                                        </p>
                                    </div>
                                    <div class='user-botonesAdd'>
                                        <input type="checkbox" id="modulo<?= $modulo['id'] ?>" name="modulos[]" value="<?= $modulo['id'] ?>"
                                            onchange="toggleModulo(this)"
                                            <?php if (in_array($modulo['id'], $modulosAsignados)) echo 'checked'; ?>>
                                        <label for="modulo<?= $modulo['id'] ?>" class="toggle-icon" id="iconoModulo<?= $modulo['id'] ?>">
                                            <?= in_array($modulo['id'], $modulosAsignados) ? '-' : '+'; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <br><br>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No tienes módulos asignados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function toggleModulo(checkbox) {
            let moduloId = checkbox.value;
            let icon = document.getElementById("iconoModulo" + moduloId);
            icon.textContent = checkbox.checked ? "-" : "+";

            let formData = new FormData();
            formData.append("modulo_id", moduloId);
            formData.append("action", checkbox.checked ? "add" : "remove");

            fetch("../../functions/Students/function_student_modules.php", {
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
</body>
</html>
