<?php
if(isset($_POST['Enviar'])){
    if (strlen($_POST['nombre'])>=1 &&
    strlen($_POST['apellido'])>=1&&
    strlen($_POST['cedula'])>=1&&
    strlen($_POST['fecha-nacimiento'])>=1&&
    strlen($_POST['genero'])>=1&&
    strlen($_POST['email'])>=1&&
    strlen($_POST['clave'])>=1)
    {
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $cedula = trim($_POST['cedula']);
        $fecha_nacimiento = $_POST['fecha-nacimiento']; // formato 'YYYY-MM-DD'
        $genero = $_POST['genero'];
        $tipo = $_POST['tipo'];
        $carrera = ($tipo === 'estudiante') ? $_POST['carrera'] : null;
        $email = trim($_POST['email']);
        $clave = trim($_POST['clave']); // Cifrar la contraseña
        $hashed_password = password_hash($clave, PASSWORD_BCRYPT);
        $imagen = "uploads/default.jpg";
        // Incluir el archivo de conexión
        include("conexion.php");

        $sql = "INSERT INTO usuarios (Nombre, Apellido, Cedula, Fecha, Genero, Email, Clave, imagen, tipo, carrera) VALUES (?, ?, ?, ?, ?, ?, ? ,?, ?,?)";
    
        // Preparar la consulta
        if ($stmt = $conex->prepare($sql)) {
            // Enlazar los parámetros
            $stmt->bind_param("ssssssssss", $nombre, $apellido, $cedula, $fecha_nacimiento, $genero, $email, $hashed_password, $imagen, $tipo, $carrera);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<h3 class='exitoso'>Registro exitoso</h3>";
            } else {
                echo "<h3 class='rechazado'>Error al registrar: " . $stmt->error . "</h3>";
            }
    
            // Cerrar la sentencia
            $stmt->close();
        } else {
            echo "<h3>Error al preparar la consulta: " . $conex->error . "</h3>";
        }
    
        // Cerrar la conexión
        $conex->close();
    } 
}
    ?>
    