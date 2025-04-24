<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id'];

$sql = "
    SELECT g.id AS grupo_id, g.nombre AS grupo_nombre, imagen, (SELECT 
                CASE 
                    WHEN archivo IS NOT NULL AND archivo != '' THEN '📎 Archivo adjunto' 
                    ELSE mensaje 
                END 
            FROM mensajes_grupo 
            WHERE grupo_id = g.id 
            ORDER BY fecha DESC 
            LIMIT 1) AS ultimo_mensaje
    FROM grupos g
    JOIN grupo_usuarios gu ON g.id = gu.grupo_id
    WHERE gu.usuario_id = ?
";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$grupos = [];
while ($row = $result->fetch_assoc()) {
    $grupos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($grupos);
?>
