<?php 
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  // Si no está autenticado, redirigir al login
  header('Location: login.php');
  exit();
}
$idUsuario = $_SESSION['usuario'];
include 'conexion.php';

$sql = "SELECT p.id  AS publicacion_id , p.contenido, p.fecha, p.imagensubida, p.compartidos, u.nombre, u.apellido, u.imagen, (SELECT COUNT(*) FROM likes WHERE publicacion_id = p.id) AS total_likes, (SELECT COUNT(*) FROM comentarios WHERE publicacion_id = p.id) AS total_comments
        FROM publicaciones p 
        JOIN usuarios u ON p.usuario_id = u.id 
        where u.id='$idUsuario'
        ORDER BY p.fecha ASC ";
$resultado = $conex->query($sql);
if ($resultado->num_rows > 0) {
    $publicaciones = [];
    while ($row = $resultado->fetch_assoc()) {
        $publicaciones[] = $row;
    }

    // Devuelve los datos en formato JSON
    header("Content-Type: application/json");
    echo json_encode($publicaciones);
} else {
    // Manejo de errores: No se encontraron publicaciones
    echo json_encode(['error' => 'No se encontraron publicaciones']);
}
?>