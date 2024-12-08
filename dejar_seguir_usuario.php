<?php
session_start();
include("conexion.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Si no está autenticado, redirigir al login
    header('Location: login.php');
    exit();
  }

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['seguido_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del usuario a dejar de seguir no proporcionado']);
    exit();
}

$seguidor_id = $_SESSION['id'];
$seguido_id = (int)$data['seguido_id'];

// Eliminar la relación de seguimiento en la base de datos
$sql = "DELETE FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $seguidor_id, $seguido_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Has dejado de seguir a este usuario';
} else {
    $response['success'] = false;
    $response['message'] = 'Error al dejar de seguir al usuario';
}

header('Content-Type: application/json');
echo json_encode($response);
?>