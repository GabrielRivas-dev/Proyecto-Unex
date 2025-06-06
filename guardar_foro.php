<?php
session_start();
include("conexion.php");

$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$tipo = $_POST['tipo'] ?? 'general';
$creado_por = $_SESSION['id'] ?? 0;
$foroimagen = null;

if (empty($titulo) || empty($descripcion) || $creado_por === 0) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
}

if (!empty($_FILES['foro-imagen']['tmp_name'])) {
    $nombreArchivo = time() . "_" . $_FILES['foro-imagen']['name'];
    $rutaArchivo = "uploads/" . $nombreArchivo;

    if (move_uploaded_file($_FILES['foro-imagen']['tmp_name'], $rutaArchivo)) {
        $foroimagen = $rutaArchivo;
    }
}

$sql = "INSERT INTO foros (titulo, descripcion, tipo, visibilidad, creado_por, imagen) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ssssis", $titulo, $descripcion, $tipo, $visibilidad, $creado_por, $foroimagen);
$stmt->execute();
$foro_id = $stmt->insert_id;

header('Location: PaginaPrincipal.php');
exit();
?>
