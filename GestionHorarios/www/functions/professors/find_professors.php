<?php

include_once '../connection.php';

try {
    if (isset($_POST["txtFindProfessor"]) && !empty($_POST["txtFindProfessor"])) {
        $searchField = strtolower($_POST["txtFindProfessor"]);

        $searchTerm = "%$searchField%";

        $query = $pdo->prepare("SELECT * FROM professors WHERE name LIKE LOWER(?) OR first_name LIKE LOWER(?) OR second_name LIKE LOWER(?) OR email LIKE LOWER(?)");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } else {
        $query = $pdo->prepare("SELECT * FROM professors");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando profesores: " . $e->getMessage();
}
