<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnFormCreateModule"])) {

    $proffesor_id = $_POST['selectProfessor'];
    $vocational_training_id = $_POST['selectVocationTraining'];
    $module_code = $_POST['txtModuleCode'];
    $name = $_POST['txtName'];
    $course = $_POST['selectCourse'];
    $session_numbers = $_POST['txtSessions'];
    $color = $_POST["colorModule"];
    $module_acronym = $_POST["txtModuleAcronym"];
    $classroom = $_POST["txtCLassRoom"];



    try {
        $query = $pdo->prepare("INSERT INTO `modules`(`professor_id`, `vocational_training_id`, `module_code`, `module_acronym`, `name`, `course`, `sessions_number`, `classroom`, `color`) 
                            VALUES (?,?,?,?,?,?,?,?,?)");
        $query->execute([$proffesor_id, $vocational_training_id, $module_code, $module_acronym, $name, $course, $session_numbers, $classroom, $color]);

        $_SESSION['mensaxe'] = "Módulo insertado correctamente";
        header('Location: ../../pages/administrator_modules.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la inserción de módulo: " . $e->getMessage();
        header('Location: ../../pages/administrator_modules.php');
        exit();
    }
}
