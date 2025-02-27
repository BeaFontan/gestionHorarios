<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../connection.php';

try {
    // Función para transformar los valores
    function transformData($data) {
        foreach ($data as &$item) {
            // Transformamos "type"
            $item['type'] = ($item['type'] === 'higher') ? "Superior" : "Medio";
            // Transformamos "modality"
            $item['modality'] = ($item['modality'] === 'ordinary') ? "Ordinario" : 
                               (($item['modality'] === 'modular') ? "Modular" : "Dual");
        }
        return $data;
    }

    if (isset($_POST["txtFindVocationalTraining"]) && !empty($_POST["txtFindVocationalTraining"])) {
        $searchField = strtolower($_POST["txtFindVocationalTraining"]);
        $searchTerm = "%$searchField%";

        // Consulta de búsqueda con parámetros
        $query = $pdo->prepare("SELECT * FROM vocational_trainings 
                                WHERE LOWER(course_code) LIKE ? 
                                OR LOWER(course_name) LIKE ? 
                                OR LOWER(modality) LIKE ? 
                                OR LOWER(type) LIKE ?");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($searchResults)); // Aplicamos transformación
    } else {
        // Si no hay término de búsqueda, traemos todos los resultados con transformación
        $query = $pdo->prepare("SELECT * FROM vocational_trainings");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($allResults)); // Aplicamos transformación
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando ciclo: " . $e->getMessage();
}
