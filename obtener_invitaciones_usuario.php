<?php
session_start();
include("conexion.php");

$usuarioId = $_SESSION['id'];

// Traer todas las invitaciones pendientes
$sql = "
  SELECT ig.id AS invitacion_id, g.id AS grupo_id, g.nombre AS grupo_nombre, u.nombre AS invitador_nombre, u.apellido AS invitador_apellido
  FROM invitaciones_grupo ig
  JOIN grupos g ON ig.grupo_id = g.id
  JOIN usuarios u ON ig.invitado_por = u.id
  WHERE ig.invitado_id = ? AND ig.estado = 'pendiente'
";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

$invitaciones = [];
while ($row = $result->fetch_assoc()) {
    $invitaciones[] = $row;
}

echo json_encode($invitaciones);
?>
