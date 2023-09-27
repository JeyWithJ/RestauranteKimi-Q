<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registro</title>
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="registro.php" method="POST">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>
            <br>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <input type="submit" value="Registrarse">
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $host = "127.0.0.1:3310";
            $username = "root";
            $password = "";
            $dbname = "inisiodesesion";

            $conexion = new mysqli($host, $username, $password, $dbname);

            if ($conexion->connect_error) {
                die("Error de conexión a la base de datos: " . $conexion->connect_error);
            }

            $nombre = $_POST["nombre"];
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            // Consulta SQL para insertar un nuevo usuario
            $sql = "INSERT INTO usuarios (NombreCompleto, Usuario, Pass) VALUES ('$nombre', '$usuario', '$password')";

            if ($conexion->query($sql) === TRUE) {
                echo "Registro exitoso.";
            } else {
                echo "Error en el registro: " . $conexion->error;
            }

            $conexion->close();
        }
        ?>

        
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
