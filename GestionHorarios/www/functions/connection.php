<?php

$server = "db";
$user = "root";
$pass = "root";
$bbdd = "Gestion_Horarios";

try {
    $pdo = new PDO("mysql:host=$server;dbname=$bbdd", $user, $pass);
} catch (PDOException $e) {
    // "erro na conexion " . $e->getMessage();

}
