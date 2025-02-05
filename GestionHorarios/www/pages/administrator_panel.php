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
            <input type="text" placeholder="Buscar alumno" name="txtFindUser">

            <button>Filtros</button>

            <div class="mostrar-users">
                <?php
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $name = $fila['name'];
                    $firstName = $fila['first_name'];
                    $secondName = $fila['second_name'];

                    echo " 
                    <div class='container-user'>
                        <div class='circle'></div>
                        <p>$name $firstName</p>
                        <p>ciclo</p>
                        <form>
                            <button name='btnUpdate'>editar</button>
                            <button name='btnDelete'>borrar</button>
                        </form>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="create_user.php">
        <button name="btnCreateUser" class="btnCreateUser">+</button>
    </form>


</body>

</html>