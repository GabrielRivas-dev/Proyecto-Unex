<?php
include ("conexion.php");
session_start();

if (isset($_SESSION['id'])) {
    $stmt = $conex->prepare("UPDATE usuarios SET session_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
}
// Destruimos todas las variables de sesión
session_unset();

// Destruimos la sesión
session_destroy();
setcookie("session_token", "", time() - 3600, "/");

// Redirigimos a la página de inicio de sesión
header('Location: login.php');
exit();
?>