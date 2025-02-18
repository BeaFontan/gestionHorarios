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
            <div class="circle">
                <img src="/images/user.png" class="pic" alt="">
            </div>
            <h3>Nombre Apellidos</h3>
            <p>Administrador</p>

            <ul>
                <li><a href="#">ALUMNOS</a></li>
                <li><a href="#">CICLOS</a></li>
                <li><a href="#">MODULOS</a></li>
                <li><a href="#">HORARIOS</a></li>
            </ul>
            <br>
            <a href="../functions/user/close_session.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b></a>
        </div>

        <!-- Contenedor derecho -->

        <?php
        if (isset($_SESSION['mensaxe'])) {
            "<p>" . $_SESSION['mensaxe'] . "</p>";
        }
        ?>
        <div class="container-rigth">
        <div>
            <form action="../functions/administrator/function_create_user.php" method="post">
                <br><br>
                <div class='user-imagen'>
                    <img src='/images/user.png' class='pic' alt='Usuario img'>
                    <p style="font-size: 90px; margin-left: 10px;">+</p>
                </div>
                <div class="row">
                    <input type="text" class='inputs-form-add' name="txtName" placeholder="Nome" required maxlength="50">
                    <input type="text" class='inputs-form-add' name="txtFirstName" placeholder="Apelido 1" required maxlength="50">
                    <input type="text" class='inputs-form-add' name="txtSecondName" placeholder="Apelido 2" maxlength="50">
                </div>
                <div class="row">
                    <input type="email" class='inputs-form-add' name="txtEmail" placeholder="Email" required maxlength="100">
                    <input type="tel" class='inputs-form-add' name="txtPhone" placeholder="Teléfono" pattern="\d{15}" maxlength="15" required>
                    <input type="text" class='inputs-form-add' name="txtDNI" placeholder="DNI" required maxlength="9">
                </div>

                <div style='text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;'>
                    <button type='submit' class='btnActualizar' name='btnSave'><b>GUARDAR</b></button>
                </div>
            </form>
        </div>
        </div>
    </div>
</body>

</html>