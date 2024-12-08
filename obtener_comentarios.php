<?php
include("conexion.php");

$publicacion_id = $_GET['publicacion_id'];

$sql = "SELECT c.id AS comentario_id, c.comentario, c.fecha, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, u.imagen AS usuario_imagen
    FROM comentarios c
    JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.publicacion_id = ?
    ORDER BY c.fecha DESC
";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $publicacion_id);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}
header('Content-Type: application/json');
echo json_encode($comentarios);
?>