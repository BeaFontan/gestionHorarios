<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';

$arrayModules = [];

// Obtener los módulos según el ciclo seleccionado
if (isset($_POST["btnMostrarCiclos"]) && !empty($_POST["ciclo"])) {
    $vocational_training = $_POST["ciclo"];
    $curso = $_POST["curso"] ?? "first"; // Por defecto, "first"

    try {
        $query = $pdo->prepare("SELECT id, name, color, sessions_number FROM `modules` WHERE vocational_training_id = ? AND course = ?");
        $query->execute([$vocational_training, $curso]);
        $arrayModules = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error al obtener módulos: " . $e->getMessage();
    }
}

// Guardar los módulos en la tabla modules_sessions
if (isset($_POST["btnGuardar"])) {
    try {
        foreach ($_POST['modules'] as $sessionId => $moduleId) {
            if (!empty($moduleId) && is_numeric($moduleId) && is_numeric($sessionId)) {

                // Verificar si la sesión realmente existe en la base de datos
                $stmtCheckSession = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE id = ?");
                $stmtCheckSession->execute([$sessionId]);
                $sessionExists = $stmtCheckSession->fetchColumn();

                if ($sessionExists > 0) {  // Si la sesión existe en la BD
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM modules_sessions WHERE module_id = ? AND session_id = ?");
                    $stmtCheck->execute([$moduleId, $sessionId]);
                    $exists = $stmtCheck->fetchColumn();

                    if ($exists == 0) { // Solo insertamos si no existe
                        $stmt = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                        $stmt->execute([$moduleId, $sessionId]);
                    } else {
                        // Si ya existe, actualizarlo en caso de que haya cambiado
                        $stmtUpdate = $pdo->prepare("UPDATE modules_sessions SET module_id = ? WHERE session_id = ?");
                        $stmtUpdate->execute([$moduleId, $sessionId]);
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
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
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
        <?php include_once('partials/container_left.php') ?>

        <div class="container-rigth">
            <form id="filter-form" method="post">
                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCiclo" name="ciclo" id="ciclo">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        $queryCiclos = $pdo->query("SELECT id, course_name FROM vocational_trainings");
                        while ($ciclo = $queryCiclos->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($ciclo['id']) . "'>" . htmlspecialchars($ciclo['course_name']) . "</option>";
                        }
                        ?>
                    </select>

                    <select class="dropdownCurso" name="curso" id="curso">
                        <option value="first">Primer Año</option>
                        <option value="second">Segundo Año</option>
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
                        $querySessions = $pdo->query("SELECT * FROM sessions ORDER BY day, start_time");
                        $arraySessions = $querySessions->fetchAll(PDO::FETCH_ASSOC);
                        
                        $diasSemana = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                        $sessionsByTime = [];
                        
                        // Guardamos la información de cada sesión en sessionsByTime
                        foreach ($arraySessions as $session) {
                            $sessionsByTime[$session['start_time']]['end_time'] = $session['end_time']; // Guardamos la hora de fin
                            $sessionsByTime[$session['start_time']][$session['day']] = $session['id'];  // Guardamos la sesión por día
                        }
                        
                        foreach ($sessionsByTime as $startTime => $sessionDays) {
                            $endTime = isset($sessionDays['end_time']) ? $sessionDays['end_time'] : ''; // Obtenemos la hora de fin correspondiente
                            echo "<tr>";
                        
                            // ✅ Ahora la celda muestra start_time - end_time
                            echo "<td class='horas'><b>{$startTime} - {$endTime}</b></td>";
                        
                            foreach ($diasSemana as $day) {
                                echo "<td class='dropdownModulo'>";
                                $sessionId = isset($sessionDays[$day]) ? $sessionDays[$day] : null;
                        
                                if ($sessionId) {
                                    echo "<select name='modules[$sessionId]' class='dropdownModulo'>";
                                    echo "<option value=''>Selecciona Módulo</option>";
                        
                                    if (!empty($arrayModules)) {
                                        foreach ($arrayModules as $module) {
                                            echo "<option value='" . htmlspecialchars($module['id']) . "' 
                                                        data-color='" . htmlspecialchars($module['color']) . "' 
                                                        data-max-sessions='" . htmlspecialchars($module['sessions_number']) . "'>"
                                                        . htmlspecialchars($module['name']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay módulos</option>";
                                    }
                                    echo "</select>";
                                } else {
                                    echo "<p>Sin sesión</p>";
                                }
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

    <script src="../js/selector_menu.js"></script>
</body>
</html>
