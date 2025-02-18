<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';

function getProfessors($pdo)
{
    try {
        $query = $pdo->query("Select * from professors");
        $query->execute();
        return $query->fetchAll();
    
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro listando profesores" . $e->getMessage();
    }
}

function get_vocational_trainings($pdo)
{
    try {
        $query = $pdo->query("Select * from vocational_trainings");
        $query->execute();
        return $query->fetchAll();
    
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro listando ciclos" . $e->getMessage();
    }
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

    <h2>Engadir Módulo</h2>
    
    <div class="container">

        <!-- Contenedor izquierdo -->
        <?php include_once('partials/container_left.php') ?>

        <!-- Contenedor derecho -->
        <?php
        if (isset($_SESSION['mensaxe'])) {
            "<p>" . $_SESSION['mensaxe'] . "</p>";
        }
        ?>
        <div class="container-rigth">
            <form action="../functions/modules/function_create_modules.php" method="post">
                
                <select name="selectProfessor" required>
                    <option>Selecciona o profesor</option>
                    <?php
                    $arrayProfessors = getProfessors($pdo);

                    if (!empty($arrayProfessors)) {
                        foreach ($arrayProfessors as $professor) {
                            echo '<option value="'.$professor["id"].'">'.$professor["name"].' '.$professor["second_name"].'</option>';
                        }
                    } ?>
                </select>

                <select name="selectVocationTraining" required>
                    <option>Selecciona o Ciclo</option>
                    <?php
                    $arrayCiclos = get_vocational_trainings($pdo);

                    if (!empty($arrayCiclos)) {
                        foreach ($arrayCiclos as $ciclo) {
                            echo '<option value="'.$ciclo["id"].'">'.$ciclo["course_name"].'</option>';
                        }
                    } ?>
                </select>
                <input type="text" name="txtModuleCode" placeholder="Código módulo" required maxlength="50">
                <input type="text" name="txtName" placeholder="Nombre do módulo" maxlength="50">
                <select name="selectCourse">
                    <option value="first">Primeiro</option>
                    <option value="second">Segundo</option>
                </select>
                <input type="number" name="txtSessions" placeholder="Nº de sesións">

                <button type="submit" name="btnFormCreateModule" id="btnCreateUser">Guardar</button>
            </form>

        </div>
    </div>
</body>

</html>