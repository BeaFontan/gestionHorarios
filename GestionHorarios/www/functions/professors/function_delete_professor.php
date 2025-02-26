<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnDelete"])) {

    $id = $_POST["id"];
 
    $query = $pdo->prepare("Select id from modules where professor_id like ?");
    $query->execute([$id]);
    $proffesor = $query->fetchAll();

    if (empty($proffesor)) {
        try {
            $query = $pdo->prepare("DELETE FROM `professors` WHERE id LIKE ?");
            $query->execute([$id]);
    
            $_SESSION['mensaxe'] = "Profesor eliminado correctamente";
    
            header('Location: ../../pages/administrator_professors.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
            header('Location: ../../pages/administrator_professors.php');
            exit();
        }
    }else {
        $_SESSION['mensaxe'] = "Non podes eliminar un profesor que imparte un módulo";
        header('Location: ../../pages/administrator_professors.php');
        exit();
    }
}
