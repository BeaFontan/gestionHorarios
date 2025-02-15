<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnSave"])) {

    $editModules = $_POST['id'];
    $module_code = $_POST['module_code'];
    $name = $_POST['name'];
    $selectCourse = $_POST['selectCourse'];
    $sessions_number = $_POST['sessions_number'];

    try {

        $query = $pdo->prepare("UPDATE `modules` SET `professor_id`=?,`vocational_training_id`=?,`module_code`=?,`name`='?,`course`=?,`sessions_number`=? WHERE id LIKE id");
        $query->execute([$professor_id, $vocational_training_id, $module_code, $name, $course, $sessions_number, editModules]);

        $_SESSION['mensaxe'] = "Módulo actualizado correctamente";

        header('Location: ../../pages/administrator_modules.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na actuación de datos" . $e->getMessage();
    }
}
