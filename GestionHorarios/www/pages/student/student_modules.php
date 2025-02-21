<?php
session_start();
include_once '../../functions/connection.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Obtener los módulos en los que el usuario está matriculado
$userId = $_SESSION['user']['id'];
$sqlModulos = "SELECT m.* FROM  m
               INNER JOIN users_modules um ON m.id = um.module_id
               WHERE um.user_id = ?";

$stmtModulos = $pdo->prepare($sqlModulos);
$stmtModulos->execute([$userId]);
$modulos = $stmtModulos->fetchAll(PDO::FETCH_ASSOC);

// Si no tiene módulos asignados, mostramos un mensaje
if (empty($modulos)) {
    $_SESSION['mensaxe'] = "No estás matriculado en ningún módulo.";

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulos del Usuario</title>
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">

    <style>
        .modulo-card {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px;
            border: 2px solid #0088cc;
            border-radius: 10px;
            margin-bottom: 10px;
            background-color: white;
            justify-content: space-between;
        }

        .modulo-card img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .modulo-card .texto {
            flex-grow: 1;
        }

        .modulo-card .texto p {
            margin: 0;
        }
    </style>
</head>

<body>
    <h2>Módulos Matriculados</h2>

    <div class="container">
        <!-- Contenedor izquierdo -->
        <?php include_once('../partials/container_left.php') ?>

        <div class="container-right">
            <div class="mostrar-modulos">
                <?php if (!empty($modulos)) : ?>
                    <?php foreach ($modulos as $modulo) : ?>
                        <div class='modulo-card'>
                            <div class='row'>
                                <div class='modulo-imagen'>
                                    <img src='/images/modulo.png' class='pic' alt='Módulo img'>
                                </div>
                                <div class='modulo-texto'>
                                    <p class='texto-nombre'><strong><?= htmlspecialchars($modulo['module_name']) ?></strong></p>
                                    <p class='texto-descripcion'><?= htmlspecialchars($modulo['description']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No tienes módulos asignados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../js/selector_menu.js"></script>
</body>

</html>
