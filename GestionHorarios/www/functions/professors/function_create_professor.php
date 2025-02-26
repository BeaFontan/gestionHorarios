<?php
session_start();

include('../connection.php');

if (isset($_POST["btnCreateProffesor"])) {
    $name = $_POST["txtName"];
    $firstName = $_POST["txtFirstName"];
    $secondName = $_POST["txtSecondName"];
    $email = $_POST["txtEmail"];

    try {
        $query = $pdo->prepare("INSERT INTO `professors`(`name`, `first_name`, `second_name`, `email`) 
        VALUES (?,?,?,?)");
        $query->execute([$name, $firstName, $secondName, $email]);

        $_SESSION['mensaxe'] = "Profesor insertado correctamente";
        header('Location: ../../pages/administrator_professors.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la inserción de datos: " . $e->getMessage();
        header('Location: ../../pages/administrator_professors.php');
        exit();
    }
}
