<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../../functions/connection.php';

$userId = $_SESSION['user']['id'];
$arrayCycles = [];

// 🔥 Obtener los ciclos en los que está inscrito el alumno
$stmtCycles = $pdo->prepare("
    SELECT vt.id, vt.course_name 
    FROM vocational_trainings vt
    JOIN users_vocational_trainings uvt ON vt.id = uvt.vocational_training_id
    WHERE uvt.user_id = ?
");
$stmtCycles->execute([$userId]);
$arrayCycles = $stmtCycles->fetchAll(PDO::FETCH_ASSOC);

// Obtener los módulos y sesiones del alumno según el ciclo seleccionado
$arrayModules = [];
$sessionsWithModules = [];
$selectedCycle = $_POST['ciclo'] ?? null;

if ($selectedCycle) {
    // 🔥 Obtener los módulos asignados al alumno dentro del ciclo seleccionado, incluyendo el color
    $stmtModules = $pdo->prepare("
        SELECT m.id, m.module_code, m.module_acronym, m.classroom, m.color
        FROM modules m
        JOIN users_modules um ON m.id = um.module_id
        WHERE um.user_id = ? AND m.vocational_training_id = ?
    ");
    $stmtModules->execute([$userId, $selectedCycle]);
    $arrayModules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

    // 🔥 Obtener las sesiones con módulos asignados dentro del ciclo
    $stmtSessions = $pdo->prepare("
        SELECT ms.session_id, m.module_acronym, m.classroom, m.color
        FROM modules_sessions ms
        JOIN modules m ON ms.module_id = m.id
        JOIN users_modules um ON m.id = um.module_id
        WHERE um.user_id = ? AND m.vocational_training_id = ?
    ");
    $stmtSessions->execute([$userId, $selectedCycle]);
    $sessionsWithModules = $stmtSessions->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Horarios - Alumnos</title>
    <link rel="stylesheet" href="../../pages/css/administrator_horarios.css">
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h2>Gestión de Horarios - Alumnos</h2>

    <div class="container">
        <?php include_once('../partials/container_left.php') ?>

        <div class="container-rigth">
            <form id="filter-form" method="post">
                <!-- Seleccionar Ciclo -->
                <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                    <select class="dropdownCiclo" name="ciclo" id="ciclo">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        foreach ($arrayCycles as $cycle) {
                            $selected = ($selectedCycle == $cycle['id']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($cycle['id']) . "' $selected>" . htmlspecialchars($cycle['course_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="selectedCiclo" id="selectedCiclo" value="<?php echo $selectedCycle; ?>">
            </form>

            <!-- TABLA DE HORARIOS -->
            <div class="timetable" id="horario">
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

                    foreach ($arraySessions as $session) {
                        $sessionsByTime[$session['start_time']]['end_time'] = $session['end_time'];
                        $sessionsByTime[$session['start_time']][$session['day']] = $session['id'];
                    }

                    foreach ($sessionsByTime as $startTime => $sessionDays) {
                        $formattedStartTime = date('G:i', strtotime($startTime));
                        $formattedEndTime = date('G:i', strtotime($sessionDays['end_time']));

                        echo "<tr>";
                        echo "<td class='horas'><b>{$formattedStartTime} - {$formattedEndTime}</b></td>";

                        foreach ($diasSemana as $day) {
                            $sessionId = $sessionDays[$day] ?? null;
                            $moduleName = "";
                            $moduleClass = "";
                            $moduleColor = "#ebeeeb8b"; // Color por defecto

                            if ($sessionId) {
                                foreach ($sessionsWithModules as $sessionModule) {
                                    if ($sessionModule['session_id'] == $sessionId) {
                                        $moduleName = htmlspecialchars($sessionModule['module_acronym']);
                                        $moduleClass = "Aula " . htmlspecialchars($sessionModule['classroom']);
                                        $moduleColor = !empty($sessionModule['color']) ? htmlspecialchars($sessionModule['color']) : "#ffffff";
                                        break;
                                    }
                                }
                            }

                            // Pintar la celda con el color del módulo
                            echo "<td style='background-color: {$moduleColor}; border-radius: 12px;' class='horas'>";
                            echo "<p>$moduleName </p>";
                            echo "<p style='font-size: 13px;'>$moduleClass</p>";
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>

            <!-- BOTÓN PARA EXPORTAR A PDF -->
            <div style="text-align: right; width: 100%; margin-top: 20px;">
                <button id="export-pdf" class="btnGuardar"><b>EXPORTAR A PDF</b></button>
            </div>
        </div>
    </div>

    <!-- Agregar jsPDF y html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../js/selector_menu.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const cicloSelect = document.getElementById("ciclo");
        const form = document.getElementById("filter-form");

        const selectedCiclo = document.getElementById("selectedCiclo").value;
        if (selectedCiclo) {
            cicloSelect.value = selectedCiclo;
        }

        cicloSelect.addEventListener("change", function() {
            form.submit();
        });

        // Obtener el nombre del alumno desde PHP sin mostrarlo en el HTML
        const alumnoNombre = "<?php echo htmlspecialchars($_SESSION['user']['name']); ?>";

        // Función para exportar a PDF con título y nombre del alumno
        document.getElementById("export-pdf").addEventListener("click", function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: "landscape", unit: "mm", format: "a4" });

            const timetable = document.getElementById("horario");
            const cicloSeleccionado = cicloSelect.options[cicloSelect.selectedIndex].text; // Obtener el ciclo seleccionado

            // Añadir el título y el nombre del alumno al PDF (pero NO en el HTML)
            doc.setFontSize(18);
            doc.text("Horario del Alumno", 140, 20, null, null, "center");
            doc.setFontSize(12);
            doc.text("Alumno: " + alumnoNombre, 140, 30, null, null, "center");
            doc.text("Ciclo seleccionado: " + cicloSeleccionado, 140, 40, null, null, "center");

            // Convertir la tabla a imagen y agregarla al PDF
            html2canvas(timetable, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const imgWidth = 280;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                doc.addImage(imgData, "PNG", 10, 50, imgWidth, imgHeight);
                doc.save("horario_alumno.pdf");
            });
        });
    });
</script>
</body>

</html>
