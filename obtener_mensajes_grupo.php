<?php
include("conexion.php");

$grupo_id = $_GET['grupo_id'] ?? 0;

$sql = "SELECT mg.*, u.nombre, u.apellido 
        FROM mensajes_grupo mg
        JOIN usuarios u ON mg.emisor_id = u.id
        WHERE mg.grupo_id = ?
        ORDER BY mg.fecha ASC";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $grupo_id);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($mensajes);
?>
