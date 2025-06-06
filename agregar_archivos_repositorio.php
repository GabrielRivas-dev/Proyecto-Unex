<?php
session_start();
include("conexion.php");

$usuario_id = $_SESSION['id'] ?? 0;
$repo_id = $_POST['repo_id'] ?? 0;

if ($usuario_id === 0 || $repo_id === 0 || !isset($_FILES['nuevo_archivo'])) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
}

// Verificar que el repositorio pertenezca al usuario
$stmt = $conex->prepare("SELECT id FROM repositorios WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $repo_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No tienes permiso para modificar este repositorio."]);
    exit();
}

// Procesar archivos
$archivos = $_FILES['nuevo_archivo'];
$guardados = [];

if (!file_exists("uploads/repositorio")) {
    mkdir("uploads/repositorio", 0777, true);
}

for ($i = 0; $i < count($archivos['name']); $i++) {
    $nombre_original = $archivos['name'][$i];
    $tipo = pathinfo($nombre_original, PATHINFO_EXTENSION);
    $nombre_final = time() . "_" . basename($nombre_original);
    $ruta = "uploads/repositorio/" . $nombre_final;

    if (move_uploaded_file($archivos['tmp_name'][$i], $ruta)) {
        $stmt = $conex->prepare("INSERT INTO archivos_repositorio (repositorio_id, ruta, nombre_original, tipo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $repo_id, $ruta, $nombre_original, $tipo);
        $stmt->execute();
        $guardados[] = $ruta;
    }
}

if (count($guardados) > 0) {
    echo json_encode(["success" => true, "archivos" => $guardados]);
} else {
    echo json_encode(["success" => false, "message" => "No se pudo subir ningún archivo."]);
}
?>
