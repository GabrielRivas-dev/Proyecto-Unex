<?php
header('Content-Type: application/json');
include("conexion.php");

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    echo json_encode([]);
    exit();
}

$likeQuery = "%{$query}%";

// Buscar usuarios
$stmt = $conex->prepare("SELECT id, Nombre, Apellido, Email, imagen 
    FROM usuarios
    WHERE Nombre LIKE ? OR Apellido LIKE ?");
$stmt->bind_param("ss", $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $row['tipo'] = 'usuario';
    $usuarios[] = $row;
}

// Buscar foros
$stmt = $conex->prepare("SELECT id, titulo, descripcion, imagen 
    FROM foros
    WHERE titulo LIKE ? OR descripcion LIKE ?");
$stmt->bind_param("ss", $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$foros = [];
while ($row = $result->fetch_assoc()) {
    $row['tipo'] = 'foro';
    $foros[] = $row;
}

// Combinar y devolver
echo json_encode(array_merge($usuarios, $foros));

?>