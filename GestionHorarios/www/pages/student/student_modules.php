<?php
session_start();
include_once '../../functions/connection.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];

// Consultar los módulos asignados al usuario
$sqlModulosAsignados = "SELECT module_id FROM users_modules WHERE user_id = ?";
$stmtModulosAsignados = $pdo->prepare($sqlModulosAsignados);
$stmtModulosAsignados->execute([$userId]);
$modulosAsignados = $stmtModulosAsignados->fetchAll(PDO::FETCH_COLUMN, 0); // Devuelve un array con los IDs de los módulos asignados

// Obtener los módulos disponibles y su ciclo
$sqlModulos = "SELECT m.*, vt.course_name AS ciclo_nombre
               FROM modules m
               JOIN vocational_trainings vt ON vt.id = m.vocational_training_id
               JOIN users_vocational_trainings uvt ON uvt.vocational_training_id = vt.id
               WHERE uvt.user_id = ?
               ORDER BY vt.course_name, m.name";

$stmtModulos = $pdo->prepare($sqlModulos);

$stmtModulos->execute([$userId]);
$modulos = $stmtModulos->fetchAll(PDO::FETCH_ASSOC);

// Agrupar los módulos por ciclo
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
    <title>Módulos del Usuario</title>
    <link rel="stylesheet" href="../css/administrator_panel.css">

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
    <h2>Módulos Matriculados</h2>

    <div class="container">
        <!-- Contenedor izquierdo -->
        <?php include_once('../partials/container_left.php') ?>

        <div class="container-rigth">
            <div class="mostrar-modulos">
                <?php if (!empty($modulosPorCiclo)) : ?>
                    <?php foreach ($modulosPorCiclo as $ciclo => $modulosCiclo) : ?>
                        <div class="ciclo-header"><?= htmlspecialchars($ciclo) ?></div>
                        <?php foreach ($modulosCiclo as $modulo) : ?>
                            <div class='container-user container-check'>
                                <div class='row'>
                                    <div class='user-imagen'>
                                        <img src='/images/modulo.png' class='pic' alt='Módulo img'>
                                    </div>
                                    <div class='user-texto'>
                                        <p class='texto-nombre'><strong><?= htmlspecialchars($modulo['name']) ?></strong></p>
                                        <p class='texto-descripcion'><?= htmlspecialchars($modulo['course']) ?></p>
                                    </div>
                                    <!-- Checkbox oculto para añadir/eliminar módulo -->
                                    <input type="checkbox" id="modulo<?= $modulo['id'] ?>" name="modulos[]" value="<?= $modulo['id'] ?>"
                                        onchange="toggleModulo(this)"
                                        <?php if (in_array($modulo['id'], $modulosAsignados)) echo 'checked'; ?>>
                                    <!-- Label que funciona como checkbox -->
                                    <label for="modulo<?= $modulo['id'] ?>" class="toggle-icon" id="iconoModulo<?= $modulo['id'] ?>">
                                        <?php echo (in_array($modulo['id'], $modulosAsignados)) ? '-' : '+'; ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
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

            // Cambiar el icono de + a -
            icon.textContent = checkbox.checked ? "-" : "+";

            // Enviar petición AJAX al servidor
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

    <script src="../../js/selector_menu.js"></script>
</body>

</html>