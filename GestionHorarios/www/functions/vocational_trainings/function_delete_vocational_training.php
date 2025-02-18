<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnDelete"])) {

    $id = $_POST["id"];

    try {
        $query = $pdo->prepare("DELETE FROM `vocational_trainings` WHERE id LIKE ?");
        $query->execute([$id]);

        $_SESSION['mensaxe'] = "Ciclo eliminado correctamente";

        header('Location: ../../pages/administrator_vocational_trainings.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
    }
}
