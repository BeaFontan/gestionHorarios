<?php
session_start();
include_once '../connection.php';

header('Content-Type: application/json');

$response = ["modules" => [], "assignedModules" => []];

if (!empty($_POST["ciclo"]) && !empty($_POST["curso"])) {
    $vocational_training = $_POST["ciclo"];
    $curso = $_POST["curso"];

    try {
        $query = $pdo->prepare("SELECT id, name, color, sessions_number FROM modules WHERE vocational_training_id = ? AND course = ?");
        $query->execute([$vocational_training, $curso]);
        $response["modules"] = $query->fetchAll(PDO::FETCH_ASSOC);

        $queryAssigned = $pdo->query("SELECT session_id, module_id FROM modules_sessions");
        $assignedModules = $queryAssigned->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($assignedModules as $assigned) {
            $response["assignedModules"][$assigned['session_id']] = $assigned['module_id'];
        }

    } catch (PDOException $e) {
        $response["error"] = "Error al obtener módulos: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
