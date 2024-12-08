<?php
// Conexión a la base de datos
include 'conexion.php'; // Incluye la conexión a tu base de datos
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  // Si no está autenticado, redirigir al login
  header('Location: login.php');
  exit();
}
$idUsuario = $_SESSION['id'];
$NombreUsuario = $_SESSION['Nombre'];
$ApellidoUsuario = $_SESSION['Apellido'];
$CedulaUsuario = $_SESSION['Cedula'];
$FechaUsuario = $_SESSION['Fecha'];
$GeneroUsuario = $_SESSION['Genero'];
$EmailUsuario = $_SESSION['Email'];
$ClaveUsuario = $_SESSION['Clave'];
$imagenUsuario = $_SESSION['imagen'];  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contenido = $_POST['description'];
    $usuario_id = $_SESSION['id']; // Asegúrate de tener la sesión iniciada con el usuario

    // Procesamiento de la imagen
    $ruta_imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $tmp_imagen = $_FILES['imagen']['tmp_name'];
        $directorio_destino = 'uploads/';
        $ruta_imagen = $directorio_destino . $nombre_imagen;
        move_uploaded_file($tmp_imagen, $ruta_imagen);
    }

    // Insertar la publicación en la base de datos
    $sql = "INSERT INTO publicaciones (usuario_id, contenido, imagensubida) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("iss", $usuario_id, $contenido, $ruta_imagen);
    $stmt->execute();
    header("Location: PaginaPrincipal.php"); // Redirecciona al feed de publicaciones
}
?>