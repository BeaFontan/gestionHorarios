<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnDelete"])) {

    $id = $_POST["id"];

    $query = $pdo->prepare("select * from users_modules where user_id like ?");
    $query->execute([$id]);
    $userModule = $query->fetchAll();

    $query = $pdo->prepare("select * from users_vocational_trainings where user_id like ?");
    $query->execute([$id]);
    $userVocationalTraining = $query->fetchAll();


    if (!empty($userModule)) {
        $_SESSION['mensaxe'] = "Non podes eliminar un alumno que está matriculado nun módulo";
        header('Location: ../../pages/administrator_panel.php');
        exit();

    }else if (!empty($userVocationalTraining)) {
        $_SESSION['mensaxe'] = "Non podes eliminar un alumno que está matriculado nun ciclo";
        header('Location: ../../pages/administrator_panel.php');
        exit();
        
    }else {
        try {
            $query = $pdo->prepare("DELETE FROM `users` WHERE id LIKE ?");
            $query->execute([$id]);
    
            $_SESSION['mensaxe'] = "Usuario eliminado correctamente";
    
            header('Location: ../../pages/administrator_panel.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
        }
    }




}
