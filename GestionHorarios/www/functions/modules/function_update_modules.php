<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');


if (isset($_POST["btnSave"])) {

    $idModuleToEdit = $_POST['id'];
    $professor_id = $_POST["selectProfessor"];
    $vocational_training_id = $_POST["selectVocationalTraining"];
    $module_code = $_POST['txtModule_code'];
    $name = $_POST['txtModule_name'];
    $selectCourse = $_POST['selectCourse'];
    $sessions_number = $_POST['txtSessions_number'];
    $classRoom=$_POST['txtCLassRoom'];
    $color=$_POST['txtColor'];
    $module_acronym=$_POST['txtModuleAcronym'];

    try {

        $query = $pdo->prepare("UPDATE `modules` 
                                SET `professor_id`=?, `vocational_training_id`=?,`module_code`=?,`name`=?,`course`=?,`sessions_number`=?,`classroom`=?, `color`=?, `module_acronym`=? 
                                WHERE id like ?");
        $query->execute([$professor_id, $vocational_training_id, $module_code, $name, $selectCourse, $sessions_number, $classRoom, $color, $module_acronym, $idModuleToEdit]);

        $_SESSION['mensaxe'] = "Módulo actualizado correctamente";

        header('Location: ../../pages/administrator_modules.php');
        exit();
    } catch (PDOException $e) {
        echo "$e";
        $_SESSION['mensaxe'] = "Erro na actuación de datos" . $e->getMessage();
    }
}
