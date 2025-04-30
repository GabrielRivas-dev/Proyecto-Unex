<?php
session_start();
include("conexion.php");

$grupoId = $_GET['grupo_id'] ?? 0;

// Obtener IDs de usuarios ya en el grupo o ya invitados
$sql = "
    SELECT usuario_id FROM grupo_usuarios WHERE grupo_id = ?
    UNION
    SELECT invitado_id FROM invitaciones_grupo WHERE grupo_id = ? AND estado = 'pendiente'
";

$stmt = $conex->prepare($sql);
$stmt->bind_param("ii", $grupoId, $grupoId);
$stmt->execute();
$res = $stmt->get_result();

$excluir = [];
while ($row = $res->fetch_assoc()) {
    $excluir[] = $row['usuario_id'] ?? $row['invitado_id'];
}

// Crear lista de exclusión para el query dinámicamente
$exclusiones = implode(",", array_map("intval", $excluir));

$query = "SELECT id, nombre, apellido FROM usuarios";
if (!empty($exclusiones)) {
    $query .= " WHERE id NOT IN ($exclusiones)";
}

$result = $conex->query($query);
$usuarios = [];

while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

header("Content-Type: application/json");
echo json_encode($usuarios);
?>
