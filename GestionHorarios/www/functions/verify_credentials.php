<?php
session_start();
require_once "connection.php";

if (isset($_POST["btnLogin"])) {
    $user = $_POST['txtUser'];
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
            'pass' => $result['password']
        ];

        echo "¡Bienvenido, " . $result['name'] . "!";
    } else {
        header('Location: ../pages/login.php');
        exit();
    }
}



//TODO!!!! Todas las contraseñas van a estar haseadas?