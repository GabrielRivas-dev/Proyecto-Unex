<?php
session_start();
include("conexion.php");

$chat_id = $_GET['chat_id'] ?? 0;

if ($chat_id == 0) {
    echo json_encode(["error" => "ID del chat no válido"]);
    exit();
}

// Obtener mensajes del chat
$sql = "SELECT id, emisor_id, mensaje, archivo, visto FROM mensajes WHERE chat_id = ? ORDER BY fecha ASC";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $row['archivo'] = $row['archivo'] ? "uploads/" . basename($row['archivo']) : null;
    $mensajes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($mensajes);
?>
