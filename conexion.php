<?php

$server="localhost";
$user="root";
$passwd="271202";
$db_name="unex";

$conex = mysqli_connect($server,$user, $passwd,$db_name);
if ($conex->connect_error) {
    die("Error al conectar con la base de datos: " . $conex->connect_error);
}

?>