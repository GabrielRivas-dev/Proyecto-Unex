<?php
if(isset($_POST['Enviar'])){
    if (
        strlen($_POST['email']) >= 1 &&
        strlen($_POST['clave']) >= 1
    ) {
        $email = trim($_POST['email']);
        $clave = $_POST['clave'];

        include("conexion.php");

        // Consulta a la base de datos
        $sql = "SELECT Clave, Nombre, id FROM usuarios WHERE email = ?";
        $stmt = $conex->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed_password = $row['Clave'];
            $NombreUsuario = $row['Nombre'];
            $idUsuario= $row['id'];
          
            // Verificar contraseña (asegúrate de que el algoritmo de hashing sea el correcto)
            if (password_verify($clave, $hashed_password)) {


                $sql = "SELECT Nombre, Apellido, Cedula, Fecha, Genero, Email, Clave, imagen FROM usuarios WHERE id=?";
                $stmt = $conex->prepare($sql);
                $stmt->bind_param("s", $idUsuario);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    $NombreUsuario =$row['Nombre'];
                    $ApellidoUsuario =$row['Apellido'];
                    $CedulaUsuario =$row['Cedula'];
                    $FechaUsuario =$row['Fecha'];
                    $GeneroUsuario =$row['Genero'];
                    $EmailUsuario =$row['Email'];
                    $ClaveUsuario =$row['Clave'];
                    $imagenUsuario =$row['imagen'];
                    
                
                    echo "<p class='exitoso'>Bienvenido $NombreUsuario</p>";
                    session_start();
                    $_SESSION['logueado']=true;
                    $_SESSION['id'] = $idUsuario;
                    $_SESSION['Nombre'] = $NombreUsuario;
                    $_SESSION['Apellido'] = $ApellidoUsuario;
                    $_SESSION['Cedula'] = $CedulaUsuario;
                    $_SESSION['Fecha'] = $FechaUsuario;
                    $_SESSION['Genero'] = $GeneroUsuario;
                    $_SESSION['Email'] = $EmailUsuario;
                    $_SESSION['Clave'] = $ClaveUsuario;
                    $_SESSION['imagen'] = $imagenUsuario;
                    header('Location: PaginaPrincipal.php');
                exit();
                }
                
            } else {
                $_SESSION['logueado']=false;
                echo "<p class='rechazado'>Contraseña incorrecta</p>";
            }
        } else {
            echo "<p class='rechazado'>Usuario no encontrado</p>";
        }

        $stmt->close();
        $conex->close();
    }
}
?>
