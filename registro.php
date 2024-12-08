<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Registro Unex</title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="#">Unex</a>
        </div>
    </header>
    <main class="fondo">
        <form method="POST">
            <div class="contenedor">
                <h1>Registrar en Unex</h1>
                <?php
                include("registrar-usuarios.php")
                    ?>
                <div class="input">
                    <input type="text" id="nombre" name="nombre" placeholder="" required>
                    <label for="nombre">Nombre:</label>
                </div>
                <div class="input">
                    <input type="text" id="apellido" name="apellido" placeholder="" required>
                    <label for="apellido">Apellido:</label>
                </div>
                <div class="input">
                    <input type="number" id="cedula" name="cedula" placeholder="" required>
                    <label for="cedula">Cedula:</label>
                </div>
                <div class="fecha">
                    <label for="#">Fecha de nacimiento:</label>
                    <input type="date" name="fecha-nacimiento" min="1920-01-01" max="2024-10-20" required>
                </div>
                <div class="genero">
                    <label for="MoF">Genero:</label>
                    <label><input type="radio" name="genero" value="Masculino"> Masculino</label>
                    <label><input type="radio" name="genero" value="Femenino"> Femenino</label>
                </div>
                <div class="input">
                    <input type="email" name="email" id="email" autocomplete="off" placeholder="" required>
                    <label for="email">Email:</label>
                </div>
                <div class="input">
                    <input type="password" name="clave" id="clave" autocomplete="off" placeholder="" required>
                    <label for="clave">Contraseña:</label>
                </div>
                <div class="recordatorio-register">
                    <label for="#">Ya tienes una cuenta? <a href="login.php">Iniciar Sesion</a></label>
                </div>
                <button class="button" type="submit" name="Enviar" value="Enviar">Enviar</button>
            </div>

        </form>

    </main>
</body>

</html>