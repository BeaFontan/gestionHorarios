<?php
session_start();
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

        header('Location: ../../pages/login.php');
        exit();
    } else {
        $message = "As contrainais non coinciden";
        header('Location: ../../pages/reset_password.php?message=' . $message);
        exit();
    }
}
