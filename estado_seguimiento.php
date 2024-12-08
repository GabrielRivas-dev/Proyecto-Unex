<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

if (!isset($_GET['seguido_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del usuario seguido no proporcionado']);
    exit();
}

$seguidor_id = $_SESSION['id']; // Usuario autenticado
$seguido_id = (int)$_GET['seguido_id'];

// Verificar si el usuario sigue al otro
$sql = "SELECT * FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $seguidor_id, $seguido_id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode([
    'success' => true,
    'sigue' => $result->num_rows > 0 // Devuelve true si hay una relación de seguimiento
]);
?>
