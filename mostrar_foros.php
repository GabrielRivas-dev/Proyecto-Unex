<?php 
include 'conexion.php';

$sql = "SELECT 
        f.id AS foro_id, f.titulo, f.descripcion, f.tipo, f.imagen, f.creado_por, f.creado_en AS fecha, 
        u.nombre, u.apellido
    FROM foros f
    JOIN usuarios u ON f.creado_por = u.id";

$resultado = $conex->query($sql);
if ($resultado->num_rows > 0) {
    $foros = [];
    while ($row = $resultado->fetch_assoc()) {
        $foros[] = $row;
    }

    // Devuelve los datos en formato JSON
    header("Content-Type: application/json");
    echo json_encode($foros);
} else {
    // Manejo de errores: No se encontraron publicaciones
    echo json_encode(['error' => 'No se encontraron foros']);
}
?>