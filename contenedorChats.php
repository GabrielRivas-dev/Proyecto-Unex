<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id'];

$sql = "
    SELECT c.id AS chat_id, 
           u.id AS receptor_id, 
           u.nombre, 
           u.apellido, 
           u.imagen,
           (SELECT 
                CASE 
                    WHEN archivo IS NOT NULL AND archivo != '' THEN '📎 Archivo adjunto' 
                    ELSE mensaje 
                END 
            FROM mensajes 
            WHERE chat_id = c.id 
            ORDER BY fecha DESC 
            LIMIT 1) AS ultimo_mensaje,
           (SELECT visto FROM mensajes 
            WHERE chat_id = c.id 
            ORDER BY fecha DESC 
            LIMIT 1) AS visto,
           (SELECT fecha FROM mensajes 
            WHERE chat_id = c.id 
            ORDER BY fecha DESC 
            LIMIT 1) AS fecha_ultimo_mensaje
    FROM chats c
    JOIN usuarios u ON (c.usuario1_id = u.id OR c.usuario2_id = u.id)
    WHERE 
        (c.usuario1_id = ? OR c.usuario2_id = ?)
        AND u.id != ?
        AND (
            (c.usuario1_id = ? AND c.usuario1_oculto = 0) OR 
            (c.usuario2_id = ? AND c.usuario2_oculto = 0)
        )
    ORDER BY fecha_ultimo_mensaje DESC
";

$stmt = $conex->prepare($sql);
$stmt->bind_param("iiiii", $idUsuario, $idUsuario, $idUsuario, $idUsuario, $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$conversaciones = [];
while ($row = $result->fetch_assoc()) {
    $conversaciones[] = $row;
}

header('Content-Type: application/json');
echo json_encode($conversaciones);
?>
