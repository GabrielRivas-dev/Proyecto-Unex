<?php
session_start();
include("conexion.php");

$grupoId = $_GET['grupo_id'] ?? 0;

// Obtener info del grupo
$stmt = $conex->prepare("SELECT g.id, g.nombre, g.imagen, g.creado_por, g.fecha_creacion, u.nombre AS creador_nombre, u.apellido AS creador_apellido
                         FROM grupos g 
                         JOIN usuarios u ON g.creado_por = u.id 
                         WHERE g.id = ?");
$stmt->bind_param("i", $grupoId);
$stmt->execute();
$result = $stmt->get_result();
$grupo = $result->fetch_assoc();

// Obtener miembros
$stmt = $conex->prepare("SELECT u.id, u.nombre, u.apellido, u.imagen
                         FROM grupo_usuarios gu 
                         JOIN usuarios u ON gu.usuario_id = u.id 
                         WHERE gu.grupo_id = ?");
$stmt->bind_param("i", $grupoId);
$stmt->execute();
$miembros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Respuesta completa
echo json_encode([
  "grupo" => $grupo,
  "miembros" => $miembros
]);
?>
