<?php
session_start();
include("conexion.php");

$grupo_id = $_POST['grupo_id'] ?? null;
$emisor_id = $_SESSION['id'] ?? null;
$mensaje = $_POST['mensaje'] ?? "";
$archivo = null;

if (!$grupo_id || !$emisor_id || (empty($mensaje) && empty($_FILES['archivo']))) {
    echo json_encode(["success" => false, "message" => "El mensaje o archivo no pueden estar vacíos"]);
    exit();
}

// Manejo de archivos
if (!empty($_FILES['archivo'])) {
    $nombreArchivo = time() . "_" . $_FILES['archivo']['name'];
    $rutaArchivo = "uploads/" . $nombreArchivo;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
        $archivo = $rutaArchivo;
    }
}

// Insertar mensaje en la base de datos
$sql = "INSERT INTO mensajes_grupo (grupo_id, emisor_id, mensaje, archivo) VALUES (?, ?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("iiss", $grupo_id, $emisor_id, $mensaje, $archivo);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al enviar mensaje"]);
}
?>
