<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include('../connection.php');

if (isset($_POST["btnSave"])) {

    $id = $_POST["id"];
    $name = $_POST["txtName"];
    $firstName = $_POST["txtFirstName"];
    $secondName = $_POST["txtSecondName"];
    $email = $_POST["txtEmail"];

    try {
        $query = $pdo->prepare("UPDATE `professors` 
                                SET `name`=?,`first_name`=?,`second_name`=?, `email`=?
                                WHERE id like ?");
        $query->execute([$name, $firstName, $secondName, $email, $id]);

        $_SESSION['mensaxe'] = "Profesor actualizado correctamente";

        header('Location: ../../pages/administrator_professors.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro na insercción de datos" . $e->getMessage();
    }
}
