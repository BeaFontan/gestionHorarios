<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnDelete"])) {

    $id = $_POST["id"];

    $query = $pdo->prepare("Select id from modules where vocational_training_id like ?");
    $query->execute([$id]);
    $module = $query->fetchAll();

    if (empty($module)) {
        try {
            $query = $pdo->prepare("DELETE FROM `vocational_trainings` WHERE id LIKE ?");
            $query->execute([$id]);

            $_SESSION['mensaxe'] = "Ciclo eliminado correctamente";

            header('Location: ../../pages/administrator_vocational_trainings.php');
            exit();
        } catch (PDOException $e) {
            echo $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
        }
    } else {
        $_SESSION['mensaxe'] = "Non podes eliminar un Ciclo que contén módulos";
        header('Location: ../../pages/administrator_vocational_trainings.php');
        exit();
    }
}
