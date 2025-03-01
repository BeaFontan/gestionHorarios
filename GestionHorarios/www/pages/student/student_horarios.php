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

$arrayModules = [];
$sessionsWithModules = [];
$selectedCycle = $_POST['ciclo'] ?? null;

if ($selectedCycle) {
    $stmtModules = $pdo->prepare("
        SELECT m.id, m.module_code, m.module_acronym, m.classroom, m.color
        FROM modules m
        JOIN users_modules um ON m.id = um.module_id
        WHERE um.user_id = ? AND m.vocational_training_id = ?
    ");
    $stmtModules->execute([$userId, $selectedCycle]);
    $arrayModules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Horarios</title>
    <link rel="icon" type="image/png" href="../../images/icono.png">
    <link rel="stylesheet" href="../../pages/css/administrator_horarios.css">
    <link rel="stylesheet" href="../../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <h2>Horario</h2>

    <div class="container">
        <?php include_once('../partials/container_left.php') ?>

        <div class="container-rigth">
            <div class="container-buscador">
                <input type="text" id="checkMenu" value="0" hidden>
                <button onclick="menu()" class='btn-menu' name=''>
                    <img src='/images/menu.png' class='boton-icono-menu' alt='Menu'>
                </button>
                <form id="filter-form" style="initial: all; width: 100%;" method="post">
                    <!-- Seleccionar Ciclo -->
                    <select class="dropdownHorarios" name="ciclo" id="ciclo">
                        <option value="">Selecciona Ciclo</option>
                        <?php
                        foreach ($arrayCycles as $cycle) {
                            $selected = ($selectedCycle == $cycle['id']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($cycle['id']) . "' $selected>" . htmlspecialchars($cycle['course_name']) . "</option>";
                        }
                        ?>
                    </select>

                    <input type="hidden" name="selectedCiclo" id="selectedCiclo" value="<?php echo $selectedCycle; ?>">
                </form>
            </div>

            <!-- TABLA DE HORARIOS -->
            <div class="timetable" id="horario">
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
                        $formattedStartTime = date('G:i', strtotime($startTime));
                        $formattedEndTime = date('G:i', strtotime($sessionDays['end_time']));

                        echo "<tr>";
                        echo "<td class='horas'><b>{$formattedStartTime} - {$formattedEndTime}</b></td>";

                        foreach ($diasSemana as $day) {
                            $sessionId = $sessionDays[$day] ?? null;
                            $moduleName = "";
                            $moduleClass = "";
                            $moduleColor = "#ebeeeb8b";

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

            <div style="text-align: right; width: 100%; margin-top: 20px;">
                <button id="export-pdf" class="btnGuardar"><b>EXPORTAR A PDF</b></button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../../js/selector_menu.js"></script>

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


            const alumnoNombre = "<?php echo htmlspecialchars($_SESSION['user']['name']); ?>";

            document.getElementById("export-pdf").addEventListener("click", function() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF({
                    orientation: "landscape",
                    unit: "mm",
                    format: "a4"
                });

                const timetable = document.getElementById("horario");
                const cicloSeleccionado = cicloSelect.options[cicloSelect.selectedIndex].text;


                doc.setFontSize(18);
                doc.text("Horario del Alumno", 140, 20, null, null, "center");
                doc.setFontSize(12);
                doc.text("Alumno: " + alumnoNombre, 140, 30, null, null, "center");
                doc.text("Ciclo seleccionado: " + cicloSeleccionado, 140, 40, null, null, "center");


                const originalStyles = timetable.style.cssText;
                timetable.style.width = "1200px";
                timetable.style.maxWidth = "none";
                timetable.style.fontSize = "16px";


                html2canvas(timetable, {
                    scale: 2
                }).then(canvas => {

                    timetable.style.cssText = originalStyles;

                    const imgData = canvas.toDataURL("image/png");
                    const imgWidth = 280;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    doc.addImage(imgData, "PNG", 10, 50, imgWidth, imgHeight);
                    doc.save("horario_alumno.pdf");
                });
            });
        });
    </script>

    <script src="../../js/menu.js"></script>
</body>

</html>