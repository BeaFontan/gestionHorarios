<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['user'])) {
    die("No autorizado");
}

$userId = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $moduloId = $_POST["modulo_id"];
    $action = $_POST["action"];

    try {
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM users_modules WHERE user_id = ?");
        $stmtCount->execute([$userId]);
        $numModulos = $stmtCount->fetchColumn();

        if ($action === "add") {
            $query = $pdo->prepare("INSERT INTO users_modules (user_id, module_id) VALUES (?, ?)");
            $query->execute([$userId, $moduloId]);
            echo "Módulo añadido";
        } elseif ($action === "remove") {
            $query = $pdo->prepare("DELETE FROM users_modules WHERE user_id = ? AND module_id = ?");
            $query->execute([$userId, $moduloId]);
            echo "Módulo eliminado";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la base de datos.";
        echo "error: " . $_SESSION['mensaxe'];
    }
}
