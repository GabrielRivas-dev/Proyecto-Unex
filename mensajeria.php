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
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const receptorId = urlParams.get("receptor_id");

    if (receptorId) {
      seleccionarReceptor(receptorId); // Cargar chat automáticamente
    }
  });
</script>
</title>
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
    <a href="perfil.php"><img class="perfil-header" src="<?php echo $imagenUsuario; ?>" alt="perfil"></a>
    <nav>
      <ul class="nav-links">
        <li><a href="PaginaPrincipal.php"><i class="fa-solid fa-house"></i></a></li>
        <li><a href="mensajeria.php"><i class="fa-solid fa-message"></i><span id="contador-mensajes"></span></a></li>
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
  <!--CONTENIDO -->
  <main class="container">
    <div class="left-column">Conversaciones
      <div class="conversaciones" id="conversaciones"></div>
    </div>
    <!--CONTENIDO DEL MEDIO -->
    <div class="main-content" id="main-content">
      <div id="chat-box">
        <div class="chat-header">
          <img id="imagen-receptor" src="uploads/default.jpg" alt="Foto de usuario">
          <p id="nombre-receptor">Selecciona un usuario</p>
        </div>
        <div class="contenidoArchivo" id="contenidoArchivo">
          <span id="file-name">Seleccionar archivo</span>
          <a onclick="quitarArchivo()" class="cerrarArchivo">&times</a>
        </div>
        <div id="mensajes"></div>
        <div class="enviarMensaje">
          <form id="formularioMensaje" enctype="multipart/form-data">
            <div class="button-image">
              <label for="archivo" class="label-button"><i class="fa-solid fa-file-import"></i>
              </label>
              <input type="file" class="image-input" id="archivo" name="archivo"
                accept="image/*, apllication/docx, application/pdf, application/msword, application/vnd.ms-excel">
            </div>
            <input class="input-mensaje" type="text" name="mensaje" id="mensaje" placeholder="Escribe un mensaje..."
              autocomplete="off">
            <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
          </form>
        </div>
      </div>
      <div class="info-grupos" id="info-grupos">
        <div class="headerGrupo">
          <img src="uploads/default.jpg" alt="foto">
          <h1>Información del grupo</h1>
          <p>creado en 12/64/45</p>
          <hr>
        </div>
        <div id="botonesGrupo"></div>
        <div id="mostrarGrupo"></div>
    </div>
    </div>
    <!--CONTENIDO DE LA DERECHA -->
    <div class="right-column">Grupos <a onclick="AgregarGrupo()"><i class="fa-solid fa-plus"></i></a>
      <div class="agregarGrupo" id="grupoFormulario">
        <input type="hidden" id="idCreador" value="<?php echo $idUsuario; ?>">
        <form id="formCrearGrupo">
          <label for="nombreGrupo">Nombre del grupo</label>
          <input type="text" id="nombreGrupo" name="nombre_grupo" required maxlength="30" requerid autocomplete="off">
          <label>Seleccionar miembros</label>
          <div id="listaUsuarios">
          </div>
          <label>Seleccionar imagen del grupo</label>
          <input type="file" accept="image/*" id="grupo-imagen" name="grupo-imagen" requerid>
          <button type="submit">Crear Grupo</button>
        </form>
        <div id="mensaje"></div>
      </div>

      <div class="grupos" id="grupos"></div>
    </div>
  </main>
  <script src="config.js.php"></script> <!-- Cargar antes que main.js -->
  <script src="mensajeria.js"></script>
</body>

</html>