<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnDelete"])) {

    $id = $_POST["id"];

    $query = $pdo->prepare("select * from modules_sessions where module_id like ?");
    $query->execute([$id]);
    $session = $query->fetchAll();

    $query = $pdo->prepare("select * from users_modules where module_id like ?");
    $query->execute([$id]);
    $user = $query->fetchAll();

    if (!empty($session)) {
        $_SESSION['mensaxe'] = "Non podes eliminar un módulo que está asignado a un horario";
        header('Location: ../../pages/administrator_modules.php');
        exit();
    } elseif (!empty($user)) {
        $_SESSION['mensaxe'] = "Non podes eliminar un módulo que ten matriculados alumnos";
        header('Location: ../../pages/administrator_modules.php');
        exit();
    } else {
        try {
            $query = $pdo->prepare("DELETE FROM `modules` WHERE id LIKE ?");
            $query->execute([$id]);

            $_SESSION['mensaxe'] = "Módulo eliminado correctamente";

            header('Location: ../../pages/administrator_modules.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['mensaxe'] = "Erro na eliminación de módulo" . $e->getMessage();
            header('Location: ../../pages/administrator_modules.php');
        }

    }
}
