<?php

function findVocationalTrainings($pdo){
    try {
        $query = $pdo->query("SELECT * FROM `vocational_trainings`");
        $query->execute();
        return $query->fetchAll();

    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
    }
}

?>

