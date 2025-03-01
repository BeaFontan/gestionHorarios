<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../connection.php';

try {
    function transformData($data)
    {
        foreach ($data as &$item) {

            $item['type'] = ($item['type'] === 'higher') ? "Superior" : "Medio";

            $item['modality'] = ($item['modality'] === 'ordinary') ? "Ordinario" : (($item['modality'] === 'modular') ? "Modular" : "Dual");
        }
        return $data;
    }

    if (isset($_POST["txtFindVocationalTraining"]) && !empty($_POST["txtFindVocationalTraining"])) {
        $searchField = strtolower($_POST["txtFindVocationalTraining"]);
        $searchTerm = "%$searchField%";

        $query = $pdo->prepare("SELECT * FROM vocational_trainings 
                                WHERE LOWER(course_code) LIKE ? 
                                OR LOWER(course_name) LIKE ? 
                                OR LOWER(modality) LIKE ? 
                                OR LOWER(type) LIKE ?");
        $query->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);

        $searchResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($searchResults));
    } else {

        $query = $pdo->prepare("SELECT * FROM vocational_trainings");
        $query->execute();

        $allResults = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(transformData($allResults));
    }
} catch (PDOException $e) {
    $_SESSION['mensaxe'] = "Error buscando ciclo: " . $e->getMessage();
}
