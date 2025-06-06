<?php
session_start();
include("conexion.php");

$usuarioId = $_SESSION['id'] ?? 0;
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio = $_POST['precio'] ?? 0;
$imagenRuta = null;

if ($usuarioId === 0 || empty($titulo) || empty($descripcion) || $precio <= 0) {
  echo json_encode(["success" => false, "message" => "Datos incompletos"]);
  exit();
}

// Manejar imagen si existe
if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
  $nombreImagen = time() . '_' . basename($_FILES['imagen']['name']);
  $rutaDestino = "uploads/" . $nombreImagen;

  if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    $imagenRuta = $rutaDestino;
  }
}

$stmt = $conex->prepare("INSERT INTO productos (usuario_id, titulo, descripcion, precio, imagen) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issds", $usuarioId, $titulo, $descripcion, $precio, $imagenRuta);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "message" => "Error al guardar"]);
}
header('Location: marketplace.php');
exit();
?>
