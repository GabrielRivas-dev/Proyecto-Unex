<?php
include("conexion.php");

$token = $_POST['token'] ?? '';
$nueva = password_hash($_POST['nueva_contrasena'], PASSWORD_DEFAULT);

$stmt = $conex->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL, token_expira = NULL WHERE token_recuperacion = ?");
$stmt->bind_param("ss", $nueva, $token);
if ($stmt->execute()) {
    echo "Contraseña actualizada. Ahora puedes iniciar sesión.";
} else {
    echo "Error al actualizar la contraseña.";
}
