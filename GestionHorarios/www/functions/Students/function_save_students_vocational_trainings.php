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
        // Contar cuántos ciclos tiene el usuario actualmente
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM users_vocational_trainings WHERE user_id = ?");
        $stmtCount->execute([$userId]);
        $numCiclos = $stmtCount->fetchColumn();

        if ($action === "add") {
            // Si ya tiene 2 ciclos, impedir la inserción
            if ($numCiclos >= 2) {
                $_SESSION['mensaxe'] = "Solo puedes seleccionar hasta 2 ciclos.";
                echo "error: " . $_SESSION['mensaxe'];
                exit();
            }

            // Agregar el ciclo
            $query = $pdo->prepare("INSERT INTO users_vocational_trainings (user_id, vocational_training_id) VALUES (?, ?)");
            $query->execute([$userId, $cicloId]);
            echo "Ciclo añadido";
        } elseif ($action === "remove") {
            // Eliminar el ciclo
            $query = $pdo->prepare("DELETE FROM users_vocational_trainings WHERE user_id = ? AND vocational_training_id = ?");
            $query->execute([$userId, $cicloId]);
            echo "Ciclo eliminado";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la base de datos.";
        echo "error: " . $_SESSION['mensaxe'];
    }
}
?>
