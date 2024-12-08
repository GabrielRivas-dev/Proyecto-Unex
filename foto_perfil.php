<?php
include("conexion.php");
$img =addslashes(file_get_contents($_FILES['img']['tmp_name']));
$query ="INSERT INTO tabla_imagenes (file) VALUES ('$img')";
$resultado= $conex->query($query);
$imagencargada= $resultado;

if ($resultado) {
    header('Location: PaginaPrincipal.php');
}
else{
    echo "Error al subir la imagen";
}

?>