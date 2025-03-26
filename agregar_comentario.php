<?php
session_start();
include("conexion.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Validar los datos recibidos
if (!isset($data['publicacion_id']) || !isset($data['comentario'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

$usuario_id = $_SESSION['id'];
$publicacion_id = (int)$data['publicacion_id'];
$comentario = trim($data['comentario']);

$stmAutor = $conex->prepare("SELECT usuario_id FROM publicaciones WHERE id = ?");
$stmAutor->bind_param("i", $publicacion_id);
$stmAutor->execute();
$resultAutor = $stmAutor->get_result();
$publicacion = $resultAutor->fetch_assoc();

$autorPublicacion = $publicacion['usuario_id'];

// Verificar que el comentario no esté vacío
if (empty($comentario)) {
    echo json_encode(['success' => false, 'message' => 'El comentario no puede estar vacío']);
    exit();
}

// Insertar el comentario en la base de datos
$sql = "INSERT INTO comentarios (publicacion_id, usuario_id, comentario) VALUES (?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("iis", $publicacion_id, $usuario_id, $comentario);

$response = [];
if ($stmt->execute()) {
    // Obtener datos del usuario para enviar al frontend
    $sqlUsuario = "SELECT Nombre, Apellido, imagen FROM usuarios WHERE id = ?";
    $stmtUsuario = $conex->prepare($sqlUsuario);
    $stmtUsuario->bind_param("i", $usuario_id);
    $stmtUsuario->execute();
    $resultadoUsuario = $stmtUsuario->get_result()->fetch_assoc();

    $response['success'] = true;
    $response['message'] = 'Comentario agregado correctamente';
    $response['comentario'] = [
        'id' => $stmt->insert_id,
        'publicacion_id' => $publicacion_id,
        'comentario' => $comentario,
        'fecha' => date('Y-m-d H:i:s'),
        'usuario_nombre' => $resultadoUsuario['Nombre'],
        'usuario_apellido' => $resultadoUsuario['Apellido'],
        'usuario_imagen' => $resultadoUsuario['imagen']
    ];

    if ($usuario_id != $autorPublicacion) {
        $mensaje = $_SESSION['Nombre'] . $_SESSION['Apellido']. " comentó tu publicación.";
        $stmtNotificacion = $conex->prepare("INSERT INTO notificaciones (usuario_id, tipo, mensaje) VALUES (?, 'comentario', ?)");
        $stmtNotificacion->bind_param("is", $autorPublicacion, $mensaje);
        $stmtNotificacion->execute();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Error al agregar el comentario';
}

header('Content-Type: application/json');
echo json_encode($response);
?>