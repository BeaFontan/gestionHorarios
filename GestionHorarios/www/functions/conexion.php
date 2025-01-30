<?php

$server = "db";
$user = "root";
$pass = "root";
$bbdd = "Gestion_Horarios";

try {
    $pdo = new PDO("mysql:host=$server;dbname=$bbdd", $user, $pass);

    //echo "conexion ok!";
} catch (PDOException $e) {
    echo "erro na conexion " . $e->getMessage();
}
