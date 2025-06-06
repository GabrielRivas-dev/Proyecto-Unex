<?php
session_start();
include("conexion.php");

header("Content-Type: application/json");

$idUsuario = $_SESSION['id'] ?? 0;
if ($idUsuario === 0) {
  echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
  exit();
}

$sql = "SELECT id, titulo, descripcion, precio, imagen, fecha FROM productos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$misProductos = [];
while ($row = $result->fetch_assoc()) {
  $misProductos[] = $row;
}

echo json_encode($misProductos);
