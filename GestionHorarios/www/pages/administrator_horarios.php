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
            $modulesIds = $stmtModules->fetchAll(PDO::FETCH_COLUMN); // Array con IDs de módulos

            if (!empty($modulesIds)) {
                $idsString = implode(',', $modulesIds);

                // Eliminar módulos previos
                $sql = "DELETE FROM modules_sessions WHERE module_id IN ($idsString)";
                $rowsAffected = $pdo->exec($sql);

                // Comprobar si quedan registros (opcional)
                $stmtCheckRemaining = $pdo->query("SELECT * FROM modules_sessions WHERE module_id IN ($idsString)");
                $remainingRecords = $stmtCheckRemaining->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // 3️⃣ 🔹 Guardar los módulos en la tabla modules_sessions
        foreach ($_POST['modules'] as $sessionId => $moduleId) {
            if (!empty($moduleId) && is_numeric($moduleId) && is_numeric($sessionId)) {
                // Verificar si la sesión existe
                $stmtCheckSession = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE id = ?");
                $stmtCheckSession->execute([$sessionId]);
                $sessionExists = $stmtCheckSession->fetchColumn();

                if ($sessionExists > 0) {
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM modules_sessions WHERE module_id = ? AND session_id = ?");
                    $stmtCheck->execute([$moduleId, $sessionId]);
                    $exists = $stmtCheck->fetchColumn();

                    if ($exists == 0) {
                        // Insertar si no existe
                        $stmtInsert = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                        $stmtInsert->execute([$moduleId, $sessionId]);
                    } else {
                        // Si ya existe, actualizar
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

// Obtener las sesiones y ordenarlas
$querySessions = $pdo->query("SELECT * FROM sessions ORDER BY day, start_time");
$arraySessions = $querySessions->fetchAll(PDO::FETCH_ASSOC);

$diasSemana = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
$sessionsByTime = [];

// Guardamos la info de cada sesión en sessionsByTime
foreach ($arraySessions as $session) {
    $sessionsByTime[$session['start_time']]['end_time'] = $session['end_time'];
    $sessionsByTime[$session['start_time']][$session['day']] = $session['id'];
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

    <!-- Estilos para mostrar/ocultar vistas y slider en móvil -->
    <style>
    /* ----- VISTAS ESCRITORIO Y MÓVIL ----- */
    @media screen and (min-width: 500px) {
        .vista-escritorio {
            display: block;
        }
        .vista-movil {
            display: none;
        }
    }
    @media screen and (max-width: 500px) {
        .vista-escritorio {
            display: none;
        }
        .vista-movil {
            display: block;
        }

        /* ----- ESTILOS DEL SLIDER MÓVIL ----- */
        .mobile-slider {
            position: relative;
            width: 100vw;
            overflow: hidden;
            margin: 0 auto;
        }
        .timetable-mobile-container {
            display: flex;
            flex-wrap: nowrap;
            width: 500%;
            transition: transform 0.4s ease-in-out;
        }
        .timetable-mobile-day {
            width: 100vw;
            flex-shrink: 0;
            padding: 15px;
            box-sizing: border-box;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin: 10px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        /* Título de cada día */
        .timetable-mobile-day h3 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
        }
        .timetable-mobile-day table {
            width: 100%;
            border-collapse: collapse;
        }
        .timetable-mobile-day th,
        .timetable-mobile-day td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ccc;
            font-size: 14px;
        }
        /* Puntos de navegación */
        .dots {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .dot {
            width: 12px;
            height: 12px;
            margin: 0 5px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .dot.active {
            background-color: #333;
        }
    }
    </style>
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

    <!-- =====================================
                 VISTA ESCRITORIO
         ===================================== -->
    <div class="vista-escritorio">
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
                </div>

                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCurso" name="curso" id="curso">
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
                        foreach ($sessionsByTime as $startTime => $sessionDays) {
                            $endTime = isset($sessionDays['end_time']) ? $sessionDays['end_time'] : '';
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
                </div> <!-- .timetable -->
            </form>
        </div>
    </div>
    <!-- Fin vista escritorio -->

    <!-- =====================================
         VISTA MÓVIL
         ===================================== -->
    <div class="vista-movil">
        <div class="container-rigth">

            <!-- FORM de filtros (ciclo, curso) en móvil -->
            <form id="filter-form-mobile" method="post">
                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCiclo" name="ciclo" id="cicloMobile">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        // Mismo query de ciclos
                        $queryCiclos = $pdo->query("SELECT id, course_name FROM vocational_trainings");
                        while ($ciclo = $queryCiclos->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($ciclo['id']) . "'>" . htmlspecialchars($ciclo['course_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCurso" name="curso" id="cursoMobile">
                        <option>Selecciona un curso</option>
                        <option value="first">Primer Año</option>
                        <option value="second">Segundo Año</option>
                    </select>
                </div>
            </form>

            <!-- FORM para guardar módulos en móvil -->
            <form method="post">
                <input type="hidden" name="ciclo" id="cicloHiddenMobile">
                <input type="hidden" name="curso" id="cursoHiddenMobile">

                <div class="mobile-slider">
                    <div class="timetable-mobile-container">
                        <?php
                        // Tarjetas, una por cada día
                        foreach ($diasSemana as $day) {
                            echo "<div class='timetable-mobile-day'>";
                            echo "<h3>" . strtoupper($day) . "</h3>";
                            echo "<table>";
                            echo "<tr><th>Hora</th><th>Módulo</th></tr>";

                            // Recorremos las horas
                            foreach ($sessionsByTime as $startTime => $sessionDays) {
                                if (isset($sessionDays[$day])) {
                                    $sessionId = $sessionDays[$day];
                                    $formattedStartTime = date('G:i', strtotime($startTime));
                                    $formattedEndTime = isset($sessionDays['end_time'])
                                        ? date('G:i', strtotime($sessionDays['end_time']))
                                        : '';

                                    echo "<tr>";
                                    // Columna de hora
                                    echo "<td><b>{$formattedStartTime} - {$formattedEndTime}</b></td>";

                                    // Columna del Módulo (select)
                                    echo "<td>";
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
                                    echo "</tr>";
                                }
                            }
                            echo "</table>";
                            echo "</div>"; // Fin .timetable-mobile-day
                        }
                        ?>
                    </div>

                    <!-- Puntos de navegación -->
                    <div class="dots"></div>
                </div> <!-- Fin .mobile-slider -->

                <!-- Botón GUARDAR en móvil -->
                <div style="text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;">
                    <button class="btnGuardar" type="submit" name="btnGuardar"><b>GUARDAR</b></button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin vista móvil -->

</div> <!-- .container -->

<!-- Tus scripts -->
<script src="../js/selector_menu.js"></script>
<script src="../js/modules.js"></script>

</body>
</html>
