<?php
session_start();
include("conexion.php");

if ($_SESSION['rol'] !== 'admin') {
  echo json_encode(["success" => false, "message" => "Acceso denegado"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$reporte_id = (int)$data['reporte_id'];

$stmt = $conex->prepare("UPDATE reportes SET estado = 'resuelto' WHERE id = ?");
$stmt->bind_param("i", $reporte_id);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "Reporte marcado como resuelto"]);
} else {
  echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}
?>
