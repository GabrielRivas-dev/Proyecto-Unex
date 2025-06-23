<?php
session_start();
include("conexion.php");

if ($_SESSION['rol'] !== 'admin') {
  echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
  exit();
}

$sql = "SELECT r.id, r.tipo, r.reportado_id, r.motivo, r.estado, r.fecha,
               u.nombre AS reportante_nombre, u.apellido AS reportante_apellido
        FROM reportes r
        JOIN usuarios u ON r.reportado_por = u.id
        ORDER BY r.fecha DESC";

$resultado = $conex->query($sql);
$reportes = [];

while ($row = $resultado->fetch_assoc()) {
  $reportes[] = $row;
}

echo json_encode($reportes);
?>
