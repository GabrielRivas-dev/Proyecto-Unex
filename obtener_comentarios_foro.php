<?php
session_start();
include("conexion.php");

$foro_id = isset($_GET['foro_id']) ? (int) $_GET['foro_id'] : 0;

if ($foro_id === 0) {
    echo json_encode([]);
    exit();
}

$stmt = $conex->prepare("SELECT c.id, c.comentario, c.fecha, c.parent_id, u.nombre, u.apellido, u.imagen
                         FROM comentarios_foro c
                         JOIN usuarios u ON c.usuario_id = u.id
                         WHERE c.foro_id = ?
                         ORDER BY c.fecha ASC");
$stmt->bind_param("i", $foro_id);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
$respuestas = [];

while ($row = $result->fetch_assoc()) {
    if ($row['parent_id'] === null) {
        $row['respuestas'] = [];
        $comentarios[$row['id']] = $row;
    } else {
        $respuestas[] = $row;
    }
}

// Asociar respuestas a su comentario padre
foreach ($respuestas as $respuesta) {
    $parentId = $respuesta['parent_id'];
    if (isset($comentarios[$parentId])) {
        $comentarios[$parentId]['respuestas'][] = $respuesta;
    }
}

echo json_encode(array_values($comentarios));
?>
