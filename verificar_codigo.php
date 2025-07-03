<?php
session_start();
$codigoIngresado = $_POST['codigo'] ?? '';

if ($codigoIngresado == $_SESSION['codigo_verificacion']) {
    header("Location: nueva_contrasena.php");
    exit();
} else {
    echo "Código incorrecto.";
}
