<?php
if(isset($_POST['Enviar'])){
    if (
        strlen($_POST['email']) >= 1 &&
        strlen($_POST['clave']) >= 1
    ) {
        $email = trim($_POST['email']);
        $clave = $_POST['clave'];
        $mantener_sesion = isset($_POST['mantener_sesion']);

        include("conexion.php");

        // Consulta a la base de datos
        $sql = "SELECT Clave, Nombre, estado, id FROM usuarios WHERE email = ?";
        $stmt = $conex->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed_password = $row['Clave'];
            $NombreUsuario = $row['Nombre'];
            $idUsuario= $row['id'];
            if ($row['estado'] != 'suspendido') {
         
            // Verificar contraseña (asegúrate de que el algoritmo de hashing sea el correcto)
            if (password_verify($clave, $hashed_password)) {


                $sql = "SELECT Nombre, Apellido, Cedula, Fecha, Genero, Email, Clave, imagen, presentacion, tipo, carrera, rol FROM usuarios WHERE id=?";
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
                    $presentacionUsuario =$row['presentacion'];
                    $tipo =$row['tipo'];
                    $carrera =$row['carrera'];
                    $rol =$row['rol'];
                
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
                    $_SESSION['presentacion'] = $presentacionUsuario;
                    $_SESSION['tipo'] = $tipo;
                    $_SESSION['carrera'] = $carrera;
                    $_SESSION['rol'] = $rol;

                    if ($mantener_sesion) {
        $token = bin2hex(random_bytes(16));
        setcookie("session_token", $token, time() + (86400 * 30), "/");

        $stmt = $conex->prepare("UPDATE usuarios SET session_token = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $idUsuario);
        $stmt->execute();
    }
                    header('Location: PaginaPrincipal.php');
                exit();
                }
                
            } else {
                $_SESSION['logueado']=false;
                 echo "<script>alert('Contraseña incorrecta.');</script>";
            }
           } else {
             echo "<script>alert('Tu cuenta ha sido suspendida por el administrador.');</script>";
        }
        } else {
             echo "<script>alert('Usuario no encontrado.');</script>";
        }

        $stmt->close();
        $conex->close();
    }
}
?>
