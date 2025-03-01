<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../connection.php';

try {
    if (isset($_POST["txtFindUser"]) && !empty($_POST["txtFindUser"])) {
        $searchField = strtoupper(trim($_POST["txtFindUser"]));
        $searchTerm = "%$searchField%";

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
    echo json_encode([]);
}
