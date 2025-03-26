<?php
session_start();
include("conexion.php");

$idUsuario = $_SESSION['id']; // Usuario actual

$sql = "SELECT COUNT(*) AS total_no_leidos 
        FROM mensajes 
        WHERE receptor_id = ? AND visto = 0";

$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["total_no_leidos" => $row["total_no_leidos"]]);
?>