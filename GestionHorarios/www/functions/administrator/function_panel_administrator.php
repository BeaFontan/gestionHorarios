<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit(); 
}

include_once '../connection.php';

try {
    if (isset($_POST["txtFindUser"])) {
        $searchField = strtolower($_POST["txtFindUser"]); 

        $searchTerm = "%$searchField%";

        $query = $pdo->prepare("SELECT * FROM users WHERE email LIKE LOWER(?) OR name LIKE LOWER(?) OR first_name LIKE LOWER(?) OR second_name LIKE LOWER(?) OR dni LIKE LOWER(?)");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);

    } else {
       echo json_encode([]);
    }

} catch (PDOException $th) {
    $_SESSION['mensaxe'] = "Error buscando alumno" . $e->getmessage();
}

