<?php
session_start();
include("conexion.php");

$idgrupo = $_GET['grupo_id'] ?? 0;

if ($idgrupo == 0) {
    echo json_encode(["error" => "ID del grupo no válido"]);
    exit();
}

$sql = "SELECT * FROM grupos WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $idgrupo);
$stmt->execute();
$result = $stmt->get_result();
$grupoInfo = $result->fetch_assoc();

header("Content-Type: application/json");
echo json_encode($grupoInfo);
?>