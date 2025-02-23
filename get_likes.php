<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$usuario_id = $_SESSION['id'];
$sql = "SELECT publicacion_id FROM likes WHERE usuario_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$likes = [];
while ($row = $result->fetch_assoc()) {
    $likes[] = $row['publicacion_id'];
}

echo json_encode(['success' => true, 'likes' => $likes]);
?>