<?php 

//Importar la conexion
require 'includes/config/database.php';
$db = conectarDB();

//Crear un email y password
$email = 'correo@correo.com';
$password = '123456';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);


//Query para crear el usuario
$query = "INSERT INTO usuario (email, password) VALUES ('$email', '$passwordHash');";

//Agregarlo a la DB
mysqli_query($db, $query);





