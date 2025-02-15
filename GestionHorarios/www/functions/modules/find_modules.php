<?php


try {
    // Verificamos si se envió el campo de búsqueda
    if (isset($_POST["txtFindVocationalTraining"]) && !empty($_POST["txtFindVocationalTraining"])) {
        $searchField = strtolower($_POST["txtFindVocationalTraining"]);

        $searchTerm = "%$searchField%";

        // Realizamos la consulta de búsqueda
        $query = $pdo->prepare("SELECT * FROM modules WHERE course_code LIKE LOWER(?) OR acronym LIKE LOWER(?) OR course_name LIKE LOWER(?) OR modality LIKE LOWER(?) OR type LIKE LOWER(?)");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($searchResults);
    } else {
        // Si no hay término de búsqueda, traemos todos los resultados
        $query = $pdo->prepare("SELECT * FROM vocational_trainings");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($allResults);
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando ciclo: " . $e->getMessage();
}
