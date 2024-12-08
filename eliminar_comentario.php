<?php
session_start();
include("conexion.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se recibió el ID del comentario
if (!isset($data['comentario_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del comentario no proporcionado']);
    exit();
}

$usuario_id = $_SESSION['id'];
$comentario_id = (int)$data['comentario_id'];

// Verificar si el comentario pertenece al usuario autenticado
$sql = "SELECT id FROM comentarios WHERE id = ? AND usuario_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $comentario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Eliminar el comentario
    $sqlDelete = "DELETE FROM comentarios WHERE id = ?";
    $stmtDelete = $conex->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $comentario_id);

    if ($stmtDelete->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comentario eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el comentario']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar este comentario']);
}
?>
