<?php
session_start();
include("conexion.php");

$data = json_decode(file_get_contents("php://input"), true);
$tipo = $data['tipo'];
$reportado_id = (int)$data['reportado_id'];
$motivo = $data['motivo'];
$reportado_por = $_SESSION['id'];

$stmt = $conex->prepare("INSERT INTO reportes (tipo, reportado_id, motivo, reportado_por) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sisi", $tipo, $reportado_id, $motivo, $reportado_por);
echo json_encode(["success" => $stmt->execute()]);
?>