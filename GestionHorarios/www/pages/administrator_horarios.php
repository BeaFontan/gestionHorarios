<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';

$arrayModules = [];

// Guardar los módulos en la tabla modules_sessions
if (isset($_POST["btnGuardar"])) {
    try {
        $ciclo = $_POST['ciclo'] ?? null;
        $curso = $_POST['curso'] ?? null;

        if ($ciclo && $curso) {
            // 1️⃣ 🔥 Obtener los módulos asociados al ciclo y curso seleccionados
            $stmtModules = $pdo->prepare("SELECT id FROM modules WHERE vocational_training_id = ? AND course = ?");
            $stmtModules->execute([$ciclo, $curso]);
            $modulesIds = $stmtModules->fetchAll(PDO::FETCH_COLUMN); // Array con los IDs de los módulos

            if (!empty($modulesIds)) {
                $idsString = implode(',', $modulesIds); // Convertir el array en una lista de valores separados por coma
            
                // 🔥 Ejecutar el DELETE de forma directa sin prepare()
                $sql = "DELETE FROM modules_sessions WHERE module_id IN ($idsString)";
                $rowsAffected = $pdo->exec($sql); // Usamos exec() en lugar de execute()
            
                // 🔥 Verificar si quedaron registros después del DELETE
                $stmtCheckRemaining = $pdo->query("SELECT * FROM modules_sessions WHERE module_id IN ($idsString)");
                $remainingRecords = $stmtCheckRemaining->fetchAll(PDO::FETCH_ASSOC);

            }
        }

        // 3️⃣ 🔹 Guardar los módulos en la tabla modules_sessions
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
                        $stmtInsert = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                        $stmtInsert->execute([$moduleId, $sessionId]);
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
            <form id="filter-form" style="all: initial;" method="post">
                <div class="container-drops">
                    <select class="dropdownCiclo" name="ciclo" id="ciclo">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        $queryCiclos = $pdo->query("SELECT id, course_name FROM vocational_trainings");
                        while ($ciclo = $queryCiclos->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($ciclo['id']) . "'>" . htmlspecialchars($ciclo['course_name']) . "</option>";
                        }
                        ?>
                    </select>
                    <select class="dropdownCiclo" name="curso" id="curso">
                        <option>Selecciona un curso</option>
                        <option value="first">Primer Año</option>
                        <option value="second">Segundo Año</option>
                    </select>
                </div>
            </form>

            <form method="post">

                <input type="hidden" name="ciclo" id="cicloHidden">
                <input type="hidden" name="curso" id="cursoHidden">

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

                            // Formateamos las horas
                            $formattedStartTime = date('G:i', strtotime($startTime));  // Convierte la hora a formato 24h sin ceros a la izquierda
                            $formattedEndTime = !empty($endTime) ? date('G:i', strtotime($endTime)) : '';  // Aplica lo mismo para la hora de fin

                            echo "<tr>";
                            echo "<td class='horas'><b>{$formattedStartTime} - {$formattedEndTime}</b></td>";

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
    <script src="../js/modules.js"></script>
</body>

</html>
