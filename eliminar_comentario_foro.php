<?php
session_start();
include("conexion.php");

$id = $_POST['id'] ?? 0;
$usuario = $_SESSION['id'] ?? 0;

// Verificar si el comentario pertenece al usuario o si el usuario es el creador del foro
$stmt = $conex->prepare("SELECT c.usuario_id, f.creado_por
                         FROM comentarios_foro c
                         JOIN foros f ON c.foro_id = f.id
                         WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['usuario_id'] == $usuario || $row['creado_por'] == $usuario) {
        $del = $conex->prepare("DELETE FROM comentarios_foro WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No autorizado"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Comentario no encontrado"]);
}
?>