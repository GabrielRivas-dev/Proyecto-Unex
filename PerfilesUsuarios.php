<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  // Si no está autenticado, redirigir al login
  header('Location: login.php');
  exit();
}
$imagenUsuario = $_SESSION['imagen'];
if (isset($_GET['id'])) {
  $usuarioId = (int) $_GET['id'];
  $_SESSION['usuario'] = $usuarioId;

  if ($_SESSION['usuario'] == $_SESSION['id']) {
    header('Location: perfil.php');
  }

  include("conexion.php");  // Conectar a la base de datos

  $sql = $conex->prepare("SELECT Nombre, Apellido, Cedula, Fecha, Genero, Email, Clave, imagen FROM usuarios WHERE id = ?");
  $sql->bind_param("i", $usuarioId);
  $sql->execute();
  $result = $sql->get_result();
  $perfil = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/perfil.css">
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
        <li><a href="mensajeria.php"><i class="fa-solid fa-message"></i><span id="contador-mensajes"></span></a></li>
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
  <main class="container">
    <div class="left-column">
      <div class="perfil">
        <img src="<?php echo $perfil['imagen'] ?>" alt="perfil">
        <p><?php echo $perfil['Nombre'] . ' ' . $perfil['Apellido']; ?></p>
      </div>
    </div>
    <div class="main-content" id="main-content">
      <div class="post-follows">
        <div class="info-followers">
          <label for="followers">
            <h3>Seguidores:</h3><span id="seguidores-count">0</span>
          </label>
          <label for="followed">
            <h3>Seguidos:</h3><span id="seguidos-count">0</span>
          </label>
        </div>
        <div class="post-follows-btns">
          <button class="post-follows-btn" id="seguir-btn-<?php echo $usuarioId ?>"
            onclick="toggleSeguir(<?php echo $usuarioId ?>)">Seguir</button>
          <a href="mensajeria.php?receptor_id=<?php echo $usuarioId; ?>">
            <button class="post-mensaje-btn">Mensaje</button>
          </a>
        </div>
      </div>
      <div class="publicaciones" id="publicaciones"></div>
      <div class="compartir-publicacion" id="compartir-publicacion" popover>
        <label>
          <h2>¿Deseas compartir esta publicacion?</h2>
          <a onclick="divCompartir()">&times</a>
        </label>
        <label><button onclick="compartirPublicacion()">Si</button>
          <button onclick="divCompartir()">No</button></label>
      </div>
    </div>
    <div class="right-column">
      Seguidos
      <div class="seguidos" id="seguidos">

      </div>

    </div>

  </main>
  <script src="perfilusuarios.js"></script>
</body>

</html>