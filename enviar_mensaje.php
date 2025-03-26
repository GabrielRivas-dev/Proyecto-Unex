<?php
session_start();
include("conexion.php");

// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit();
}

$emisor = $_SESSION['id'];
$chat_id = $_POST['chat_id'] ?? 0;
$mensaje = $_POST['mensaje'] ?? "";
$ruta_imagen = NULL;

// Obtener receptor_id del chat
$sql = "SELECT usuario1_id, usuario2_id FROM chats WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$result = $stmt->get_result();
$chat = $result->fetch_assoc();

if (!$chat) {
    echo json_encode(["success" => false, "message" => "Chat no encontrado"]);
    exit();
}

// Determinar quién es el receptor
$receptor = ($chat['usuario1_id'] == $emisor) ? $chat['usuario2_id'] : $chat['usuario1_id'];

// Verificar que los datos sean válidos
if ($chat_id == 0 || empty($mensaje) && empty($_FILES['archivo']['name']) || $receptor == 0) {
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit();
}

// Procesar archivo si se subió uno
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $directorio_destino = 'uploads/';
    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true); // Crear carpeta si no existe
    }

    $archivoNombre = basename($_FILES['archivo']['name']);
    $archivoExtension = pathinfo($archivoNombre, PATHINFO_EXTENSION);
    $ruta_imagen = $directorio_destino . time() . "_" . uniqid() . "." . $archivoExtension; // Nombre único

    // Validar tipo de archivo
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    if (!in_array(strtolower($archivoExtension), $tiposPermitidos)) {
        echo json_encode(["success" => false, "message" => "Tipo de archivo no permitido"]);
        exit();
    }

    // Mover archivo al servidor
    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_imagen)) {
        echo json_encode(["success" => false, "message" => "Error al mover el archivo"]);
        exit();
    }
}

// Insertar mensaje en la base de datos
$sql = "INSERT INTO mensajes (chat_id, emisor_id, receptor_id, mensaje, archivo, visto) VALUES (?, ?, ?, ?, ?, 0)";
$stmt = $conex->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la consulta SQL: " . $conex->error]);
    exit();
}

$stmt->bind_param("iiiss", $chat_id, $emisor, $receptor, $mensaje, $ruta_imagen);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error al ejecutar la consulta: " . $stmt->error]);
    exit();
}

echo json_encode(["success" => true, "archivo" => $ruta_imagen]);
?>
