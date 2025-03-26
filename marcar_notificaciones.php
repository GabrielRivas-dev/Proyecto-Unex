<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id'];

$sql = "UPDATE notificaciones SET leida = 1 WHERE usuario_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

echo json_encode(['success' => true]);
?>