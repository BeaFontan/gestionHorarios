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
        $searchField = strtoupper($_POST["txtFindUser"]); // Convertimos la búsqueda a mayúsculas
        $searchTerm = "%$searchField%";

        // Buscamos por nombre (first_name), apellido (second_name), y la concatenación de name + first_name en mayúsculas
        $query = $pdo->prepare("SELECT * FROM users 
        WHERE (
            (UPPER(first_name) LIKE UPPER(?) OR UPPER(second_name) LIKE UPPER(?)) 
            OR UPPER(CONCAT(name, ' ', first_name)) LIKE UPPER(?) 
            OR UPPER(email) LIKE UPPER(?) 
            OR UPPER(dni) LIKE UPPER(?)
        )
        AND rol = 'student'");

        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, devolver todos los usuarios
        $query = $pdo->prepare("SELECT * FROM users WHERE rol LIKE 'student'");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando usuario: " . $e->getMessage();
}
