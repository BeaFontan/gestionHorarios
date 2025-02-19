<?php
session_start();

include_once '../../functions/connection.php';
include_once '../../functions/administrator/find_vocational_trainings.php';
include_once '../../functions/administrator/load_modules.php';

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
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php if (isset($_SESSION['mensaxe'])): ?>
        <p style="color:red; align-items: center;"><?php echo $_SESSION['mensaxe'];
        unset($_SESSION['mensaxe']); ?></p>
    <?php endif; ?>

    <h2>Cambiar nombre con PHP</h2>

    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>


        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <form method="post" action="../functions/administrator/function_panel_administrator.php" id="search-form">
                <input type="text" id="buscar" placeholder="Buscar alumno" name="txtFindUser">
            </form>

            <!-- Botón de Filtros -->
            <button type="button" onclick="toggleFilters()">Filtros</button>

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

                    echo "<div class='container-user'>";
                    echo "<div class='circle'></div>";
                    echo "<p>$name $firstName</p>";
                    echo "<p>ciclo</p>";

                    if ($editUserId == $id) {
                        echo "
                        <form action='../functions/administrator/function_update_user.php' method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='text' name='txtName' value='$name' required><br>
                            <input type='text' name='txtFirstName' value='$firstName' placeholder='Primeiro Apelido' required><br>
                            <input type='text' name='txtSecondName' value='$secondName' placeholder='Segundo Apelido' ><br>
                            <input type='number' name='txtTelephone' value='$telephone' placeholder='Teléfono' ><br>
                            <input type='email' name='txtEmail' value='$email' placeholder='Email' ><br>
                            <input type='text' name='txtDNI' value='$dni' placeholder='DNI' required><br>

                            <button type='submit' name='btnSave'>Actualizar</button>
                        </form>
                        ";
                    } else {
                        echo "
                        <form method='post'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='hidden' name='name' value='$name'>
                            <input type='hidden' name='first_name' value='$firstName'>
                            <input type='hidden' name='second_name' value='$secondName'>
                            <input type='hidden' name='email' value='$email'>
                            <input type='hidden' name='telephone' value='$telephone'>
                            <input type='hidden' name='dni' value='$dni'>
                        
                            <button type='submit' name='btnUpdate'>
                                <i class='fas fa-edit'></i> 
                            </button>
                        </form>
                        ";
                    }

                    // Botón "Borrar"
                    echo "
                    <form method='post' action='../functions/administrator/function_delete_user.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' name='btnDelete'>    
                            <i class='fas fa-trash'></i> 
                        </button>
                    </form>";

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <form action="create_user.php">
        <button name="btnCreateUser" class="btnCreateUser">+</button>
    </form>

    <script src="../../js/find_user.js"></script>

</body>

</html>