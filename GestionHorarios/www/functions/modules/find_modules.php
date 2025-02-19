<?php

include_once '../connection.php';

try {
    // Verificamos si se envió el campo de búsqueda
    if (isset($_POST["txtFindModules"]) && !empty($_POST["txtFindModules"])) {
        $searchField = strtolower($_POST["txtFindModules"]);

        $searchTerm = "%$searchField%";

        // Realizamos la consulta de búsqueda
        $query = $pdo->prepare("SELECT * FROM modules WHERE professor_id LIKE LOWER(?) OR vocational_training_id LIKE LOWER(?) OR module_code LIKE LOWER(?) OR name LIKE LOWER(?) OR course LIKE LOWER(?) OR sessions_number LIKE LOWER(?) ");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, traemos todos los resultados
        $query = $pdo->prepare("SELECT * FROM modules");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando modulos: " . $e->getMessage();
}
