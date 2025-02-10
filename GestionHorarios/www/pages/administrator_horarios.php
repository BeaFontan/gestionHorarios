<?php
include_once '../functions/connection.php';

$sql = "SELECT * FROM users ";
$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../pages/css/administrator_horarios.css">
    <!-- <link rel="stylesheet" href="../pages/css/style.css"> -->
    <script src="https://kit.fontawesome.com/d685d46b6c.js" crossorigin="anonymous"></script>
</head>

<body>
    <h2>Cambiar nombre con php</h2>
    <div class="container">
        <!-- Contenedor izquierdo -->
        <div class="container-left">
            <div class="circle"></div>
            <h3>Nombre Apellidos</h3>
            <p>Administrador</p>

            <ul>
                <li><a href="#">ALUMNOS</a></li>
                <li><a href="#">CICLOS</a></li>
                <li><a href="#">MODULOS</a></li>
                <li><a href="#">HORARIOS</a></li>
            </ul>
        </div>

        <!-- Contenedor derecho -->
        <div class="container-rigth">
            <div style="text-align: center; margin-bottom: 20px; width: 100%;">
                <select class="dropdownCiclo">
                    <option value="opcion1">Opción 1</option>
                    <option value="opcion2">Opción 2</option>
                    <option value="opcion3">Opción 3</option>
                    <option value="opcion4">Opción 4</option>
                </select>
            </div>

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
                    <tr>
                        <td class="horas"><b>8:45 - 9:35</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>9:35 - 10:25</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>10:25 - 11:15</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>11:15 - 12:05</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>12:05 - 12:55</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>12:55 - 13:45</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>13:45 - 14:35</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <hr>
                <table>
                    <tr>
                        <td class="horas"><b>16:00 - 16:50</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>16:50 - 17:40</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>17:40 - 18:30</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="horas"><b>18:30 - 19:20</b></td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                        <td class="dropdownModulo">
                            <select class="dropdownModulo">
                                <option value="opcion1">Opción 1</option>
                                <option value="opcion2">Opción 2</option>
                                <option value="opcion3">Opción 3</option>
                                <option value="opcion4">Opción 4</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="text-align: right; width: 100%; margin-top: 30px; margin-bottom: 30px;">
                <button class="btnGuardar" type="button"><b>GUARDAR</b></button>
            </div>
        </div>
    </div>

</body>

</html>