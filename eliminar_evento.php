<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Si no está autenticado, redirigir al login
    header('Location: login.php');
    exit();
}
include("conexion.php");
$idUsuario = $_SESSION['id'];
$esAdmin = $_SESSION['rol'] === 'admin';


$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se recibió el ID de la publicacion
if (!isset($data['evento_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del evento no proporcionado']);
    exit();
}
$evento_id = (int) $data['evento_id'];

if ($esAdmin) {
    // El administrador puede eliminar cualquier publicación
    $stmt = $conex->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'evento eliminado por el administrador']);
    exit();
}

// Verificar si la publicación pertenece al usuario autenticado
$stmt = $conex->prepare("SELECT id FROM eventos WHERE id = ? AND creador_id = ?");
$stmt->bind_param("ii", $evento_id, $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // La publicación pertenece al usuario, proceder a eliminarla
    $delete_stmt = $conex->prepare("DELETE FROM eventos WHERE id = ?");
    $delete_stmt->bind_param("i", $evento_id);

    if ($delete_stmt->execute()) {
        // Mensaje de éxito
        echo json_encode(['success' => true, 'message' => 'evento eliminado con éxito.']);
    } else {
        // Mensaje de error
        echo json_encode(['success' => true, 'message' => 'Error al eliminar el evento.']);
    }
    $delete_stmt->close();
} else {
    // Mensaje de permiso denegado
    echo json_encode(['success' => true, 'message' => 'No tienes permiso para eliminar este evento.']);
}
$stmt->close();
$conex->close();
?>