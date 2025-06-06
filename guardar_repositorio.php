<?php
session_start();
include("conexion.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

$usuario_id = $_SESSION['id'] ?? 0;
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$visibilidad = $_POST['visibilidad'] ?? 'privado';

if ($usuario_id === 0 || empty($titulo) || empty($descripcion)) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
}

// Insertar repositorio primero
$sql_repo = "INSERT INTO repositorios (usuario_id, titulo, descripcion, visibilidad) VALUES (?, ?, ?, ?)";
$stmt_repo = $conex->prepare($sql_repo);
$stmt_repo->bind_param("isss", $usuario_id, $titulo, $descripcion, $visibilidad);

if (!$stmt_repo->execute()) {
    echo json_encode(["success" => false, "message" => "Error al guardar repositorio"]);
    exit();
}

$repositorio_id = $stmt_repo->insert_id;

// Verificar archivos
if (!isset($_FILES['archivos']) || empty($_FILES['archivos']['name'][0])) {
    echo json_encode(["success" => false, "message" => "No se subieron archivos"]);
    exit();
}

$archivos = $_FILES['archivos'];

if (!file_exists("uploads/repositorio")) {
    mkdir("uploads/repositorio", 0777, true);
}

$sql_archivo = "INSERT INTO archivos_repositorio (repositorio_id, ruta, nombre_original, tipo) VALUES (?, ?, ?, ?)";
$stmt_archivo = $conex->prepare($sql_archivo);

for ($i = 0; $i < count($archivos['name']); $i++) {
    $nombre_original = basename($archivos['name'][$i]);
    $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
    $nombre_guardado = time() . "_" . $nombre_original;
    $ruta_final = "uploads/repositorio/" . $nombre_guardado;

    if (move_uploaded_file($archivos['tmp_name'][$i], $ruta_final)) {
        $stmt_archivo->bind_param("isss", $repositorio_id, $ruta_final, $nombre_original, $extension);
        $stmt_archivo->execute();
    }
}

echo json_encode(["success" => true]);

header("Location: repositorio.php"); // Redirigir al listado de repos
exit();
?>
