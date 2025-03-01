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
    <title>Engadir Profesor</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <input type="text" id="checkMenu" value="0" hidden>
    <button onclick="menu()" class='btn-menu' name=''>
        <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
    </button>

    <?php if (isset($_SESSION['mensaxe'])): ?>
        <div class="tooltip-container">
            <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
        </div>
        <?php unset($_SESSION['mensaxe']); ?>
    <?php endif; ?>

    <h2>Engadir Profesor</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <form style="width: 100%;" action="../functions/professors/function_create_professor.php" method="post">
                <br><br>

                <div>
                    <img src='/images/user.png' class='pic-crear' alt='Usuario img'>
                </div>

                <br><br>

                <div class="row-crear">
                    <input type="text" class='inputs-form-add' name="txtName" placeholder="Nome" required maxlength="50">
                    <input type="text" class='inputs-form-add' name="txtFirstName" placeholder="Apelido 1" required maxlength="50">
                </div>
                <div class="row-crear">
                    <input type="text" class='inputs-form-add' name="txtSecondName" placeholder="Apelido 2" maxlength="50">
                    <input type="email" class='inputs-form-add' name="txtEmail" placeholder="Email" required maxlength="100">
                </div>

                <div class="row-crear-guardar">
                    <button type="submit" class='btnActualizar' name="btnCreateProffesor" id="btnCreateUser">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/menu.js"></script>
</body>

</html>