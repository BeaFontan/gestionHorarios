<?php
session_start();

include_once '../functions/connection.php';

$sql = "SELECT * FROM professors";
$stmt = $pdo->query($sql);

$editUserId = null;
$name = "";
$firstName = "";
$secondName = "";

// Si se ha presionado el botón "Editar"
if (isset($_POST["btnUpdate"])) {
    $editUserId = $_POST["id"];
    $name = $_POST["name"];
    $firstName = $_POST["first_name"];
    $secondName = $_POST["second_name"];
    $email = $_POST["email"];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <div class="tooltip-container">
            <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
        </div>
        <?php unset($_SESSION['mensaxe']); ?>
    <?php endif; ?>

    <h2>Profesores</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div class="container-buscador">
                <input type="text" id="checkMenu" value="0" hidden>
                <button onclick="menu()" class='btn-menu' name=''>
                    <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
                </button>
                <form method="post" style="all:initial; width: 100%;" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar profesor" name="txtFindProfessor">
                </form>
            </div>

            <div class="mostrar-profesores">
                <?php
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $fila['id'];
                    $name = $fila['name'];
                    $firstName = $fila['first_name'];
                    $secondName = $fila['second_name'];
                    $email = $fila['email'];

                    echo "<div class='container-user'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/user.png' class='pic' alt='Usuario img'>
                                </div>
                              <div class='user-texto'>
                                    <p class='texto-nombre'>$name $firstName $secondName</p>
                                    <p class='texto-ciclo'>$email</p>
                                </div>";

                    if ($editUserId == $id) {
                        echo "
                                <div class='user-botones'>
                                    <form method='post' action='../functions/administrator/function_delete_user.php'>
                                        <input type='hidden' name='id' value='$id'>
                                        <button type='submit' class='btn-delete' name='btnDelete'>    
                                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <div class='user-editar'>
                            <form action='../functions/professors/function_update_professor.php' style='all:initial; width: 100%;' method='post'>
                                <div class='row-edit'>
                                    <input type='hidden' class='inputs-form' name='id' value='$id'>
                                    <input type='text' class='inputs-form-2' name='txtName' value='$name' required><br>
                                    <input type='text' class='inputs-form-2' name='txtFirstName' value='$firstName' placeholder='Primeiro Apelido' required><br>
                                </div>
                                <div class='row-edit'>
                                    <input type='text' class='inputs-form-2' name='txtSecondName' value='$secondName' placeholder='Segundo Apelido' ><br>
                                    <input type='email' class='inputs-form-2' name='txtEmail' value='$email' placeholder='Email' ><br>
                                </div>

                                <div class='row-guardar'>
                                    <button type='submit' class='btnActualizar' name='btnSave'>Actualizar</button>
                                </div>
                            </form>
                        </div>
                        ";
                    } else {
                        echo "
                        <div class='user-botones'>
                            <form method='post'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='first_name' value='$firstName'>
                                <input type='hidden' name='second_name' value='$secondName'>
                                <input type='hidden' name='email' value='$email'>
                                
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>
                            <form method='post' action='../../functions/professors/function_delete_professor.php'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' class='btn-delete' name='btnDelete'>    
                                    <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                    <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                </button>
                            </form>
                        </div>
                    </div>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="administrator_create_professor.php">
        <button name="btnCreateProffesor" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_professors.js"></script>
    <script src="../js/selector_menu.js"></script>
    <script src="../js/menu.js"></script>

</body>

</html>