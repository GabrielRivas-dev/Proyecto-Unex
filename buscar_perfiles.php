<?php
header('Content-Type: application/json');
include("conexion.php");

// Obtener la consulta del input
$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    echo json_encode([]);
    exit();
}

// Buscar usuarios cuyos nombres o apellidos coincidan con la consulta
$stmt = $conex->prepare("SELECT id, Nombre, Apellido, Email, imagen 
    FROM usuarios
    WHERE Nombre LIKE ? OR Apellido LIKE ?
");
$likeQuery = "%{$query}%";
$stmt->bind_param("ss", $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

// Devolver resultados como JSON
echo json_encode($usuarios);
?>