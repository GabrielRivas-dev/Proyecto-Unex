<?php
session_start();
include("conexion.php");

// Verificar autenticación
if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit();
}

// Leer IDs desde el cuerpo JSON
$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['ids'] ?? [];

if (empty($ids)) {
    echo json_encode(["success" => false, "message" => "No se proporcionaron archivos a eliminar"]);
    exit();
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

// Obtener rutas de archivos a eliminar
$sql = "SELECT ruta FROM archivos_repositorio WHERE id IN ($placeholders)";
$stmt = $conex->prepare($sql);
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$rutas = [];
while ($row = $result->fetch_assoc()) {
    $rutas[] = $row['ruta'];
}

// Eliminar registros
$sql = "DELETE FROM archivos_repositorio WHERE id IN ($placeholders)";
$stmt = $conex->prepare($sql);
$stmt->bind_param($types, ...$ids);
$eliminado = $stmt->execute();

if ($eliminado) {
    // Borrar físicamente los archivos
    foreach ($rutas as $ruta) {
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "No se pudo eliminar"]);
}
?>
