<?php 
session_start();
$idUsuario = $_SESSION['id'];
include 'conexion.php';

$sql = "SELECT s.id AS seguidos_id, s.seguido_id AS seguido_id, u.nombre, u.apellido, u.imagen,
(SELECT COUNT(*) FROM seguidores WHERE seguidos_id = s.id) AS total_seguidos
        FROM seguidores s 
        JOIN usuarios u ON s.seguido_id = u.id 
        WHERE seguidor_id = ? ";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$seguidos = [];
while ($row = $result->fetch_assoc()) {
    $seguidos[] = $row;
}

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($seguidos);
?>
