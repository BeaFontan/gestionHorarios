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
        $query = $pdo->prepare("DELETE FROM `users` WHERE id LIKE ?");
        $query->execute([$id]);
       
        $_SESSION['mensaxe'] = "Usuario eliminado correctamente";

         header('Location: ../../pages/administrator_panel.php');
         exit();

    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na eliminación de datos" . $e->getMessage();
    }
}

?>

