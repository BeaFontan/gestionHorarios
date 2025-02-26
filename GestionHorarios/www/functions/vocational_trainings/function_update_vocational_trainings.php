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
    $course_name = $_POST['txtCourse_name'];
    $modality = $_POST['selectModality'];
    $type = $_POST['selectType'];

    try {

        $query = $pdo->prepare("UPDATE `vocational_trainings` 
                                    SET `course_code`=?,`course_name`=?,`modality`=?,`type`=?
                                    WHERE id like ?");
        $query->execute([$course_code, $course_name, $modality, $type, $id]);

        $_SESSION['mensaxe'] = "Ciclo actualizado correctamente";

        header('Location: ../../pages/administrator_vocational_trainings.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na insercción de datos" . $e->getMessage();
    }
}
