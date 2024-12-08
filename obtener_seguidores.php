<?php
session_start();
include("conexion.php");

// Habilitar errores para depuración (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID del usuario no proporcionado']);
    exit();
}

$usuario_id = (int)$_GET['usuario_id'];

// Consultar la cantidad de seguidores
$sqlSeguidores = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE seguido_id = ?";
$stmtSeguidores = $conex->prepare($sqlSeguidores);
if (!$stmtSeguidores) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta de seguidores']);
    exit();
}
$stmtSeguidores->bind_param("i", $usuario_id);
$stmtSeguidores->execute();
$resultSeguidores = $stmtSeguidores->get_result()->fetch_assoc();
$total_seguidores = $resultSeguidores['total_seguidores'];

// Consultar la cantidad de seguidos
$sqlSeguidos = "SELECT COUNT(*) AS total_seguidos FROM seguidores WHERE seguidor_id = ?";
$stmtSeguidos = $conex->prepare($sqlSeguidos);
if (!$stmtSeguidos) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta de seguidos']);
    exit();
}
$stmtSeguidos->bind_param("i", $usuario_id);
$stmtSeguidos->execute();
$resultSeguidos = $stmtSeguidos->get_result()->fetch_assoc();
$total_seguidos = $resultSeguidos['total_seguidos'];
echo json_encode([
    'success' => true,
    'seguidores' => $total_seguidores,
    'seguidos' => $total_seguidos
]);
?>