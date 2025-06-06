<?php
include("conexion.php");
header("Content-Type: application/json");

$query = $_GET['query'] ?? '';
$query = "%$query%";

$stmt = $conex->prepare("SELECT p.*, u.nombre, u.apellido FROM productos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.titulo LIKE ? ORDER BY p.fecha DESC");
$stmt->bind_param("s", $query);
$stmt->execute();

$result = $stmt->get_result();
$productos = [];

while ($row = $result->fetch_assoc()) {
  $productos[] = $row;
}

echo json_encode($productos);
