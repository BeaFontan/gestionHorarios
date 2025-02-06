<?php

include('../connection.php');


$name = $_POST["txtName"];
$firstName = $_POST["txtFirstName"];
$secondName = $_POST["txtSecondName"];
$email = $_POST["txtEmail"];
$phone = $_POST["txtPhone"];
$dni = $_POST["txtDNI"];
$rol = "student";
$password = $name . substr($firstName, 0, 1) . substr($secondName, 0, 1);
$passHash = password_hash($password, PASSWORD_DEFAULT);


if (isset($_POST["btnCreateUser"])) {
    try {
        $query = $pdo->prepare("");
    } catch (PDOException $e) {
        $mensaxe = "Erro na insercción de datos" . $e->getMessage();
    }
}
?>

