<?php
session_start();
include("conexion.php");

$usuario_id = $_SESSION['id'] ?? 0;
$repo_id = $_GET['repo_id'] ?? 0;

if (!$usuario_id || !$repo_id) {
    die("Acceso denegado.");
}

// Verificar que el repositorio pertenezca al usuario
$stmt = $conex->prepare("SELECT id FROM repositorios WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $repo_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("No tienes permiso para eliminar este repositorio.");
}

// Obtener archivos a eliminar
$stmt = $conex->prepare("SELECT ruta FROM archivos_repositorio WHERE repositorio_id = ?");
$stmt->bind_param("i", $repo_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if (file_exists($row['ruta'])) {
        unlink($row['ruta']); // Eliminar archivo físico
    }
}

// Eliminar registros de archivos
$stmt = $conex->prepare("DELETE FROM archivos_repositorio WHERE repositorio_id = ?");
$stmt->bind_param("i", $repo_id);
$stmt->execute();

// Eliminar el repositorio
$stmt = $conex->prepare("DELETE FROM repositorios WHERE id = ?");
$stmt->bind_param("i", $repo_id);
$stmt->execute();

header("Location: repositorio.php"); // Redirigir al listado de repos
exit();
?>
