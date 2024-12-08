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

include 'conexion.php';

$sql = "SELECT p.titulo, p.contenido, p.fecha, p.imagensubida, u.nombre, u.apellido, u.imagen
        FROM publicaciones p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha ASC ";
$resultado = $conex->query($sql);

$publicacion = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/mensajeria.css">
  <link rel="stylesheet" href="css\fontawesome-free-6.6.0-web\css\all.css">
  <title>Unex</title>
</head>

<body>
  <!--ENCABEZADO -->
  <header class="header">
    <div class="logo">
      <a href="PaginaPrincipal.php">Unex</a>
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="PaginaPrincipal.php">Inicio</a></li>
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="mensajeria.php">Mensajes</a></li>
        <li><a href="#">Notificaciones</a></li>
        <li><a href="cerrar_sesion.php">Cerrar sesion</a></li>
      </ul>
    </nav>
    <input type="search" class="search-bar" placeholder="Buscar....">
    <a onclick="openNav()" class="menu"><button>Menu</button></a>
    <div class="overlay" id="mobile-menu">
      <a href="#" onclick="closeNav()" class="close">&times</a>
      <div class="overlay-content">
        <a href="PaginaPrincipal.php">Inicio</a>
        <a href="perfil.php">Perfil</a>
        <a href="#">Mensajes</a>
        <a href="cerrar_sesion.php">Cerrar sesion</a>
      </div>
    </div>
  </header>
  <!--CONTENIDO -->
  <main class="container">
    <div class="left-column">Amigos
      
    </div>
    <!--CONTENIDO DEL MEDIO -->
    <div class="main-content" id="main-content">
        <div class="chat-header">
            <img src="<?php echo htmlspecialchars($publicacion['imagen']) ?>" alt="Foto de usuario">
            <strong><?php echo htmlspecialchars($publicacion['nombre']), " ", htmlspecialchars($publicacion['apellido']) ?></strong>
    </div>
        <div class="chat"></div>
        <div class="chat-input">
            <form >
             <textarea  name="mensaje" placeholder="escribe aqui"></textarea>
             <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
             </form>
            </div>
            
        </div>
        
    </div>
    <!--CONTENIDO DE LA DERECHA -->
    <div class="right-column">Grupos

    </div>
  </main>
  <script src="Main.js"> </script>
</body>

</html>