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


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css\fontawesome-free-6.6.0-web\css\all.css">
  <title>Unex</title>
</head>

<body>
  <header class="header">
    <div class="logo">
      <a href="PaginaPrincipal.php">Unex</a>
    </div>
    <a href="perfil.php"><img class="perfil-header" src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
    <nav>
      <ul class="nav-links">
        <li><a href="PaginaPrincipal.php"><i class="fa-solid fa-house"></i></a></li>
        <li><a href="mensajeria.php"><i class="fa-solid fa-message"></i> <span id="contador-mensajes"></span></a></li>
        <li><button onclick="mostrarNotificaciones()"><i class="fa-solid fa-bell"></i> <span
              id="contador-notificaciones"></span></button></li>
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
  <main class="container">
    <div class="left-column">
      <div class="perfil">
        <a href="perfil.php"><img src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
        <p><?php echo "$NombreUsuario $ApellidoUsuario"; ?></p>
      </div>
      <div class="mensajePresentacion">
        <p id="mensajePresentacion"><?php echo $presentacionUsuario; ?></p>
      </div>
     <div class="links">
         <button popovertarget="crear-evento" class="evento-create-btn"><i class="fa-solid fa-calendar-plus"></i> Crear
          evento</button>
        <a href="eventos.php" class="evento-btn"><i class="fa-solid fa-location-dot"></i>
          Eventos</Em></a>
        <button popovertarget="crear-foro" class="foro-create-btn"><i class="fa-solid fa-globe"></i><i class="fa-solid fa-plus"></i> Crear
          foro</button>
        <a href="verForos.php" class="evento-btn"><i class="fa-solid fa-globe"></i>
          Foros</Em></a>
          <a href="marketplace.php" class="evento-btn"><i class="fa-solid fa-store"></i>
          UnexShop</Em></a>
          <a href="repositorio.php" class="evento-btn"><i class="fa-solid fa-briefcase"></i>
          UnexRepos</Em></a>
      </div>
      <div id="invitaciones-usuario"></div>

    </div>
    <!--CONTENIDO DEL MEDIO -->
    <div class="main-content" id="main-content">
      <!--CREAR PUBLICACION-->
      <div class="eventos" id="eventos"> </div>
      <div class="crear-evento" id="crear-evento" popover>
        <form id="form-evento" action="guardar_evento.php" method="POST">
          <h2>Crear Evento</h2>
          <label>Título del evento:</label>
          <input type="text" name="titulo" required>
          <label>Descripción:</label>
          <textarea name="descripcion" required></textarea>
          <label>Fecha y hora:</label>
          <input type="date" name="fecha" min="2010-01-01"  required>
          <input type="time" name="hora" id="hora" required>
          <input type="checkbox" id="activar-mapa"> Agregar ubicación

          <!-- Contenedor para el mapa -->
         
            <input type="hidden" id="latitud" name="latitud">
            <input type="hidden" id="longitud" name="longitud">
         
          <button type="submit">Guardar evento</button>
        </form>
        <div id="contenedor-mapa" style="display: none; margin-top: 10px;">
        <div id="map" style="height: 280px;"></div> </div>
      </div>
      <div class="crear-foro" id="crear-foro" popover>
        <form action="guardar_foro.php" id="formCrearForo" method="POST" enctype="multipart/form-data">
          <h2>Crear nuevo foro</h2>

          <label for="titulo">Título del foro:</label>
          <input type="text" id="titulo" name="titulo" required>

          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion" required></textarea>

          <label for="tipo">Tipo de foro:</label>
          <select id="tipo" name="tipo">
            <option value="general">General</option>
            <option value="tema">Tema</option>
            <option value="estudio">Estudio</option>
          </select>
          <label>Seleccionar imagen del foro</label>
          <input type="file" accept="image/*" id="foro-imagen" name="foro-imagen" required>
          <button type="submit">Crear foro</button>
        </form>
      </div>
    </div>
    <!--CONTENIDO DE LA DERECHA -->
    <div class="right-column">
      Seguidos
      <div class="seguidos" id="seguidos">
      </div>
      Foros 
      <div class="seguidos-foros" id="seguidos-foros"></div>
    </div>
  </main>
  <script src="eventos.js"> </script>
</body>

</html>