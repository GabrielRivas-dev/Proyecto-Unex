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
if (!isset($data['publicacion_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de la publicacion no proporcionado']);
    exit();}
        $publicacion_id = (int)$data['publicacion_id'];
    
        // Verificar si la publicación pertenece al usuario autenticado
        $stmt = $conex->prepare("SELECT id FROM publicaciones WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $publicacion_id, $idUsuario );
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // La publicación pertenece al usuario, proceder a eliminarla
            $delete_stmt = $conex->prepare("DELETE FROM publicaciones WHERE id = ?");
            $delete_stmt->bind_param("i", $publicacion_id);
            
            if ($delete_stmt->execute()) {
                // Mensaje de éxito
                echo json_encode(['success' => true, 'message' => 'Publicación eliminada con éxito.']);
            } else {
                // Mensaje de error
                echo json_encode(['success' => true, 'message' => 'Error al eliminar la publicación.']);
            }
            $delete_stmt->close();
        } else {
            // Mensaje de permiso denegado
            echo json_encode(['success' => true, 'message' => 'No tienes permiso para eliminar esta publicación.']);
        }
        $stmt->close();
        $conex->close();
    ?>