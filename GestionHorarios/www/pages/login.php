<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../pages/css/style.css" />
</head>

<body>
    <h1>
        <span class="raya">Timetable</span> <span class="negrita">IES</span> San
        Clemente
    </h1>

    <div class="circle"></div>

    <form action="/prueba.php" method="get">
        <input type="text" name="txtUser" id="txtUser" placeholder="Usuario" />
        <input
            type="password"
            name="txtPass"
            id="txtPass"
            placeholder="Contrasinal" />
        <button type="submit" name="btnLogin" id="btnLogin">Entrar</button>
    </form>

    <a href="#">¿Esqueceches a túa contrasinal?</a>
</body>

</html>