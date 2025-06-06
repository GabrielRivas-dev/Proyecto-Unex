<?php 
session_start();
$idUsuario = $_SESSION['id'];
include 'conexion.php';

$sql = "SELECT f.foro_id, f.usuario_id AS seguido_id, s.titulo,  s.imagen
        FROM foro_seguidores f 
        JOIN foros s ON foro_id = s.id
        WHERE usuario_id = ? ";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$foroSeguidos = [];
while ($row = $result->fetch_assoc()) {
    $foroSeguidos[] = $row;
}

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($foroSeguidos);
?>
