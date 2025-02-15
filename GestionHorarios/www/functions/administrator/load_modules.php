<?php

function loadModules($pdo, $vocational_training)
{
    try {

        try {
            $query = $pdo->prepare("SELECT * FROM `modules` where vocational_training_id = ?");
            $query->execute([$vocational_training]);
            return $query->fetchAll();
        } catch (PDOException) {
            echo "";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
    }
}
