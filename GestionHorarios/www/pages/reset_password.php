<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resetear contrasinal</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/style.css" />
</head>

<body>
    <h1>
        <span class="raya">Cambiar Contrasinal</span> <span class="negrita">IES</span> San
        Clemente
    </h1>

    <div class="circle"></div>
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <div class="tooltip-container">
            <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
        </div>
        <?php unset($_SESSION['mensaxe']); ?>
    <?php endif; ?>

    <form action="../functions/user/function_reset_password.php" method="post">
        <input
            type="password"
            name="txtPassNew1"
            id="txtPass"
            placeholder="Nova contrasinal" />

        <input
            type="password"
            name="txtPassNew2"
            id="txtPass"
            placeholder="Repite a nova contrasinal" />

        <button type="submit" name="btnRessetPassword" id="btnRessetPassword">Cambiar contrasinal</button>
    </form>

    <a href="login.php">Login</a>
</body>

</html>