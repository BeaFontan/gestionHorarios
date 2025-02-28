<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['user'])) {
    die("No autorizado");
}

$userId = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cicloId = $_POST["ciclo_id"];
    $action = $_POST["action"];

    try {
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM users_vocational_trainings WHERE user_id = ?");
        $stmtCount->execute([$userId]);
        $numCiclos = $stmtCount->fetchColumn();

        if ($action === "add") {
            if ($numCiclos >= 2) {
                $_SESSION['mensaxe'] = "Solo puedes seleccionar hasta 2 ciclos.";
                echo "error: " . $_SESSION['mensaxe'];
                exit();
            }

            $query = $pdo->prepare("INSERT INTO users_vocational_trainings (user_id, vocational_training_id) VALUES (?, ?)");
            $query->execute([$userId, $cicloId]);
            echo "Ciclo añadido";
        } elseif ($action === "remove") {
            
            $queryModules = $pdo->prepare("
                DELETE um FROM users_modules um
                INNER JOIN modules m ON um.module_id = m.id
                INNER JOIN vocational_trainings v ON m.vocational_training_id = v.id
                WHERE um.user_id = ? AND v.id = ?
            ");
            
            $queryModules->execute([$userId, $cicloId]);
            $query = $pdo->prepare("DELETE FROM users_vocational_trainings WHERE user_id = ? AND vocational_training_id = ?");
            $query->execute([$userId, $cicloId]);

            echo "Ciclo y módulos relacionados eliminados";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la base de datos.";
        echo "error: " . $_SESSION['mensaxe'];
    }
}
