<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';
include_once '../functions/administrator/find_vocational_trainings.php';

$arrayVocationalTrainings = findVocationalTrainings($pdo);
$arrayModules = [];

// Obtener los módulos según el ciclo seleccionado
if (isset($_POST["btnMostrarCiclos"]) && !empty($_POST["ciclo"])) {
    $vocational_training = $_POST["ciclo"];

    try {
        $query = $pdo->prepare("SELECT id, name, color FROM `modules` WHERE vocational_training_id = ?");
        $query->execute([$vocational_training]);
        $arrayModules = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error al obtener módulos: " . $e->getMessage();
    }
}

// Guardar los módulos en la tabla modules_sessions
if (isset($_POST["btnGuardar"])) {
    try {
        foreach ($_POST['modules'] as $sessionId => $dayModules) {
            foreach ($dayModules as $dayIndex => $moduleId) {
                if (!empty($moduleId) && is_numeric($moduleId) && is_numeric($sessionId)) {

                    // Verificar si ya existe la combinación antes de insertar
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM modules_sessions WHERE module_id = ? AND session_id = ?");
                    $stmtCheck->execute([$moduleId, $sessionId]);
                    $exists = $stmtCheck->fetchColumn();

                    if ($exists == 0) { // Solo insertamos si no existe
                        $stmt = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                        $stmt->execute([$moduleId, $sessionId]);
                    }
                }
            }
        }
        $_SESSION['mensaxe'] = "Datos guardados correctamente.";
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error al guardar los datos: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Horarios</title>
    <link rel="stylesheet" href="../pages/css/administrator_horarios.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
<?php if (isset($_SESSION['mensaxe'])): ?>
    <div class="tooltip-container">
        <span class="error-tooltip"><?php echo $_SESSION['mensaxe']; ?></span>
    </div>
    <?php unset($_SESSION['mensaxe']); ?>
<?php endif; ?>

    <h2>Xestión de Horarios</h2>

    <div class="container">
        <div class="container-left">
            <div class="circle"></div>
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
                <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión </b></a>
        </div>

        <div class="container-rigth">
            <form id="filter-form" method="post">
                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCiclo" name="ciclo" id="ciclo">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        if ($arrayVocationalTrainings) {
                            foreach ($arrayVocationalTrainings as $ciclo) {
                                echo "<option value='" . htmlspecialchars($ciclo['id']) . "'>" . htmlspecialchars($ciclo['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <button class="btnBuscar" type="submit" name="btnMostrarCiclos">Mostrar módulos</button>
                </div>
            </form>

            <form method="post">
                <div class="timetable">
                    <table>
                        <tr>
                            <th class="cabeceraSemanaBlanc"></th>
                            <th class="cabeceraSemana">LUNES</th>
                            <th class="cabeceraSemana">MARTES</th>
                            <th class="cabeceraSemana">MIÉRCOLES</th>
                            <th class="cabeceraSemana">JUEVES</th>
                            <th class="cabeceraSemana">VIERNES</th>
                        </tr>

                        <?php
                        // Definir horas y sus IDs correspondientes (session_id)
                        $sessions = [
                            1 => "8:45 - 9:35",
                            2 => "9:35 - 10:25",
                            3 => "10:25 - 11:15",
                            4 => "11:15 - 12:05",
                            5 => "12:05 - 12:55",
                            6 => "12:55 - 13:45",
                            7 => "13:45 - 14:35",
                            8 => "16:00 - 16:50",
                            9 => "16:50 - 17:40",
                            10 => "17:40 - 18:30",
                            11 => "18:30 - 19:20"
                        ];

                        foreach ($sessions as $sessionId => $hora) {
                            echo "<tr>";
                            echo "<td class='horas'><b>$hora</b></td>";

                            for ($i = 0; $i < 5; $i++) { // 5 columnas (Lunes a Viernes)
                                echo "<td class='dropdownModulo'>";
                                echo "<select name='modules[$sessionId][$i]' class='dropdownModulo'>";
                                echo "<option value=''>Selecciona Módulo</option>";

                                if (!empty($arrayModules)) {
                                    foreach ($arrayModules as $module) {
                                        echo "<option value='" . htmlspecialchars($module['id']) . "' data-color='" . htmlspecialchars($module['color']) . "'>" . htmlspecialchars($module['name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay módulos</option>";
                                }
                                echo "</select>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>

                    <div style="text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;">
                        <button class="btnGuardar" type="submit" name="btnGuardar"><b>GUARDAR</b></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   <script src="../js/option_color.js"></script>
   <script src="../js/selector_menu.js"></script>
</body>

</html>
