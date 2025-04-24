<?php
session_start();
header("Content-Type: application/json");
include("conexion.php");

$usuarioId = $_SESSION['id'] ?? null;
$grupoId = $_POST['grupo_id'] ?? 0;

if (!$usuarioId || !$grupoId) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit();
}

// Verificar si pertenece al grupo
$stmt = $conex->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $grupoId, $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No perteneces al grupo."]);
    exit();
}

// Verificar si es el creador
$stmt = $conex->prepare("SELECT creado_por FROM grupos WHERE id = ?");
$stmt->bind_param("i", $grupoId);
$stmt->execute();
$grupo = $stmt->get_result()->fetch_assoc();
$esCreador = $grupo['creado_por'] == $usuarioId;

// Eliminar del grupo
$stmt = $conex->prepare("DELETE FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $grupoId, $usuarioId);
$stmt->execute();

// Si es el creador, buscar nuevo creador
if ($esCreador) {
    $stmt = $conex->prepare("SELECT usuario_id FROM grupo_usuarios WHERE grupo_id = ? LIMIT 1");
    $stmt->bind_param("i", $grupoId);
    $stmt->execute();
    $nuevo = $stmt->get_result()->fetch_assoc();

    if ($nuevo) {
        $nuevoCreador = $nuevo['usuario_id'];
        $stmt = $conex->prepare("UPDATE grupos SET creado_por = ? WHERE id = ?");
        $stmt->bind_param("ii", $nuevoCreador, $grupoId);
        $stmt->execute();

        $stmt = $conex->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
        $grupoNombre = $stmt->get_result()->fetch_assoc()['nombre'];

        // Insertar notificación
        $mensaje = "Ahora eres el nuevo creador del grupo:" . htmlspecialchars($grupoNombre);
        $tipo = "nuevo_creador";

        $stmt = $conex->prepare("INSERT INTO notificaciones (usuario_id, tipo, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $nuevoCreador, $tipo, $mensaje);
        $stmt->execute();
    } else {
        // Nadie más queda → eliminar grupo
        $stmt = $conex->prepare("DELETE FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
    }
}

echo json_encode(["success" => true, "message" => "Has salido del grupo."]);
?>