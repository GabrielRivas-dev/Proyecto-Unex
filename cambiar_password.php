<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

include("conexion.php");

$data = json_decode(file_get_contents("php://input"), true);
$idUsuario = $_SESSION['id'];
$passwordActual = $data['password_actual'];
$passwordNueva = $data['password_nueva'];

// Validar que se ingresó la contraseña actual y nueva
if (empty($passwordActual) || empty($passwordNueva)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit();
}

// Consultar la contraseña actual en la base de datos
$stmt = $conex->prepare("SELECT clave FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Verificar si la contraseña ingresada coincide con la almacenada
if (!$row || !password_verify($passwordActual, $row['clave'])) {
    echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta.']);
    exit();
}

// Validar que la nueva contraseña tenga mínimo 6 caracteres
if (strlen($passwordNueva) < 6) {
    echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 6 caracteres.']);
    exit();
}

// Cifrar la nueva contraseña
$passwordHash = password_hash($passwordNueva, PASSWORD_BCRYPT);

// Actualizar la contraseña en la base de datos
$stmt = $conex->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
$stmt->bind_param("si", $passwordHash, $idUsuario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
}
?>
