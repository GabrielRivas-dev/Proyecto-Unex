<?php
session_start();
include("conexion.php");

$repo_id = $_GET['repo_id'] ?? 0;
if (!$repo_id) {
    die("ID de repositorio no proporcionado");
}

// Obtener archivos del repositorio
$stmt = $conex->prepare("SELECT nombre_original, ruta FROM archivos_repositorio WHERE repositorio_id = ?");
$stmt->bind_param("i", $repo_id);
$stmt->execute();
$result = $stmt->get_result();

$archivos = [];
while ($row = $result->fetch_assoc()) {
    if (file_exists($row['ruta'])) {
        $archivos[] = $row;
    }
}

if (empty($archivos)) {
    die("No hay archivos válidos en este repositorio.");
}

// Crear archivo ZIP temporal
$zip = new ZipArchive();
$zip_filename = tempnam(sys_get_temp_dir(), "repo_") . ".zip";

if ($zip->open($zip_filename, ZipArchive::CREATE) !== true) {
    die("No se pudo crear el archivo ZIP.");
}

foreach ($archivos as $archivo) {
    $zip->addFile($archivo['ruta'], $archivo['nombre_original']);
}
$zip->close();

// Enviar ZIP al navegador
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=repositorio_$repo_id.zip");
header("Content-Length: " . filesize($zip_filename));
readfile($zip_filename);

// Eliminar ZIP temporal
unlink($zip_filename);
exit();
?>
