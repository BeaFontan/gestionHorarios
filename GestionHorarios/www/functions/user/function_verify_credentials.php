<?php
session_start();
require_once "../connection.php";

if (isset($_POST["btnLogin"])) {
    $user = htmlspecialchars($_POST['txtUser']);
    $pass = $_POST['txtPass'];

    $sql = "SELECT * FROM users WHERE name=:name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':name' => $user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($pass, $result['password'])) {
        $_SESSION['user'] = [
            'id' => $result['id'],
            'name' => $result['name'],
            'pass' => $result['password'],
            'firstName' => $result['first_name'],
            'rol' => $result['rol']
        ];

        if ($result['password_reset'] != 1) {
            header('Location: ../../pages/reset_password.php');
            exit();
        } elseif (strcmp($_SESSION["user"]["rol"], "admin") == 0) {
            header('Location: ../../pages/administrator_panel.php');
            exit();
        } else {
            header('Location: ../../pages/student/student_vocational_trainings.php');
            exit();
        }
    } else {
        $message = "Usuario ou contrasinal incorrectos";
        header('Location: ../../pages/login.php?message=' . $message);
        exit();
    }
}
