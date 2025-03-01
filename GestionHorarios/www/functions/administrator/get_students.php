<?php
include_once '../connection.php';

$alumnos = [];

$editUserId = isset($_POST['editUserId']) ? $_POST['editUserId'] : null;

if (isset($_POST["ciclo"]) && !empty($_POST["ciclo"])) {
    $cicloId = $_POST["ciclo"];

    $query = "
        SELECT u.id, u.name, u.first_name, u.second_name, u.dni, u.email, u.telephone 
        FROM users u
        INNER JOIN users_vocational_trainings uv ON u.id = uv.user_id
        WHERE uv.vocational_training_id = ? AND u.rol = 'student'
    ";

    $stmtAlumnos = $pdo->prepare($query);
    $stmtAlumnos->execute([$cicloId]);
    $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);
} else {
    $query = "
        SELECT u.id, u.name, u.first_name, u.second_name, u.dni, u.email, u.telephone 
        FROM users u
        WHERE u.rol = 'student'
    ";

    $stmtAlumnos = $pdo->query($query);
    $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);
}

if (!empty($alumnos)) {

    foreach ($alumnos as $fila) {
        $id = htmlspecialchars($fila['id']);
        $name = htmlspecialchars($fila['name']);
        $firstName = htmlspecialchars($fila['first_name']);
        $secondName = htmlspecialchars($fila['second_name']);
        $telephone = htmlspecialchars($fila['telephone']);
        $dni = htmlspecialchars($fila['dni']);
        $email = htmlspecialchars($fila['email']);

        echo "<div class='container-user'>
                <div class='row'>
                    <div class='user-imagen'>
                        <img src='/images/user.png' class='pic' alt='Usuario img'>
                    </div>
                    <div class='user-texto'>
                        <p class='texto-nombre'>$name $firstName $secondName</p>
                        <p class='texto-ciclo'><strong>DNI: </strong>$dni</p>
                        <p class='texto-ciclo' style='font-size: 14px;'><strong>Email:</strong> $email - <strong>Teléfono:</strong> $telephone</p>
                    </div>";

        if ($editUserId == $id) {
            echo "
                </div>
                <div class='user-editar'>
                    <form action='../functions/administrator/function_update_user.php' style='all:initial; width: 100%;' method='post'>
                        <input type='hidden' name='id' value='$id'>
                        <div class='row-edit'>
                            <input type='text' class='inputs-form' name='txtName' value='$name' maxlength='50' required><br>
                            <input type='text' class='inputs-form' name='txtFirstName' value='$firstName' placeholder='Primer Apellido' maxlength='50' required><br>
                            <input type='text' class='inputs-form' name='txtSecondName' value='$secondName' placeholder='Segundo Apellido' maxlength='50'><br>
                        </div>
                        <div class='row-edit'>
                            <input type='number' class='inputs-form' name='txtTelephone' value='$telephone' placeholder='Teléfono'><br>
                            <input type='email' class='inputs-form' name='txtEmail' value='$email' placeholder='Email' maxlength='100'><br>
                            <input type='text' class='inputs-form' name='txtDNI' value='$dni' placeholder='DNI' maxlength='9' required><br>
                        </div>
                        <div class='row-guardar'>
                            <button type='submit' class='btnActualizar' name='btnSave'>Actualizar</button>
                        </div>
                    </form>
                </div>";
        } else {
            echo "
                <div class='user-botones'>
                    <form method='post'>
                                <input type='hidden' name='id' value='$id'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='first_name' value='$firstName'>
                                <input type='hidden' name='second_name' value='$secondName'>
                                <input type='hidden' name='email' value='$email'>
                                <input type='hidden' name='telephone' value='$telephone'>
                                <input type='hidden' name='dni' value='$dni'>
                            
                                
                                <button type='submit' class='btn' name='btnUpdate'>
                                    <img src='/images/edit.png' class='boton-icono-edit' alt='Editar'>
                                    <img src='/images/edit_hover.png' class='edit-hover' alt='Editar'>
                                </button>
                            </form>
                    <form method='post' action='../functions/administrator/function_delete_user.php'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' class='btn-delete' name='btnDelete'>    
                            <img src='/images/delete.png' class='boton-icono-delete' alt='Borrar'>
                            <img src='/images/delete_hover.png' class='delete-hover' alt='Borrar'>
                        </button>
                    </form>
                </div>
            </div>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No hay alumnos disponibles.</p>";
}
