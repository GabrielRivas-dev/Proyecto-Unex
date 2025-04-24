<?php
session_start();
include("conexion.php");

$usuarioId = $_SESSION['id'];
$presentacion = $_POST['presentacion'] ?? '';

$sql = "UPDATE usuarios SET presentacion = ? WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("si", $presentacion, $usuarioId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar"]);
}
?>
