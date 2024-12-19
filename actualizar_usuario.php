<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$campo = $data['campo'];
$valor = $data['valor'];
$usuario_id = $_SESSION['id'];

// Validar que el campo sea permitido
$campos_permitidos = ['nombre', 'apellido', 'estado', 'cedula', 'fecha'];
if (!in_array($campo, $campos_permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Campo no permitido']);
    exit();
}

// Actualizar el campo en la base de datos
$sql = "UPDATE usuarios SET $campo = ? WHERE id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("si", $valor, $usuario_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Información actualizada']);
    
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la información']);
}
?>