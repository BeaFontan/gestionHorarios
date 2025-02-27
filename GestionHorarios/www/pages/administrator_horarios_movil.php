<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include_once '../functions/connection.php';

// -------------------------------------------------
// 1) LÓGICA DE GUARDADO (MISMA QUE TU CÓDIGO ORIGINAL)
// -------------------------------------------------
if (isset($_POST["btnGuardar"])) {
    try {
        $ciclo = $_POST['ciclo'] ?? null;
        $curso = $_POST['curso'] ?? null;

        if ($ciclo && $curso) {
            // 1️⃣ Obtener los módulos asociados al ciclo y curso
            $stmtModules = $pdo->prepare("SELECT id FROM modules WHERE vocational_training_id = ? AND course = ?");
            $stmtModules->execute([$ciclo, $curso]);
            $modulesIds = $stmtModules->fetchAll(PDO::FETCH_COLUMN); // Array con IDs de módulos

            if (!empty($modulesIds)) {
                $idsString = implode(',', $modulesIds);
            
                // Borramos las asignaciones previas de esos módulos
                $sql = "DELETE FROM modules_sessions WHERE module_id IN ($idsString)";
                $pdo->exec($sql);

                // (Opcional) Comprobar si quedaron registros
                // $stmtCheckRemaining = $pdo->query("SELECT * FROM modules_sessions WHERE module_id IN ($idsString)");
                // $remainingRecords = $stmtCheckRemaining->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // 2️⃣ Guardar los módulos seleccionados en modules_sessions
        if (isset($_POST['modules'])) {
            foreach ($_POST['modules'] as $sessionId => $moduleId) {
                if (!empty($moduleId) && is_numeric($moduleId) && is_numeric($sessionId)) {
                    // Verificar que la sesión existe
                    $stmtCheckSession = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE id = ?");
                    $stmtCheckSession->execute([$sessionId]);
                    $sessionExists = $stmtCheckSession->fetchColumn();

                    if ($sessionExists > 0) {
                        // Revisamos si ya existe la asignación
                        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM modules_sessions WHERE module_id = ? AND session_id = ?");
                        $stmtCheck->execute([$moduleId, $sessionId]);
                        $exists = $stmtCheck->fetchColumn();

                        if ($exists == 0) {
                            // Insertamos
                            $stmtInsert = $pdo->prepare("INSERT INTO modules_sessions (module_id, session_id) VALUES (?, ?)");
                            $stmtInsert->execute([$moduleId, $sessionId]);
                        } else {
                            // O actualizamos si ya existe
                            $stmtUpdate = $pdo->prepare("UPDATE modules_sessions SET module_id = ? WHERE session_id = ?");
                            $stmtUpdate->execute([$moduleId, $sessionId]);
                        }
                    }
                }
            }
        }

        $_SESSION['mensaxe'] = "Datos guardados correctamente.";
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Error al guardar los datos: " . $e->getMessage();
    }
}

// -------------------------------------------------
// 2) CONSULTAS PARA MOSTRAR DATOS
// -------------------------------------------------

// A) Obtenemos la lista de ciclos (para el <select> de ciclo)
$queryCiclos = $pdo->query("SELECT id, course_name FROM vocational_trainings");
$ciclos = $queryCiclos->fetchAll(PDO::FETCH_ASSOC);

// B) Capturamos ciclo y curso seleccionados
$cicloSeleccionado = $_POST['ciclo'] ?? "";
$cursoSeleccionado = $_POST['curso'] ?? "";

// C) Obtenemos los módulos para ese ciclo+curso
$arrayModules = [];
if (!empty($cicloSeleccionado) && !empty($cursoSeleccionado)) {
    $stmtMods = $pdo->prepare("SELECT * FROM modules WHERE vocational_training_id = ? AND course = ?");
    $stmtMods->execute([$cicloSeleccionado, $cursoSeleccionado]);
    $arrayModules = $stmtMods->fetchAll(PDO::FETCH_ASSOC);
}

// D) Obtenemos las sesiones
$querySessions = $pdo->query("SELECT * FROM sessions ORDER BY day, start_time");
$arraySessions = $querySessions->fetchAll(PDO::FETCH_ASSOC);

// E) Mapear días en inglés a días en español
$diasMap = [
    'Monday'    => 'LUNES',
    'Tuesday'   => 'MARTES',
    'Wednesday' => 'MIÉRCOLES',
    'Thursday'  => 'JUEVES',
    'Friday'    => 'VIERNES'
];

// F) Agrupar las sesiones por día, manteniendo orden por hora
//    Para luego mostrarlas en una tabla "vertical" por día
$sessionsByDay = [
    'LUNES'     => [],
    'MARTES'    => [],
    'MIÉRCOLES' => [],
    'JUEVES'    => [],
    'VIERNES'   => []
];

foreach ($arraySessions as $session) {
    $dayName = $diasMap[$session['day']] ?? null;
    if ($dayName) {
        $sessionsByDay[$dayName][] = $session;
    }
}

// -------------------------------------------------
// 3) MOSTRAR EN PANTALLA (CON SWIPER) - 1 Slide por día
// -------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horarios (Móvil con Slider)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tus estilos CSS (ajusta según tu proyecto) -->
    <!-- <link rel="stylesheet" href="../pages/css/administrator_horarios.css"> -->
    <link rel="stylesheet" href="../pages/css/administrator_panel.css">
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <style>
    /* Ajusta los estilos a tu gusto o a tu diseño actual */
    body {
        margin: 0; 
        padding: 0; 
        font-family: Arial, sans-serif;
    }

    .container {
        display: flex;
        flex-direction: row;
        margin: 0;
    }

    /* Para que se vea algo de estructura */
    .container-rigth {
        flex: 1;
        padding: 20px;
    }

    .swiper {
        width: 100%;
        height: calc(100vh - 200px); /* Ajusta según tu header/footers */
    }

    .swiper-slide {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
        box-sizing: border-box;
        overflow-y: auto; /* scroll interno */
    }

    .dia-header {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* Tabla de cada día */
    .day-table {
        width: 100%;
        border-collapse: collapse;
        max-width: 400px; /* Ajusta si quieres limitar el ancho */
    }

    .day-table th, .day-table td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }

    .day-table th {
        background: #f2f2f2;
        font-weight: bold;
    }

    .day-table td select {
        width: 100%;
        padding: 6px;
        font-size: 1rem;
    }

    /* Paginación (los puntitos) */
    .swiper-pagination-bullet {
        background: #007bff;
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

<h2 style="text-align:center;">Horarios (Versión Móvil con Slider)</h2>

<div class="container">
    <?php include_once('partials/container_left.php'); ?>

    <div class="container-rigth">
        <!-- FORM FILTRO (ciclo y curso) -->
        <form id="filter-form" method="post" style="margin-bottom:20px; text-align:center;">
            <div style="margin-bottom:10px;">
                <select class="dropdownCiclo" name="ciclo" id="ciclo">
                    <option value="">Ciclo formativo</option>
                    <?php foreach ($ciclos as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['id']); ?>"
                            <?php if ($cicloSeleccionado == $c['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($c['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom:10px;">
                <select class="dropdownCurso" name="curso" id="curso">
                    <option value="">Selecciona un curso</option>
                    <option value="first"  <?php if ($cursoSeleccionado=='first')  echo 'selected'; ?>>Primer Año</option>
                    <option value="second" <?php if ($cursoSeleccionado=='second') echo 'selected'; ?>>Segundo Año</option>
                </select>
            </div>

            <button type="submit">Filtrar</button>
        </form>

        <!-- FORM PARA GUARDAR (asignar módulos) -->
        <form method="post">
            <!-- Ocultos para mantener el ciclo/curso en el POST -->
            <input type="hidden" name="ciclo" id="cicloHidden" value="<?php echo htmlspecialchars($cicloSeleccionado); ?>">
            <input type="hidden" name="curso" id="cursoHidden" value="<?php echo htmlspecialchars($cursoSeleccionado); ?>">

            <!-- Swiper Container -->
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php
                    // Recorremos cada día en el orden que nos interesa
                    $ordenDias = ['LUNES','MARTES','MIÉRCOLES','JUEVES','VIERNES'];
                    foreach ($ordenDias as $dia) {
                        $listaSesiones = $sessionsByDay[$dia] ?? [];
                        ?>
                        <div class="swiper-slide">
                            <div class="dia-header"><?php echo $dia; ?></div>
                            
                            <!-- Tabla con HORAS (columna 1) y SELECT (columna 2) -->
                            <?php if (!empty($listaSesiones)): ?>
                                <table class="day-table">
                                    <tr>
                                        <th>Hora</th>
                                        <th>Módulo</th>
                                    </tr>
                                    <?php foreach ($listaSesiones as $session): ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $start = date('G:i', strtotime($session['start_time']));
                                                $end   = date('G:i', strtotime($session['end_time']));
                                                echo "$start - $end";
                                                ?>
                                            </td>
                                            <td>
                                                <select name="modules[<?php echo $session['id']; ?>]">
                                                    <option value="">Selecciona Módulo</option>
                                                    <?php if (!empty($arrayModules)): ?>
                                                        <?php foreach ($arrayModules as $mod): ?>
                                                            <option value="<?php echo htmlspecialchars($mod['id']); ?>">
                                                                <?php echo htmlspecialchars($mod['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No hay módulos</option>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php else: ?>
                                <p>No hay sesiones para este día.</p>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </div>
                <!-- Paginación (puntitos) -->
                <div class="swiper-pagination"></div>
            </div>

            <!-- Botón GUARDAR -->
            <div style="text-align:center; margin-top:20px;">
                <button class="btnGuardar" type="submit" name="btnGuardar"><b>GUARDAR</b></button>
            </div>
        </form>
    </div>
</div>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- Tus scripts -->
<script src="../js/selector_menu.js"></script>
<script src="../js/modules.js"></script>

<script>
    // Inicializar Swiper
    var swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        }
    });

    // Sincronizar selects con inputs ocultos (por si lo necesitas)
    document.addEventListener("DOMContentLoaded", () => {
        const cicloSelect = document.getElementById("ciclo");
        const cursoSelect = document.getElementById("curso");
        const cicloHidden = document.getElementById("cicloHidden");
        const cursoHidden = document.getElementById("cursoHidden");

        function actualizarCicloCurso() {
            cicloHidden.value = cicloSelect.value;
            cursoHidden.value = cursoSelect.value;
        }

        if (cicloSelect && cursoSelect) {
            cicloSelect.addEventListener("change", actualizarCicloCurso);
            cursoSelect.addEventListener("change", actualizarCicloCurso);
        }
    });
</script>

</body>
</html>
