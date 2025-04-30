<?php
session_start();
include("conexion.php");

$grupoId = $_POST['grupo_id'];
$invitadoId = $_POST['usuario_id'];
$invitadoPor = $_SESSION['id'];

// Evitar duplicados
$verificar = $conex->prepare("SELECT * FROM invitaciones_grupo WHERE grupo_id = ? AND invitado_id = ? AND estado = 'pendiente'");
$verificar->bind_param("ii", $grupoId, $invitadoId);
$verificar->execute();
if ($verificar->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Ya fue invitado"]);
    exit();
}

$stmt = $conex->prepare("INSERT INTO invitaciones_grupo (grupo_id, invitado_id, invitado_por) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $grupoId, $invitadoId, $invitadoPor);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Invitación enviada"]);
