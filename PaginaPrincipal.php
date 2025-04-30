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
$presentacionUsuario = $_SESSION['presentacion'];
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
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css\fontawesome-free-6.6.0-web\css\all.css">
  <title>Unex</title>
</head>

<body>
  <!--ENCABEZADO -->
  <header class="header">
    <div class="logo">
      <a href="PaginaPrincipal.php">Unex</a>
    </div>
    <a href="perfil.php"><img class="perfil-header" src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
    <nav>
      <ul class="nav-links">
        <li><a href="PaginaPrincipal.php"><i class="fa-solid fa-house"></i></a></li>
        <li><a href="mensajeria.php"><i class="fa-solid fa-message"></i> <span id="contador-mensajes"></span></a></li>
        <li><button onclick="mostrarNotificaciones()"><i class="fa-solid fa-bell"></i> <span id="contador-notificaciones"></span></button></li>
        <li><button onclick="openConfiguration()"><i class="fa-solid fa-gear"></i></button></li>
      </ul>
    </nav>
    <div id="lista-notificaciones" class="lista-notificaciones">
    </div>
    <div id="configuration" class="configuration">
      <ul>
        <li><a href="Configuracion_usuario.php"><strong>Configurar usuario</strong></a></li>
        <li><a href="cerrar_sesion.php"><strong>Cerrar sesion</strong></a></li>
      </ul>
    </div>
    <div id="resultados"></div>
    <input type="search" class="search-bar" id="buscador" oninput="buscarPerfiles()" placeholder="Buscar....">
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
    <div class="left-column">
      <div class="perfil">
        <a href="perfil.php"><img src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
        <p><?php echo "$NombreUsuario $ApellidoUsuario"; ?></p>
      </div>
      <div class="mensajePresentacion">
        <p id="mensajePresentacion"><?php echo $presentacionUsuario; ?></p>
    </div>
    <div id="invitaciones-usuario"></div>

    </div>
    <!--CONTENIDO DEL MEDIO -->
    <div class="main-content" id="main-content">
      <!--CREAR PUBLICACION-->
      <div class="post-create">
        <form action="publicar.php" method="POST" enctype="multipart/form-data">
          <div class="input-create-post">
            <a href="perfil.php"><img src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
            <textarea maxlength="250" class="description" name="description"
              placeholder="¿Que publicaras hoy <?php echo "$NombreUsuario" ?>?"></textarea>
          </div>
          <div class="buttons-create-post">
            <label for="file-input" class="upload-file-label">Subir foto</label>
            <input type="file" class="file-input" id="file-input" name="imagen" accept="image/*">
            <button class="post-create-btn" type="submit" value="enviar">Publicar</button>
          </div>
        </form>
      </div>
      <div class="publicaciones" id="publicaciones"> </div>
      <div class="compartir-publicacion" id="compartir-publicacion" popover>
        <label><h2>¿Deseas compartir esta publicacion?</h2>
        <a onclick="divCompartir()">&times</a>
      </label>
      <label><button onclick="compartirPublicacion()">Si</button>
      <button onclick="divCompartir()">No</button></label>  
        
      </div>
    </div>
    <!--CONTENIDO DE LA DERECHA -->
    <div class="right-column">
      Seguidos
      <div class="seguidos" id="seguidos">

      </div>

    </div>


  </main>
  <script src="Main.js"> </script>
</body>

</html>