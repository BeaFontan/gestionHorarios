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
    <div class="tooltip-container">
        <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
    </div>
    <?php unset($_SESSION['mensaxe']); ?>
<?php endif; ?>

    <h2>Engadir Alumno</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>


        <!-- Contenedor derecho -->
        <div class="container-rigth">
                <form style="width: 100%;" action="../functions/administrator/function_create_user.php" method="post">
                    <br><br>
                    <div>
                        <img src='/images/user.png' class='pic-crear' alt='Usuario img'>
                        <!-- <p style="font-size: 90px; margin-left: 10px;">+</p> -->
                    </div>

                    <br><br>
                    
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
                        <input type="tel" class='inputs-form-add' name="txtPhone" placeholder="Teléfono" maxlength="15" minlength="9" required>
                    </div>

                    <div class="row-crear-guardar">
                    <button type="submit" class='btnActualizar' name="btnFormCreateUser" id="btnCreateUser">Guardar</button>
                    </div>
                </form>
        </div>
    </div>
</body>

</html>