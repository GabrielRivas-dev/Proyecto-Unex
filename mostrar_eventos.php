<?php 
include 'conexion.php';

$sql = "SELECT 
        e.id AS evento_id, e.titulo, e.descripcion, e.fecha, e.hora, e.latitud, e.longitud, e.creado_en, 
        u.nombre, u.apellido, u.imagen
    FROM eventos e
    JOIN usuarios u ON e.creador_id = u.id";

$resultado = $conex->query($sql);
if ($resultado->num_rows > 0) {
    $eventos = [];
    while ($row = $resultado->fetch_assoc()) {
        $eventos[] = $row;
    }

    // Devuelve los datos en formato JSON
    header("Content-Type: application/json");
    echo json_encode($eventos);
} else {
    // Manejo de errores: No se encontraron publicaciones
    echo json_encode(['error' => 'No se encontraron publicaciones']);
}
?>