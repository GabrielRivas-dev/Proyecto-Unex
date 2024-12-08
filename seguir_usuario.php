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
    echo json_encode(['success' => false, 'message' => 'ID del usuario a seguir no proporcionado']);
    exit();
}

$seguidor_id = $_SESSION['id']; // ID del usuario autenticado
$seguido_id = (int)$data['seguido_id'];

// Verificar que el usuario no se siga a sí mismo
if ($seguidor_id === $seguido_id) {
    echo json_encode(['success' => false, 'message' => 'No puedes seguirte a ti mismo']);
    exit();
}

// Insertar la relación de seguimiento en la base de datos
$sql = "INSERT INTO seguidores (seguidor_id, seguido_id) VALUES (?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $seguidor_id, $seguido_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Ahora sigues a este usuario';
} else {
    $response['success'] = false;
    $response['message'] = 'Error al seguir al usuario. Tal vez ya lo sigues.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>