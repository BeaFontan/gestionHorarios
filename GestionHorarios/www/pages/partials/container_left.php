<?php
if (strcmp($_SESSION["user"]["rol"], "admin") == 0) {
    echo '<!-- Contenedor izquierdo -->
            <div class="container-left">
                <div class="circle">
                    <img src="/images/user.png" class="pic-user" alt="Usuario">
                </div>
                
                <h3>'.$_SESSION['user']['name'].'</h3>
                <p>'.$_SESSION['user']['rol'].'</p>

                <ul>
                    <li><a href="administrator_panel.php">ALUMNOS</a></li>
                    <li><a href="administrator_vocational_trainings.php">CICLOS</a></li>
                    <li><a href="administrator_modules.php">MODULOS</a></li>
                    <li><a href="administrator_horarios.php">HORARIOS</a></li>
                </ul>

                <br>
                <div style="margin-top: auto;">
                    <a href="../functions/user/close_session.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b>
                    </a>
                </div>
            </div>';
}else {
    echo '<!-- Contenedor izquierdo -->
            <div class="container-left">
                <div class="circle">
                    <img src="/images/user.png" class="pic-user" alt="Usuario">
                </div>
                
                <h3>'.$_SESSION['user']['name'].'</h3>
                <p>'.$_SESSION['user']['rol'].'</p>

                <ul>
                    <li><a href="../student/student_vocational_trainings.php">CICLOS</a></li>
                    <li><a href="../student/student_modules.php">MODULOS</a></li>
                    <li><a href="../student/student_horarios.php">HORARIOS</a></li>
                </ul>

                <br>

                <div style="margin-top: auto;">
                    <a href="../functions/user/close_session.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i> <b>Cerrar sesión</b>
                    </a>
                </div>
            </div>';
    
}



?>