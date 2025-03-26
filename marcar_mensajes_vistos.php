<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id']; // Usuario actual
$chatId = $_POST['chat_id'] ?? 0;

if ($chatId == 0) {
    echo json_encode(["success" => false, "message" => "ID de chat no válido"]);
    exit();
}

// Marcar mensajes como vistos solo si el usuario es el receptor
$sql = "UPDATE mensajes SET visto = 1 WHERE chat_id = ? AND receptor_id = ? AND visto = 0";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $chatId, $idUsuario);
$stmt->execute();

echo json_encode(["success" => true]);
?>