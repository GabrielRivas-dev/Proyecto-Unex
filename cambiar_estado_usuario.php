<?php
session_start();
include("conexion.php");

// Solo admin puede ejecutar esto
if ($_SESSION['rol'] !== 'admin') {
  echo json_encode(["success" => false, "message" => "No autorizado"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$usuario_id = (int)($data['usuario_id'] ?? 0);
$estado = in_array($data['estado'], ['activo', 'suspendido']) ? $data['estado'] : 'activo';

$stmt = $conex->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $estado, $usuario_id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Estado actualizado a $estado"]);
