<?php

include_once '../connection.php';

try {
    // Verificamos si se envió el campo de búsqueda
    if (isset($_POST["txtFindProfessor"]) && !empty($_POST["txtFindProfessor"])) {
        $searchField = strtolower($_POST["txtFindProfessor"]);

        $searchTerm = "%$searchField%";

        // Realizamos la consulta de búsqueda
        $query = $pdo->prepare("SELECT * FROM professors WHERE name LIKE LOWER(?) OR first_name LIKE LOWER(?) OR second_name LIKE LOWER(?) OR email LIKE LOWER(?)");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, traemos todos los resultados
        $query = $pdo->prepare("SELECT * FROM professors");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando profesores: " . $e->getMessage();
}
