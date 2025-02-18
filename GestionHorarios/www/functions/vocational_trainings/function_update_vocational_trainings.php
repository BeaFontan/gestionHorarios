<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnSave"])) {

    $id = $_POST['id'];
    $course_code = $_POST['txtCourse_code'];
    $acronym = $_POST['txtAcronym'];
    $course_name = $_POST['txtName'];
    $modality = $_POST['txtModality'];
    $type = $_POST['txtType'];

    try {

        $query = $pdo->prepare("UPDATE `vocational_trainings` 
                                    SET `course_code`=?,`acronym`=?,`course_name`=?,`modality`=?,`type`=?
                                    WHERE id like ?");
        $query->execute([$course_code, $acronym, $course_name, $modality, $type, $id]);

        $_SESSION['mensaxe'] = "Ciclo actualizado correctamente";

        header('Location: ../../pages/administrator_vocational_trainings.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na insercción de datos" . $e->getMessage();
    }
}
