<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

include("conexion.php");

$idUsuario = $_SESSION['id']; // ID del usuario autenticado

// Leer el JSON recibido
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['publicacion_id'])) {
    $publicacionId = (int)$data['publicacion_id'];

    // Verificar si el usuario ya dio like
    $stmt = $conex->prepare("SELECT id FROM likes WHERE publicacion_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $publicacionId, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ya dio like, eliminarlo
        $deleteStmt = $conex->prepare("DELETE FROM likes WHERE publicacion_id = ? AND usuario_id = ?");
        $deleteStmt->bind_param("ii", $publicacionId, $idUsuario);
        if ($deleteStmt->execute()) {
            // Contar los likes restantes
            $countStmt = $conex->prepare("SELECT COUNT(*) AS total FROM likes WHERE publicacion_id = ?");
            $countStmt->bind_param("i", $publicacionId);
            $countStmt->execute();
            $totalLikes = $countStmt->get_result()->fetch_assoc()['total'];

            echo json_encode(['success' => true, 'likes' => $totalLikes]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el like']);
        }
    } else {
        // No ha dado like, agregarlo
        $insertStmt = $conex->prepare("INSERT INTO likes (publicacion_id, usuario_id) VALUES (?, ?)");
        $insertStmt->bind_param("ii", $publicacionId, $idUsuario);
        if ($insertStmt->execute()) {
            // Contar los likes actualizados
            $countStmt = $conex->prepare("SELECT COUNT(*) AS total FROM likes WHERE publicacion_id = ?");
            $countStmt->bind_param("i", $publicacionId);
            $countStmt->execute();
            $totalLikes = $countStmt->get_result()->fetch_assoc()['total'];

            echo json_encode(['success' => true, 'likes' => $totalLikes]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar el like']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de publicación no proporcionado']);
}
?>