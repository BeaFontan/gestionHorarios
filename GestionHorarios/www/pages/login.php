<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/style.css" />
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="titleLoginPass">
        <span class="raya">Login</span> <span class="negrita">IES</span> San
        Clemente
    </h1>
    <div class="circle">
        <img src="/images/user.png" class="imagen" alt="Usuario">
    </div>
    <?php
    if (isset($_GET['message'])) {
        echo '<p style="color: red;">' . $_GET['message'] . '</p>';
    }
    ?>
    <form action="../functions/user/function_verify_credentials.php" method="post">
        <input type="text" name="txtUser" id="txtUser" placeholder="Usuario" />

        <div class="password-container">
            <input
                type="password"
                name="txtPass"
                id="txtPass"
                placeholder="Contrasinal" />
            <button type="button" id="togglePassword">
                <i class="fa-solid fa-eye-slash"></i>
            </button>
        </div>

        <button type="submit" name="btnLogin" id="btnLogin">Entrar</button>
    </form>

    <a href="reset_password.php">¿Esqueceches a túa contrasinal?</a>

    <script src="../js/eye_pass.js"></script>
</body>

</html>