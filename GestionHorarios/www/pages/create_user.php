<?php
include_once '../functions/connection.php';

$sql = "SELECT * FROM users ";
$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <!-- <link rel="stylesheet" href="../pages/css/style.css"> -->
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h2>Cambiar nombre con php</h2>
    <div class="container">
        <!-- Contenedor izquierdo -->
        <div class="container-left">
            <div class="circle"></div>
            <h3>Nombre Apellidos</h3>
            <p>Administrador</p>

            <ul>
                <li><a href="#">ALUMNOS</a></li>
                <li><a href="#">CICLOS</a></li>
                <li><a href="#">MODULOS</a></li>
                <li><a href="#">HORARIOS</a></li>
            </ul>
        </div>

        <!-- Contenedor derecho -->
        <div class="container-rigth">


        <form action="../functions/administrator/function_create_user.php" method="post">
            <input type="text" name="txtName" placeholder="Nome" >
            <input type="text" name="txtFirstName" placeholder="Apelido 1" >
            <input type="text" name="txtSecondName" placeholder="Apelido 2" >
            <input type="text" name="txtEmail" placeholder="Email" >
            <input type="text" name="txtPhone" placeholder="Teléfono" >
            <input type="text" name="txtDNI" placeholder="DNI" >

            <button type="submit" name="btnCreateUser" id="btnCreateUser">Guardar</button>
        </form>
        
            
        </div>
    </div>
</body>

</html>