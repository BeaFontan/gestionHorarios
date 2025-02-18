<?php
session_start();

include_once '../functions/connection.php';
include_once '../functions/administrator/find_vocational_trainings.php';
include_once '../functions/administrator/load_modules.php';

$arrayVocationalTrainings = findVocationalTrainings($pdo);

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

// Inicializamos variables
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
    $phone = $_POST["telephone"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h2>Alumnos</h2>
    <div class="container">

        <?php
        if (isset($_SESSION['mensaxe'])) {
            "<p>" . $_SESSION['mensaxe'] . "</p>";
        }
        ?>
        <!-- Contenedor izquierdo -->
        <div class="container-left">
            <div class="circle">
                <img src="/images/user.png" class="pic" alt="">
            </div>
            <h3><?php echo $_SESSION['user']['name']?></h3>
            <p><?php echo $_SESSION['user']['rol']?></p>

            <ul>
                <li><a href="administrator_panel.php">ALUMNOS</a></li>
                <li><a href="administrator_vocational_trainings.php">CICLOS</a></li>
                <li><a href="administrator_modules.php">MODULOS</a></li>
                <li><a href="administrator_horarios.php">HORARIOS</a></li>
            </ul>
            <br>
            <a href="../functions/user/close_session.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b></a>
        </div>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                <form method="post" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                    <input class="buscador" type="text" id="buscar" placeholder="Buscar alumno" name="txtFindUser">
                </form>
                <!-- Botón de Filtros -->
                <button class="btnFiltrar" type="button" onclick="toggleFilters()"><i class="fa fa-filter" style="margin-right: 5px;" aria-hidden="true"></i> Filtros</button>
            </div>

            <!-- Contenedor de los filtros, inicialmente oculto -->
            <div id="filters" style="display:none;">
                <form id="filter-form">
                    <label for="ciclo">Selecciona Ciclo</label>
                    <select name="ciclo" id="ciclo" onchange="loadModulos(this.value)">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        if ($arrayVocationalTrainings) {
                            foreach ($arrayVocationalTrainings as $ciclo) {
                                echo "<option value='" . $ciclo['vocational_training_id'] . "'>" . $ciclo["course_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <label for="modulo">Selecciona Módulo</label>
                    <select name="modulo" id="modulo">
                        <option value="">Selecciona Módulo</option>
                    </select>
                </form>
            </div>
            <div class="mostrar-users">
                <?php
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $fila['id'];
                    $name = $fila['name'];
                    $firstName = $fila['first_name'];
                    $secondName = $fila['second_name'];
                    $telephone = $fila['second_name'];
                    $dni = $fila['dni'];
                    $email = $fila['email'];

                    echo "<div class='container-user'>
                            <div class='row'>
                                <div class='user-imagen'>
                                    <img src='/images/user.png' class='pic' alt='Usuario img'>
                                </div>
                              <div class='user-texto'>
                                    <p class='texto-nombre'>$name $firstName </p>
                                    <p class='texto-ciclo'>ciclo</p>
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
                            <form action='../functions/administrator/function_update_user.php' method='post'>
                                <div class='row' style='margin-left: 50px;'>
                                    <input type='hidden' class='inputs-form' name='id' value='$id'>
                                    <input type='text' class='inputs-form' name='txtName' value='$name' required><br>
                                    <input type='text' class='inputs-form' name='txtFirstName' value='$firstName' placeholder='Primeiro Apelido' required><br>
                                    <input type='text' class='inputs-form' name='txtSecondName' value='$secondName' placeholder='Segundo Apelido' ><br>
                                </div>
                                <div class='row' style='margin-left: 50px;'>
                                    <input type='number' class='inputs-form' name='txtTelephone' value='$telephone' placeholder='Teléfono' ><br>
                                    <input type='email' class='inputs-form' name='txtEmail' value='$email' placeholder='Email' ><br>
                                    <input type='text' class='inputs-form' name='txtDNI' value='$dni' placeholder='DNI' required><br>
                                </div>

                                <div style='text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;'>
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
                                <input type='hidden' name='telephone' value='$telephone'>
                                <input type='hidden' name='dni' value='$dni'>
                            
                                
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>
                            <form method='post' action='../functions/administrator/function_delete_user.php'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' class='btn-delete' name='btnDelete'>    
                                    <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                                    <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                                </button>
                            </form>
                        </div>
                    </div>

                        ";
                        
                    }

                    // Botón "Borrar"
                   /* echo "<form method='post' action='../functions/administrator/function_delete_user.php'>
                   <i class='fas fa-edit'></i>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' name='btnDelete'>    
                                <i class='fas fa-trash'></i> 
                            </button>
                        </form>
                    </div>";*/

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="create_user.php">
        <button name="btnCreateUser" class="btnCreateUser">+</button>
    </form>

    <script src="../js/find_user.js"></script>

</body>

</html>