<?php
session_start();
include("conexion.php");

$nombreGrupo = $_POST['nombre_grupo'];
$imagenGrupo = null;
$miembros = json_decode($_POST['miembros'], true);
$creador = $_SESSION['id'];

if (empty($nombreGrupo) || empty($miembros)) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
}

if (!empty($_FILES['grupo-imagen'])) {
    $nombreArchivo = time() . "_" . $_FILES['grupo-imagen']['name'];
    $rutaArchivo = "uploads/" . $nombreArchivo;

    if (move_uploaded_file($_FILES['grupo-imagen']['tmp_name'], $rutaArchivo)) {
        $imagenGrupo = $rutaArchivo;
    }
}
// Crear el grupo
$sql = "INSERT INTO grupos (nombre, creado_por, imagen) VALUES (?, ?, ?)";
$stmt = $conex->prepare($sql);
$stmt->bind_param("sis", $nombreGrupo, $creador, $imagenGrupo);
$stmt->execute();
$grupoId = $stmt->insert_id;

// Insertar al creador y los miembros en grupo_usuarios
array_push($miembros, $creador);
foreach ($miembros as $miembro) {
    $sql = "INSERT INTO grupo_usuarios (grupo_id, usuario_id) VALUES (?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ii", $grupoId, $miembro);
    $stmt->execute();
}

echo json_encode(["success" => true, "grupo_id" => $grupoId]);
?>
