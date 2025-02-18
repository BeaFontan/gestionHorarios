<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnCreateVocationalTraining"])) {
    $course_code = $_POST['txtCourse_code'];
    $acronym = $_POST['txtAcronym'];
    $course_name = $_POST['txtName'];
    $modality = $_POST['selectModality'];
    $type = $_POST['selectType'];

    try {
        $query = $pdo->prepare("INSERT INTO `vocational_trainings`(`course_code`, `acronym`, `course_name`, `modality`, `type`) 
                                VALUES (?,?,?,?,?)");
        $query->execute([$course_code, $acronym, $course_name, $modality, $type]);

        $_SESSION['mensaxe'] = "Ciclo insertado correctamente";
        header('Location: ../../pages/administrator_vocational_trainings.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la inserción de datos: " . $e->getMessage();
        header('Location: ../../pages/administrator_panel.php');
        exit();
    }
}
