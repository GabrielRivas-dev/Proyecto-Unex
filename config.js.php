<?php
session_start();
header("Content-Type: application/javascript");

if (!isset($_SESSION['id'])) {
    echo "const usuarioActual = null;";
} else {
    echo "const usuarioActual = " . $_SESSION['id'] . ";";
}
?>
