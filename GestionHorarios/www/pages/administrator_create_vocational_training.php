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
        <p style="color:red; align-items: center;"><?php echo $_SESSION['mensaxe'];
        unset($_SESSION['mensaxe']); ?></p>
    <?php endif; ?>

    <h2>Engadir Ciclo</h2>
    
    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>


        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div>
                <form action="../functions/vocational_trainings/function_create_vocational_training.php" method="post">
                    <br><br>
                    <div class='user-imagen'>
                        <img src='/images/ciclo.png' class='pic' alt='Usuario img'>
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
    </div>
</body>

</html>