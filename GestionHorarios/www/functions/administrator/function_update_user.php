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
    $phone = $_POST["txtTelephone"];
    $dni = $_POST["txtDNI"];

    try {
        $query = $pdo->prepare("UPDATE `users` 
                                SET `email`=?,`name`=?,`first_name`=?,`second_name`=?,`telephone`=?,`dni`=?
                                WHERE id like ?");
        $query->execute([$email, $name, $firstName, $secondName, $phone, $dni, $id]);

        $_SESSION['mensaxe'] = "Usuario actualizado correctamente";

        header('Location: ../../pages/administrator_panel.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro na insercción de datos" . $e->getMessage();
    }
}
