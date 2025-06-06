<?php
session_start();
include("conexion.php");

$foroId = (int)$_POST['foro_id'];
$usuarioId = $_SESSION['id'];

$stmt = $conex->prepare("SELECT * FROM foro_seguidores WHERE foro_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $foroId, $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si ya sigue, dejar de seguir
    $stmt = $conex->prepare("DELETE FROM foro_seguidores WHERE foro_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $foroId, $usuarioId);
    $stmt->execute();
    echo json_encode(["success" => true, "following" => false]);
} else {
    // Seguir
    $stmt = $conex->prepare("INSERT INTO foro_seguidores (foro_id, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $foroId, $usuarioId);
    $stmt->execute();
    echo json_encode(["success" => true, "following" => true]);
}
?>
