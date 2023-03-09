<?php

    //Conexion a la DB
    require 'includes/config/database.php';
    $db = conectarDB();


    //Autenticacion de usuario
    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = mysqli_real_escape_string($db,filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));

        $password = mysqli_real_escape_string($db,$_POST['password']);

        if(!$email) {
            $errores[] = 'El email es obligatorio o no es válido';
        }
        if(!$password) {
            $errores[] = 'El password es obligatorio';
        }
        if(empty($errores)) {
            //Revisar la existencia del usuario
            $query = "SELECT * FROM usuario WHERE email ='$email';";
            $resultado = mysqli_query($db, $query);


            if($resultado->num_rows) {
                //Si el usuario existe, revisamos si el password es correcto
                $usuario = mysqli_fetch_assoc($resultado);

                //Verificar el password
                $auth = password_verify($password, $usuario['password']);
               
                if($auth) {
                    //Password Correcto
                    session_start();

                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /bienesraices/admin/index.php');


                } else {
                    //Password Incorrecto
                    $errores[] = 'El password es incorrecto';
                }
            } else {
                $errores[] = 'El usuario no existe';
            }
        }
    }

    require 'includes/funciones.php';
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario">
            <fieldset>
                <legend>Email y Password</legend>


                <label for="email">E-mail</label>
                <input type="email" name= "email" placeholder="Tu Correo electrónico" id="email">

                <label for="password">Contraseña</label>
                <input type="password" name= "password" placeholder="Tu Password" id="password"> 

            </fieldset>

            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
    </main>
    

    <?php incluirTemplate('footer'); ?>