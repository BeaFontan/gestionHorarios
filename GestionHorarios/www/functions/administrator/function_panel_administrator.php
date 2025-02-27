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
        $searchField = strtoupper(trim($_POST["txtFindUser"])); // Convertimos a mayúsculas y eliminamos espacios
        $searchTerm = "%$searchField%";

        // Buscamos por nombre (first_name), apellido (second_name), nombre completo (name + first_name), email y dni
        $query = $pdo->prepare("
            SELECT id, name, first_name, second_name, email, dni, telephone 
            FROM users 
            WHERE rol = 'student'
            AND (
                UPPER(first_name) LIKE ? 
                OR UPPER(second_name) LIKE ? 
                OR UPPER(CONCAT(name, ' ', first_name)) LIKE ? 
                OR UPPER(email) LIKE ? 
                OR UPPER(dni) LIKE ?
            )
        ");

        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, devolver todos los usuarios
        $query = $pdo->prepare("
            SELECT id, name, first_name, second_name, email, dni, telephone 
            FROM users 
            WHERE rol = 'student'
        ");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando usuario: " . $e->getMessage();
    echo json_encode([]); // Devolvemos un array vacío en caso de error
}
