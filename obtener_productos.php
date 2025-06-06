<?php
header("Content-Type: application/json");
session_start();
include("conexion.php");

$sql = "SELECT p.id, p.usuario_id, p.titulo, p.descripcion, p.precio, p.imagen, p.fecha,
               u.nombre, u.apellido, u.imagen AS imagen_usuario
        FROM productos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$result = $conex->query($sql);

$productos = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}

echo json_encode($productos);
