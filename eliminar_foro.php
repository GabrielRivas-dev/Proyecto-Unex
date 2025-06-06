<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Si no está autenticado, redirigir al login
    header('Location: login.php');
    exit();
}
include("conexion.php");
$idUsuario = $_SESSION['id'];


$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se recibió el ID de la publicacion
if (!isset($data['foro_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del foro no proporcionado']);
    exit();
}
$foro_id = (int) $data['foro_id'];

// Verificar si la publicación pertenece al usuario autenticado
$stmt = $conex->prepare("SELECT id FROM foros WHERE id = ? AND creado_por = ?");
$stmt->bind_param("ii", $foro_id, $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
   $stmt = $conex->prepare("SELECT creado_por FROM foros WHERE id = ?");
$stmt->bind_param("i", $foro_id);
$stmt->execute();
$result = $stmt->get_result();
$foro = $result->fetch_assoc();

// Eliminar comentarios primero
$stmt = $conex->prepare("DELETE FROM comentarios_foro WHERE foro_id = ?");
$stmt->bind_param("i", $foro_id);
$stmt->execute();

// Eliminar seguidores
$stmt = $conex->prepare("DELETE FROM foro_seguidores WHERE foro_id = ?");
$stmt->bind_param("i", $foro_id);
$stmt->execute();

// Finalmente eliminar el foro
$stmt = $conex->prepare("DELETE FROM foros WHERE id = ?");
$stmt->bind_param("i", $foro_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo json_encode(["success" => true, "message" =>"foro eliminado correctamente"]);
} else {
  echo json_encode(["success" => false, "message" => "No se pudo eliminar el foro"]);
}
}
$stmt->close();
$conex->close();
?>