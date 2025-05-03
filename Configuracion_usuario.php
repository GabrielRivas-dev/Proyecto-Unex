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
$carrera = $_SESSION['carrera'];
$tipo = $_SESSION['tipo'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/configuracionUsuario.css">
  <link rel="stylesheet" href="css\fontawesome-free-6.6.0-web\css\all.css">
  <title>Unex</title>
</head>

<body>
  <div id="my-popover" popover>
    <img src="<?php echo $imagenUsuario; ?>" alt="perfil">
    <div class="file-upload">
      <form action="imagenupload.php" method="POST" enctype="multipart/form-data">
        <label for="file-input" class="custom-file-label">Subir foto</label>
        <input type="file" class="file-input" id="file-input" name="imagen" accept="image/*">
        <button type="submit" value="enviar">Cambiar</button>
      </form>
    </div>
  </div>
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
    <input type="hidden" id="id-usuario" data-id="<?php echo $idUsuario ?>">
    <div class="left-column">
      <div class="perfil">
        <button popovertarget="my-popover" class="cambiarfoto"><img src="<?php echo $imagenUsuario; ?>"
            alt="perfil"></button>
        <p><?php echo "$NombreUsuario $ApellidoUsuario"; ?></p>
      </div>
      <div class="info-usuario">
        <div class="info-followers-perfil">
          <button onclick="mostrarSeguidores()"><label for="followers">
              <h3>Seguidores:</h3><span id="seguidores-count">0</span>
            </label></button>
          <button onclick="mostrarSeguidos()"><label for="followed">
              <h3>Seguidos:</h3><span id="seguidos-count">0</span>
            </label></button>
        </div>

      </div>
    </div>
    <div class="main-content" id="main-content">

      <div class="post">
        <h2 class="titulo">Informacion de <?php echo "$NombreUsuario $ApellidoUsuario"; ?></h2>
        <div class="grid">
          <div class="usuarioInfo">
            <h3>Datos de la cuenta</h3>
            <div class="informacion">
              <label for="Nombre"><strong>Nombre del usuario:</strong></label>
              <p id="nombre-texto"><?php echo "$NombreUsuario"; ?></p>

              <button onclick="editarcampo('nombre')"><i class="fa-solid fa-pen-to-square"></i></button>

            </div>
            <div class="informacion">
              <input class="editarcampo" type="text" id="nombre-input" value="<?php echo "$NombreUsuario"; ?>"
                style="display: none;">
              <button id="guardar-nombre" style="display: none;" onclick="guardarCampo('nombre')"><i
                  class="fa-solid fa-hammer"></i></button>
            </div>

            <div class="informacion">
              <label for="Apellido"><strong>Apellido del usuario:</strong></label>
              <p id="apellido-texto"><?php echo "$ApellidoUsuario"; ?></p>

              <button onclick="editarcampo('apellido')"><i class="fa-solid fa-pen-to-square"></i></button>

            </div>

            <div class="informacion">
              <input class="editarcampo" type="text" id="apellido-input" value="<?php echo "$ApellidoUsuario"; ?>"
                style="display: none;">
              <button id="guardar-apellido" style="display: none;" onclick="guardarCampo('apellido')"><i
                  class="fa-solid fa-hammer"></i></button>
            </div>
          </div>
          <div class="usuarioInfo">
            <h3>Datos del usuario</h3>
            <div class="informacion">
              <label for="Cedula"><strong>Cédula del usuario:</strong></label>
              <p id="cedula-texto"><?php echo "$CedulaUsuario"; ?></p>
            </div>
            <div class="informacion">
              <label for="Fecha-de-nacimiento"><strong>Fecha de nacimiento:</strong></label>
              <p id="fecha-texto"><?php echo "$FechaUsuario"; ?></p>

              <button onclick="editarcampo('fecha')"><i class="fa-solid fa-pen-to-square"></i></button>

            </div>
            <div class="informacion">
              <input class="editarcampo" type="date" id="fecha-input" value="<?php echo "$FechaUsuario"; ?>"
                style="display: none;">
              <button id="guardar-fecha" style="display: none;" onclick="guardarCampo('fecha')"><i
                  class="fa-solid fa-hammer"></i></button>
            </div>

            <div class="informacion">
              <label for="EstadoActual"><strong>Estado actual:</strong></label>
              <p id="estado-texto">Soltero</p>

              <button onclick="editarcampo('estado')"><i class="fa-solid fa-pen-to-square"></i></button>

            </div>
            <div class="informacion">
              <input class="editarcampo" type="text" id="estado-input" value="Soltero" style="display: none;">
              <button id="guardar-estado" style="display: none;" onclick="guardarCampo('estado')"><i
                  class="fa-solid fa-hammer"></i></button>
            </div>
          </div>
          <div class="usuarioInfo">
            <h3>Credenciales</h3>

            <div class="informacion">
              <label for="Correo"><strong>Correo:</strong></label>
              <p> <?php echo "$EmailUsuario"; ?></p>

              <button onclick="editarcampo('email')"><i class="fa-solid fa-pen-to-square"></i></button>

            </div>
            <div class="informacion">
              <input class="editarcampo" type="email" id="email-input" value="<?php echo "$EmailUsuario"; ?>"
                style="display: none;">
              <button id="guardar-email" style="display: none;" onclick="guardarCampo('email')"><i
                  class="fa-solid fa-hammer"></i></button>
            </div>
            <div class="informacion">
              <label for="Clave"><strong>Contraseña:</strong></label>
              <button id="CambiarContraseñaButton" onclick="cambiarClave()"><strong>Cambiar contraseña</strong>
              </button>
            </div>

            <div class="cambiar-contraseña" id="cambiar-contraseña" popover>
              <h2>Cambiar Contraseña</h2>
              <a href="#" onclick="cambiarClave()" class="close">&times</a>
              <form id="form-cambiar-password">
                <label for="password-actual"><strong> Contraseña Actual:</strong> </label>
                <input type="password" id="password-actual" name="password_actual" required>

                <label for="password-nueva"><strong> Nueva Contraseña:</strong> </label>
                <input type="password" id="password-nueva" name="password_nueva" required>

                <label for="password-confirmar"><strong> Confirmar Nueva Contraseña:</strong> </label>
                <input type="password" id="password-confirmar" name="password_confirmar" required>

                <button type="submit"><strong>Actualizar Contraseña</strong></button>
              </form>
              <p id="mensaje"></p>
            </div>
          </div>
          <div class="usuarioInfo">
            <h3>Datos Academicos</h3>
            <label for="categoria"><strong>Categoría: <strong> <?php echo "$tipo"; ?></label>
            <?php if ($tipo=="estudiante") {
              echo '<br><label for="Carrera"><strong>Carrera cursando:</strong></label>' .$carrera;
  
            } ?>
          </div>
        </div>
      </div>

    </div>
    </div>
    <div class="right-column">
      Seguidos
      <div class="seguidos" id="seguidos">

      </div>

    </div>


  </main>
  <script src="configuracionUsuario.js"></script>
</body>

</html>