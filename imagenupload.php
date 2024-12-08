<?php
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

include("conexion.php");
$imagen = $_FILES['imagen']['name'];
$rutaDestino = "uploads/" . basename($imagen);

// Mover la imagen a la carpeta 'uploads'
if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    // Insertar la ruta en la base de datos
    $sql = "UPDATE usuarios set imagen='$rutaDestino' where id='$idUsuario'";
    if ($conex->query($sql) === TRUE) {
        echo "Imagen subida exitosamente.";
        $_SESSION['imagen']=$rutaDestino;
    } else {
        echo "Error: " . $sql . "<br>" . $conex->error;
    }
} else {
    echo "Error al subir la imagen.";
}
header('Location: perfil.php');
$conex->close();
?>
