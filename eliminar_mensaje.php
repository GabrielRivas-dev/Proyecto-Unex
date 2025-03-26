<?php
session_start();
include("conexion.php");

// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
$mensajeId = $data['mensaje_id'] ?? 0;
$idUsuario = $_SESSION['id'];

if ($mensajeId == 0) {
    echo json_encode(["success" => false, "message" => "ID de mensaje no válido"]);
    exit();
}

// Verificar que el mensaje pertenece al usuario
$sql = "DELETE FROM mensajes WHERE id = ? AND emisor_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $mensajeId, $idUsuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "No tienes permiso para eliminar este mensaje"]);
}
?>
