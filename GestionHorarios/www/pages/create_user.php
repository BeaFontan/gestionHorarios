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
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <p style="color:red; align-items: center;"><?php echo $_SESSION['mensaxe'];
        unset($_SESSION['mensaxe']); ?></p>
    <?php endif; ?>

    <h2>Engadir Alumno</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>


        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div style="display: flex; flex-wrap: wrap; width: 80%">
                <form action="../functions/administrator/function_create_user.php" method="post">
                    <br><br>
                    <div>
                        <img src='/images/user.png' class='pic' alt='Usuario img'>
                        <p style="font-size: 90px; margin-left: 10px;">+</p>
                    </div>
                    <div class="row-crear">
                        <input type="text" class='inputs-form-add' name="txtName" placeholder="Nome" required maxlength="50">
                        <input type="text" class='inputs-form-add' name="txtFirstName" placeholder="Apelido 1" required maxlength="50">
                    </div>
                    <div class="row-crear">
                        <input type="text" class='inputs-form-add' name="txtSecondName" placeholder="Apelido 2" maxlength="50">
                        <input type="text" class='inputs-form-add' name="txtDNI" placeholder="DNI" required maxlength="9">
                    </div>
                    <div class="row-crear">
                        <input type="email" class='inputs-form-add' name="txtEmail" placeholder="Email" required maxlength="100">
                        <input type="tel" class='inputs-form-add' name="txtPhone" placeholder="Teléfono" pattern="\d{15}" maxlength="15" required>
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