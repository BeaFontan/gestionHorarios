<?php

include_once '../connection.php';

try {
    // Función para transformar los valores antes de enviarlos al frontend
    function transformData($data) {
        foreach ($data as &$item) {
            // Transformar el campo "course" a "1º" o "2º"
            $item['course'] = ($item['course'] === 'first') ? "1º" : "2º";
        }
        return $data;
    }

    if (isset($_POST["txtFindModules"]) && !empty($_POST["txtFindModules"])) {
        $searchField = strtolower($_POST["txtFindModules"]);
        $searchTerm = "%$searchField%";

        // Realizamos la consulta con JOIN para obtener los nombres de profesor y ciclo formativo
        $query = $pdo->prepare("
            SELECT m.*, p.name AS professor_name, p.first_name AS professor_first_name, vt.course_name 
            FROM modules m
            LEFT JOIN professors p ON m.professor_id = p.id
            LEFT JOIN vocational_trainings vt ON m.vocational_training_id = vt.id
            WHERE LOWER(m.module_code) LIKE ? 
            OR LOWER(m.name) LIKE ? 
            OR LOWER(m.course) LIKE ?
            OR LOWER(vt.course_name) LIKE ?
            OR LOWER(p.name) LIKE ?
            OR LOWER(p.first_name) LIKE ?");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($searchResults)); // Aplicamos transformación
    } else {
        // Si no hay búsqueda, traemos todos los resultados con transformación
        $query = $pdo->prepare("
            SELECT m.*, p.name AS professor_name, p.first_name AS professor_first_name, vt.course_name 
            FROM modules m
            LEFT JOIN professors p ON m.professor_id = p.id
            LEFT JOIN vocational_trainings vt ON m.vocational_training_id = vt.id");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($allResults)); // Aplicamos transformación
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando módulos: " . $e->getMessage();
}
