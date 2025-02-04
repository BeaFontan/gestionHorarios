<?php
$password = "abc123"; // La contraseña que quieres encriptar

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Mostrar la contraseña encriptada
echo "La contraseña encriptada es: " . $hashed_password;
