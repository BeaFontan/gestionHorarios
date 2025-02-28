<?php

function loadModules($pdo)
{
    try {
        $query = $pdo->query("SELECT * FROM `modules`");
        $query->execute();
        return $query->fetchAll();
    } catch (PDOException $e) {
        $_SESSION['mensaxe'] = "Erro buscando módulos" . $e->getMessage();
    }
}
