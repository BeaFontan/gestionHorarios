<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engadir Alumno</title>

    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <!-- <link rel="stylesheet" href="../pages/css/style.css"> -->
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h2>Engadir Alumno</h2>
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

        <?php
        if (isset($_SESSION['mensaxe'])) {
            "<p>" . $_SESSION['mensaxe'] . "</p>";
        }
        ?>
        <div class="container-rigth">
            <form action="../functions/administrator/function_create_user.php" method="post">
                <input type="text" name="txtName" placeholder="Nome" required maxlength="50">
                <input type="text" name="txtFirstName" placeholder="Apelido 1" required maxlength="50">
                <input type="text" name="txtSecondName" placeholder="Apelido 2" maxlength="50">
                <input type="email" name="txtEmail" placeholder="Email" required maxlength="100">
                <input type="tel" name="txtPhone" placeholder="Teléfono" pattern="\d{15}" maxlength="15" required>
                <input type="text" name="txtDNI" placeholder="DNI" required maxlength="9">

                <button type="submit" name="btnCreateUser" id="btnCreateUser">Guardar</button>
            </form>

        </div>
    </div>
</body>

</html>