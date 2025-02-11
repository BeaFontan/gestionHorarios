<?php
session_start();
require_once "../connection.php";

if (isset($_POST["btnLogin"])) {
    $user = htmlspecialchars($_POST['txtUser']);
    $pass = $_POST['txtPass'];

    // Preparar la consulta para buscar al usuario por nombre
    $sql = "SELECT * FROM users WHERE name=:name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':name' => $user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña es válida
    if ($result && password_verify($pass, $result['password'])) {
        $_SESSION['user'] = [
            'name' => $result['name'],
            'pass' => $result['password'],
            'firstName' => $result['first_name']
        ];

        if ($result['password_reset'] != 1) {
            header('Location: ../../pages/reset_password.php');
            exit();
        } else {
            echo "¡Bienvenido, " . $result['name'] . "!"; //Redigir al panel de user
        }
    } else {
        $message = "Usuario ou contrasinal incorrectos";
        header('Location: ../../pages/login.php?message=' . $message);
        exit();
    }
}
