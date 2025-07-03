<?php
session_start();
include("conexion.php");

$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$latitud = $_POST['latitud'] ?? null;
$longitud = $_POST['longitud'] ?? null;
$idUsuario = $_SESSION['id'];

$sql = "INSERT INTO eventos (titulo, descripcion, fecha, hora, latitud, longitud, creador_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("ssssddi", $titulo, $descripcion, $fecha, $hora, $latitud, $longitud, $idUsuario);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Evento guardado"]);
    header('Location: eventos.php');
    exit();
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar"]);
}
?>
