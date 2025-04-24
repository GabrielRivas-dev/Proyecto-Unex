<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id'];
$chatId = $_POST['chat_id'] ?? 0;

if (!$chatId) {
    echo json_encode(["success" => false, "message" => "ID de chat faltante"]);
    exit();
}

// Obtener info del chat
$sql = "SELECT usuario1_id, usuario2_id, usuario1_oculto, usuario2_oculto FROM chats WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $chatId);
$stmt->execute();
$result = $stmt->get_result();
$chat = $result->fetch_assoc();

if (!$chat) {
    echo json_encode(["success" => false, "message" => "Chat no encontrado"]);
    exit();
}

if ($idUsuario == $chat['usuario1_id']) {
    $stmt = $conex->prepare("UPDATE chats SET usuario1_oculto = 1 WHERE id = ?");
} elseif ($idUsuario == $chat['usuario2_id']) {
    $stmt = $conex->prepare("UPDATE chats SET usuario2_oculto = 1 WHERE id = ?");
} else {
    echo json_encode(["success" => false, "message" => "No tienes acceso a este chat"]);
    exit();
}

$stmt->bind_param("i", $chatId);
$stmt->execute();

// Verificar si ambos ocultaron
if ($chat['usuario1_oculto'] == 1 && $chat['usuario2_oculto'] == 1) {
    $conex->query("DELETE FROM mensajes WHERE chat_id = $chatId");
    $conex->query("DELETE FROM chats WHERE id = $chatId");
    echo json_encode(["success" => true, "message" => "Chat eliminado completamente"]);
    exit();
}

echo json_encode(["success" => true, "message" => "Chat ocultado"]);
?>
