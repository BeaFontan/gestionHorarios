<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once "../connection.php";

if (isset($_POST["btnRessetPassword"])) {
    $txtPassNew1 = $_POST['txtPassNew1'];
    $txtPassNew2 = $_POST['txtPassNew2'];
    $password_reset = true;

    if (strcmp($txtPassNew1, $txtPassNew2) == 0) {

        $txtPassNew1Hash = password_hash($txtPassNew1, PASSWORD_DEFAULT);

        $sql = "UPDATE users 
        SET password = :password, 
            password_reset = :password_reset 
        WHERE name = :name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'password' => $txtPassNew1Hash,
            'password_reset' => $password_reset,
            'name' => $_SESSION['user']['name']
        ]);

        $_SESSION["mensaxe"] = "Contrasinal cambiado con éxito";
        header('Location: ../../pages/login.php');
        exit();
    } else {
        $_SESSION["mensaxe"] = "As contrainais non coinciden";
        header('Location: ../../pages/reset_password.php');
        exit();
    }
}
