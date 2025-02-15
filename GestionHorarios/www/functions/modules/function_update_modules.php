<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnSave"])) {

    $id = $_POST['id'];
    $professor_id = $_POST['professor_id'];
    $vocational_training_id = $_POST['vocational_training_id'];
    $module_code = $_POST['module_code'];
    $name = $_POST['name'];
    $course = $_POST['course'];
    $sessions_number = $_POST['sessions_number'];
    $course_name = "";
    echo "llegó1";
    try {

        $query = $pdo->prepare("UPDATE `modules` SET `professor_id`=?,`vocational_training_id`=?,`module_code`=?,`name`='?,`course`=?,`sessions_number`=? WHERE id LIKE id");
        $query->execute([$professor_id, $vocational_training_id, $module_code, $name, $course, $sessions_number]);

        $_SESSION['mensaxe'] = "Módulo actualizado correctamente";

        header('Location: ../../pages/administrator_modules.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na actuación de datos" . $e->getMessage();
    }
}
