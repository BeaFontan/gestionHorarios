<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../connection.php';

try {
    // Si hay un término de búsqueda, realizamos la búsqueda
    if (isset($_POST["txtFindUser"]) && !empty($_POST["txtFindUser"])) {
        $searchField = strtolower($_POST["txtFindUser"]);

        $searchTerm = "%$searchField%";

        // Consulta con filtros para buscar por email, nombre, etc.
        $query = $pdo->prepare("SELECT * FROM users WHERE email LIKE LOWER(?) OR name LIKE LOWER(?) OR first_name LIKE LOWER(?) OR second_name LIKE LOWER(?) OR dni LIKE LOWER(?)");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, devolver todos los usuarios
        $query = $pdo->prepare("SELECT * FROM users");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando usuario: " . $e->getMessage();
}
