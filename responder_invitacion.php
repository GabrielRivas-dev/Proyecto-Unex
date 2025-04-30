<?php
session_start();
include("conexion.php");

$usuarioId = $_SESSION['id'];
$invitacionId = $_POST['invitacion_id'];
$respuesta = $_POST['respuesta']; // 'aceptada' o 'rechazada'

// Obtener grupo_id
$stmt = $conex->prepare("SELECT grupo_id FROM invitaciones_grupo WHERE id = ? AND invitado_id = ?");
$stmt->bind_param("ii", $invitacionId, $usuarioId);
$stmt->execute();
$res = $stmt->get_result();
$inv = $res->fetch_assoc();

if (!$inv) {
    echo json_encode(["success" => false, "message" => "Invitación no encontrada"]);
    exit();
}

$grupoId = $inv['grupo_id'];

// Actualizar invitación
$stmt = $conex->prepare("UPDATE invitaciones_grupo SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $respuesta, $invitacionId);
$stmt->execute();

// Si aceptó, lo añadimos al grupo
if ($respuesta === 'aceptada') {
    $stmt = $conex->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $grupoId, $usuarioId);
    $stmt->execute();
}

echo json_encode(["success" => true, "message" => "Invitación " . $respuesta]);
?>
