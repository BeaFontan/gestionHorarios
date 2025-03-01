<?php
session_start();

include('../connection.php');

if (isset($_POST["btnFormCreateUser"])) {
    $name = $_POST["txtName"];
    $firstName = $_POST["txtFirstName"];
    $secondName = $_POST["txtSecondName"];
    $email = $_POST["txtEmail"];
    $phone = $_POST["txtPhone"];
    $dni = $_POST["txtDNI"];
    $rol = "student";
    $password = strtolower($name) . strtolower(substr($firstName, 0, 1));
    $passHash = password_hash($password, PASSWORD_DEFAULT);
    $password_reset = 0;

    try {
        $query = $pdo->prepare("INSERT INTO `users`(`email`, `password`, `password_reset`, `rol`, `name`, `first_name`, `second_name`, `telephone`, `dni`) 
                                VALUES (?,?,?,?,?,?,?,?,?)");
        $query->execute([$email, $passHash, $password_reset, $rol, $name, $firstName, $secondName, $phone, $dni]);

        $_SESSION['mensaxe'] = "Usuario insertado correctamente";
        header('Location: ../../pages/administrator_panel.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error en la inserción de datos: " . $e->getMessage();
        header('Location: ../../pages/administrator_panel.php');
        exit();
    }
}
