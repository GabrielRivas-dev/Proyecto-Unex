<?php
session_start();
include("conexion.php");

$usuarioActual = $_SESSION['id'];
$receptor = $_GET['receptor_id'] ?? 0;

if ($receptor == 0) {
    echo json_encode(["error" => "ID del receptor no válido"]);
    exit();
}

// Buscar si ya existe un chat entre los usuarios
$sql = "SELECT id FROM chats WHERE (usuario1_id = ? AND usuario2_id = ?) OR (usuario1_id = ? AND usuario2_id = ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("iiii", $usuarioActual, $receptor, $receptor, $usuarioActual);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $chat_id = $row['id'];
} else {
    // Si no existe, crear un nuevo chat
    $stmt = $conex->prepare("INSERT INTO chats (usuario1_id, usuario2_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $usuarioActual, $receptor);
    $stmt->execute();
    $chat_id = $stmt->insert_id;
}

// Obtener la información del receptor
$sql = "SELECT nombre, apellido, imagen FROM usuarios WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $receptor);
$stmt->execute();
$result = $stmt->get_result();
$receptorInfo = $result->fetch_assoc();

// Devolver el chat_id junto con la información del receptor
echo json_encode([
    "chat_id" => $chat_id,
    "receptor" => [
        "id" => $receptor,
        "nombre" => $receptorInfo['nombre'],
        "apellido" => $receptorInfo['apellido'],
        "imagen" => $receptorInfo['imagen']
    ]
]);
?>
