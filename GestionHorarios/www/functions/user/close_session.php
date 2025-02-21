<?php
session_start();

unset($_SESSION["user"]);
unset($_SESSION["mensaxe"]);
session_destroy();


header('Location: ../../pages/login.php');
exit();
