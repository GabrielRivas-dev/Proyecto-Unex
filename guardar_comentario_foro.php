<?php
session_start();
include("conexion.php");

$foro_id = $_POST['foro_id'] ?? 0;
$comentario = trim($_POST['comentario'] ?? '');
$usuario_id = $_SESSION['id'] ?? 0;
$parent_id = $_POST['parent_id'] ?? null;

if ($foro_id == 0 || empty($comentario) || $usuario_id == 0) {
  echo json_encode(["success" => false, "message" => "Datos inválidos"]);
  exit();
}



$stmt = $conex->prepare("INSERT INTO comentarios_foro (foro_id, usuario_id, comentario, parent_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $foro_id, $usuario_id, $comentario, $parent_id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
