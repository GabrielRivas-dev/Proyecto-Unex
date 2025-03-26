<?php
session_start();
header('Content-Type: application/json');

include("conexion.php");

// Habilitar errores en PHP para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Obtener el JSON enviado desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$publicacionId = isset($data['publicacion_id']) ? (int)$data['publicacion_id'] : 0;
$idUsuario = $_SESSION['id'];

$stmAutor = $conex->prepare("SELECT usuario_id FROM publicaciones WHERE id = ?");
$stmAutor->bind_param("i", $publicacionId);
$stmAutor->execute();
$resultAutor = $stmAutor->get_result();
$publicacion = $resultAutor->fetch_assoc();

$autorPublicacion = $publicacion['usuario_id'];

if ($publicacionId <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de publicación inválido']);
    exit();
}

// Verificar si la publicación original existe
$stmt = $conex->prepare("SELECT contenido, imagensubida FROM publicaciones WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta SQL 1: ' . $conex->error]);
    exit();
}
$stmt->bind_param("i", $publicacionId);
$stmt->execute();
$result = $stmt->get_result();
$publicacion = $result->fetch_assoc();

if (!$publicacion) {
    echo json_encode(['success' => false, 'message' => 'Publicación original no encontrada']);
    exit();
}

// Insertar la publicación compartida

$sql = "INSERT INTO publicaciones (usuario_id, contenido, imagensubida) VALUES (?, ?, ?)";
    $stmt2 = $conex->prepare($sql);
    $stmt2->bind_param("iss", $idUsuario, $publicacion['contenido'], $publicacion['imagensubida']);
    if ($stmt2->execute()) {
        $countSql= "UPDATE publicaciones SET compartidos = compartidos + 1 WHERE id = ?";
        $countStmt = $conex->prepare($countSql);
        $countStmt->bind_param("i", $publicacionId);
        $countStmt->execute();
        $resultCount = $countStmt->get_result();
        echo json_encode(['success' => true, 'message' => 'Publicación compartida con éxito']);

        if ($idUsuario != $autorPublicacion) {
            $mensaje =  $_SESSION['Nombre'] . $_SESSION['Apellido']. " compartió tu publicación.";
            $stmtNotificacion = $conex->prepare("INSERT INTO notificaciones (usuario_id, tipo, mensaje) VALUES (?, 'compartir', ?)");
            $stmtNotificacion->bind_param("is", $autorPublicacion, $mensaje);
            $stmtNotificacion->execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al compartir la publicación: ' . $stmt->error]);
    }
    ?>

