<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>

<body>
<img class="imagenUnex" src="uploads/imagen Unex3.png" alt="">
    <header class="header">
        <div class="logo">
            <a href="#">Unex</a>
        </div>
    </header>
    <main class="fondo">
        <form method="post" id="Miformulario">
            <div class="contenedor">
                <h1>Iniciar sesión en Unex</h1>
                <?php
                include("validar.php");
                ?>
                <div class="input">
                    
                    <input type="email" id="email" name="email" autocomplete="off" placeholder="" required>
                    <label for="email">Email:</label>
                </div>
                <div class="input">

                    <input type="password" id="password" name="clave" autocomplete="off" placeholder="" required>
                    <label for="password">Contraseña:</label>
                </div>
                <div class="recordatorio-register">
                    <label for="#"><input type="checkbox">Recordar inicio</label>
                    <label for="#">No tienes cuenta? <a href="registro.php">Registrar</a></label>
                </div>
                <button class="button" type="submit" name="Enviar" value="Enviar">Iniciar</button>
            </div>
        </form>
    </main>
    <script src=""></script>
</body>

</html>