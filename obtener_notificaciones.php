<?php
session_start();
include("conexion.php");

// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

$idUsuario = $_SESSION['id'];

// Obtener todas las notificaciones
$sql = "SELECT * FROM notificaciones WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}

// Contar solo las no leídas
$sqlCount = "SELECT COUNT(*) AS no_leidas FROM notificaciones WHERE usuario_id = ? AND leida = 0";
$stmt = $conex->prepare($sqlCount);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$countResult = $stmt->get_result();
$countRow = $countResult->fetch_assoc();
$notificacionesNoLeidas = $countRow['no_leidas'] ?? 0;

// Devolver las notificaciones y el contador de no leídas
echo json_encode([
    'notificaciones' => $notificaciones,
    'no_leidas' => $notificacionesNoLeidas
]);
?>
