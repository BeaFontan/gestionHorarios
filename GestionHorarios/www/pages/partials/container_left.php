<?php
if (strcmp($_SESSION["user"]["rol"], "admin") == 0) {
    echo '<!-- Contenedor izquierdo -->
            <div id="contIzq" class="container-left">
                <div class="circle">
                    <img src="/images/user.png" class="pic-user" alt="Usuario">
                </div>
                
                <h3>' . $_SESSION['user']['name'] . '</h3>';

    if ($_SESSION['user']['rol'] == 'student') {
        echo '<p>Alumno</p>';
    } else {
        echo '<p>Administrativo</p>';
    }

    echo ' <ul>
                    <li><a href="administrator_panel.php">ALUMNOS</a></li>
                    <li><a href="administrator_professors.php">PROFESORES</a></li>
                    <li><a href="administrator_vocational_trainings.php">CICLOS</a></li>
                    <li><a href="administrator_modules.php">MODULOS</a></li>
                    <li><a href="administrator_horarios.php">HORARIOS</a></li>
                </ul>

                <br>
                
                <div style="margin-top: auto; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <a href="reset_password.php" class="logout">
                        <b>Cambiar contrasinal</b>
                    </a>
                    <br>
                    <a href="../../functions/user/close_session.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b>
                    </a>
                </div>
            </div>';
} else {
    echo '<!-- Contenedor izquierdo -->
            <div id="contIzq" class="container-left">
                <div class="circle">
                    <img src="/images/user.png" class="pic-user" alt="Usuario">
                </div>
                
                  <h3>' . $_SESSION['user']['name'] . '</h3>';

    if ($_SESSION['user']['rol'] == 'student') {
        echo '<p>Alumno</p>';
    } else {
        echo '<p>Administrativo</p>';
    };
    echo '<ul>
                    <li><a href="../student/student_vocational_trainings.php">CICLOS</a></li>
                    <li><a href="../student/student_modules.php">MODULOS</a></li>
                    <li><a href="../student/student_horarios.php">HORARIOS</a></li>
                </ul>

                <br>

                <div style="margin-top: auto; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <a href="../reset_password.php" class="logout">
                        <b>Cambiar contrasinal</b>
                    </a>
                    <br>
                    <a href="../../functions/user/close_session.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b>
                    </a>
                </div>
            </div>';
}
