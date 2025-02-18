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
    <h2>Engadir Ciclo</h2>
    <div class="container">
        <!-- Contenedor izquierdo -->
        <?php if (isset($_SESSION['mensaxe'])): ?>
            <p style="color:red;"><?php echo $_SESSION['mensaxe'];
                                    unset($_SESSION['mensaxe']); ?></p>
        <?php endif; ?>

            <!-- Contenedor izquierdo -->
            <div class="container-left">
                <div class="circle">
                    <img src="/images/user.png" class="pic" alt="">
                </div>
                <h3><?php echo $_SESSION['user']['name'] ?></h3>
                <p><?php echo $_SESSION['user']['rol'] ?></p>

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
            <?php
            if (isset($_SESSION['mensaxe'])) {
                "<p>" . $_SESSION['mensaxe'] . "</p>";
            }
            ?>
            <div class="container-rigth">
                <div>
                    <form action="../functions/vocational_trainings/function_create_vocational_training.php" method="post">
                        <br><br>
                        <div class='user-imagen'>
                            <img src='/images/asignatura.png' class='pic' alt='Usuario img'>
                            <p style="font-size: 90px; margin-left: 10px;">+</p>
                        </div>
                        <div class="row">
                            <input class='inputs-form-add' type="text" name="txtCourse_code" placeholder="Código ciclo" required maxlength="50">
                            <input class='inputs-form-add' type="text" name="txtAcronym" placeholder="Siglas" required maxlength="5">
                            <input class='inputs-form-add' type="text" name="txtName" placeholder="Nombre del Ciclo" maxlength="100">
                        </div>
                        <div class="row">
                            <select class='inputs-form-add' name="selectModality" required>
                                <option value="ordinary">Ordinario</option>
                                <option value="modular">Modular</option>
                                <option value="dual">Dual</option>
                            </select>
                            <select class='inputs-form-add' name="selectType" required>
                                <option value="medium">Medio</option>
                                <option value="higher">Superior</option>
                            </select>
                        </div>
                        <div style='text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;'>
                            <button type="submit" class='btnActualizar' name="btnCreateVocationalTraining" id="btnCreateUser">Guardar</button>
                        </div>
                    </form>
                </div>
        </div>
</body>

</html>