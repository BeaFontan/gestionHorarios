<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';

$arrayModules = [];

if (isset($_POST["btnGuardar"])) {
    try {
        $ciclo = $_POST['ciclo'] ?? null;
        $curso = $_POST['curso'] ?? null;

        if ($ciclo && $curso) {
            $stmtModules = $pdo->prepare("SELECT id FROM modules WHERE vocational_training_id = ? AND course = ?");
            $stmtModules->execute([$ciclo, $curso]);
            $modulesIds = $stmtModules->fetchAll(PDO::FETCH_COLUMN); 

            if (!empty($modulesIds)) {
                $idsString = implode(',', $modulesIds); 
            
                $sql = "DELETE FROM modules_sessions WHERE module_id IN ($idsString)";
                $rowsAffected = $pdo->exec($sql); 
            
                $stmtCheckRemaining = $pdo->query("SELECT * FROM modules_sessions WHERE module_id IN ($idsString)");
                $remainingRecords = $stmtCheckRemaining->fetchAll(PDO::FETCH_ASSOC);

            }
        }

        foreach ($_POST['modules'] as $sessionId => $moduleId) {
            if (!empty($moduleId) && is_numeric($moduleId) && is_numeric($sessionId)) {

        
                $stmtCheckSession = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE id = ?");
                $stmtCheckSession->execute([$sessionId]);
                $sessionExists = $stmtCheckSession->fetchColumn();

                if ($sessionExists > 0) {  
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM modules_sessions WHERE module_id = ? AND session_id = ?");
                    $stmtCheck->execute([$moduleId, $sessionId]);
                    $exists = $stmtCheck->fetchColumn();

                    if ($exists == 0) { 
                        $stmtInsert = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                        $stmtInsert->execute([$moduleId, $sessionId]);
                    } else {
                        
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
    <title>Horarios</title>
    <link rel="icon" type="image/png" href="../images/icono.png">
    <link rel="stylesheet" href="../pages/css/administrator_horarios.css">
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

    <h2>Xestión de Horarios</h2>

    <div class="container">
        <?php include_once('partials/container_left.php') ?>

        <div class="container-rigth">
            
            <input type="text" id="checkMenu" value="0" hidden>
            <button onclick="menu()" style="margin-top: 2%;" class='btn-menu-crear'>    
                <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
            </button>
             
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

            <form style="all:initial;" method="post">

                <input type="hidden" name="ciclo" id="cicloHidden">
                <input type="hidden" name="curso" id="cursoHidden">

                <div class="timetable">
                    <table>
                        <tr>
                            <th class="cabeceraSemanaBlanc"></th>
                            <th class="cabeceraSemana" data-dia="LUNES" data-inicial="L"></th>
                            <th class="cabeceraSemana" data-dia="MARTES" data-inicial="M"></th>
                            <th class="cabeceraSemana" data-dia="MIÉRCOLES" data-inicial="X"></th>
                            <th class="cabeceraSemana" data-dia="JUEVES" data-inicial="J"></th>
                            <th class="cabeceraSemana" data-dia="VIERNES" data-inicial="V"></th>
                        </tr>

                        <?php
                        $querySessions = $pdo->query("SELECT * FROM sessions ORDER BY day, start_time");
                        $arraySessions = $querySessions->fetchAll(PDO::FETCH_ASSOC);

                        $diasSemana = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                        $sessionsByTime = [];

                  
                        foreach ($arraySessions as $session) {
                            $sessionsByTime[$session['start_time']]['end_time'] = $session['end_time']; 
                            $sessionsByTime[$session['start_time']][$session['day']] = $session['id']; 
                        }

                        foreach ($sessionsByTime as $startTime => $sessionDays) {
                            $endTime = isset($sessionDays['end_time']) ? $sessionDays['end_time'] : '';

                            // Formateamos las horas
                            $formattedStartTime = date('G:i', strtotime($startTime));  
                            $formattedEndTime = !empty($endTime) ? date('G:i', strtotime($endTime)) : '';  

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
    <script src="../js/menu.js"></script>
</body>

</html>
