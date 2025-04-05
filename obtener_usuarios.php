<?php
include("conexion.php");

$sql = "SELECT id, nombre, apellido FROM usuarios";
$result = $conex->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
?>