<?php
include("conexion.php");
session_start();

$email = $_POST['email'] ?? '';
$codigo = rand(100000, 999999);
$_SESSION['codigo_verificacion'] = $codigo;
$_SESSION['email_verificacion'] = $email;

// Opcional: Guarda el código en la base de datos
$stmt = $conex->prepare("UPDATE usuarios SET codigo_recuperacion = ? WHERE email = ?");
$stmt->bind_param("is", $codigo, $email);
$stmt->execute();

// Mostrar en pantalla o enviar por email
echo "Tu código es: <strong>$codigo</strong>"; // ⚠️ Solo temporal. En producción se debe enviar por email.

echo '
<form method="POST" action="verificar_codigo.php">
  <input type="text" name="codigo" placeholder="Ingresa el código recibido" required>
  <button type="submit">Verificar</button>
</form>';
