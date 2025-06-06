<?php
session_start();
include("conexion.php");

$usuario_id = $_SESSION['id'] ?? 0;

$sql = "SELECT r.id, r.titulo, r.descripcion, r.visibilidad, r.fecha
        FROM repositorios r
        WHERE r.usuario_id = ?
        ORDER BY r.fecha DESC";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$repositorios = [];
while ($row = $result->fetch_assoc()) {
    $repo_id = $row['id'];

    // Obtener archivos relacionados a este repositorio
    $archivos_stmt = $conex->prepare("SELECT ruta, nombre_original, tipo FROM archivos_repositorio WHERE repositorio_id = ?");
    $archivos_stmt->bind_param("i", $repo_id);
    $archivos_stmt->execute();
    $archivos_result = $archivos_stmt->get_result();

    $archivos = [];
    while ($file = $archivos_result->fetch_assoc()) {
        $archivos[] = $file;
    }

    $row['archivos'] = $archivos;
     $row['eliminar_url'] = "eliminar_repositorio.php?repo_id=" . $repo_id;
    $repositorios[] = $row;
}

header("Content-Type: application/json");
echo json_encode($repositorios);
