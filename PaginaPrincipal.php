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
$rol = $_SESSION['rol'];
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
    <a onclick="openNav()" class="menu"><button><i class="fa-solid fa-bars"></i></button></a>
    <div class="overlay" id="mobile-menu">
      <a href="#" onclick="closeNav()" class="close">&times</a>
      <div class="overlay-content">
        <a href="PaginaPrincipal.php">Inicio</a>
        <a href="perfil.php">Perfil</a>
        <a href="mensajeria.php">Mensajes</a>
        <a href="mensajeria.php">Configuracion</a>
        <a href="mensajeria.php">Eventos</a>
        <a href="mensajeria.php">Foros</a>
        <a href="mensajeria.php">UnexShop</a>
        <a href="mensajeria.php">UnexRepos</a>
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
      <div class="links">
        <?php if ($_SESSION['rol'] === 'admin') {
    echo "<a href='admin_panel.php' class='evento-btn'><i class='fa-solid fa-user-tie'></i> Admin</a>";
}?>
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
      <div class="post-create">
        <form action="publicar.php" method="POST" enctype="multipart/form-data">
          <div class="input-create-post">
            <a href="perfil.php"><img src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
            <textarea maxlength="250" class="description" name="description"
              placeholder="¿Que publicarás hoy <?php echo "$NombreUsuario" ?>?"></textarea>
          </div>
          <div class="buttons-create-post">
            <label for="file-input" class="upload-file-label">Subir foto</label>
            <input type="file" class="file-input" id="file-input" name="imagen" accept="image/*">
            <button class="post-create-btn" type="submit" value="enviar">Publicar</button>
              <div class="contenidoArchivo" id="contenidoArchivo">
          <span id="file-name">Seleccionar archivo</span>
          <a onclick="quitarArchivo()" class="cerrarArchivo">&times</a>
        </div>
          </div>
        </form>
      </div>
      <div class="publicaciones" id="publicaciones"> </div>
      <div class="compartir-publicacion" id="compartir-publicacion" popover>
        <label>
          <h2>¿Deseas compartir esta publicacion?</h2>
          <a onclick="divCompartir()">&times</a>
        </label>
        <label><button onclick="compartirPublicacion()">Si</button>
          <button onclick="divCompartir()">No</button></label>
      </div>
      <div class="crear-evento" id="crear-evento" popover>
        <form id="form-evento" action="guardar_evento.php" method="POST">
          <h2>Crear Evento</h2>
          <label><strong>Título del evento:</strong></label>
          <input type="text" name="titulo" required>
          <label><strong>Descripción:</strong></label>
          <textarea name="descripcion" required></textarea>
          <label><strong>Fecha y hora:</strong></label>
          <input type="date" name="fecha" min="2010-01-01" required>
          <input type="time" name="hora" id="hora" required>
          <strong>Agregar ubicación</strong>
          <input type="checkbox" id="activar-mapa">

          <!-- Contenedor para el mapa -->

          <input type="hidden" id="latitud" name="latitud">
          <input type="hidden" id="longitud" name="longitud">

          <button type="submit">Guardar evento</button>
        </form>
        <div id="contenedor-mapa" style="display: none; margin-top: 10px;">
          <div id="map" style="height: 300px;"></div>
        </div>
      </div>
      <div class="crear-foro" id="crear-foro" popover>
        <form action="guardar_foro.php" id="formCrearForo" method="POST" enctype="multipart/form-data">
          <h2>Crear nuevo foro</h2>

          <label for="titulo"><strong>Título del foro:</strong></label>
          <input type="text" id="titulo" name="titulo" required>

          <label for="descripcion"><strong>Descripción:</strong></label>
          <textarea id="descripcion" name="descripcion" required></textarea>

          <label for="tipo"><strong>Tipo de foro:</strong></label>
          <select id="tipo" name="tipo">
            <option value="general">General</option>
            <option value="tema">Tema</option>
            <option value="estudio">Estudio</option>
          </select>
          <label><strong>Seleccionar imagen del foro</strong></label>
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
  <script src="Main.js"> </script>
</body>

</html>