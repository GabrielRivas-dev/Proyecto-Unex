<?php
session_start();
include("conexion.php");

$sql = "SELECT r.id, r.titulo, r.descripcion, r.visibilidad, r.fecha, u.nombre, u.apellido
        FROM repositorios r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE r.visibilidad = 'publico'
        ORDER BY r.fecha DESC";

$resultado = $conex->query($sql);
$repositorios = [];

while ($row = $resultado->fetch_assoc()) {
    $repo_id = $row['id'];

    $stmt = $conex->prepare("SELECT ruta, nombre_original, tipo FROM archivos_repositorio WHERE repositorio_id = ?");
    $stmt->bind_param("i", $repo_id);
    $stmt->execute();
    $result_archivos = $stmt->get_result();

    $archivos = [];
    while ($archivo = $result_archivos->fetch_assoc()) {
        $archivos[] = $archivo;
    }

    $row['archivos'] = $archivos;
    $row['descargar_zip'] = "descargar_zip.php?repo_id=" . $repo_id; // enlace para descargar todo
    $repositorios[] = $row;
}

echo json_encode($repositorios);
