<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleperfil.css">
    <title>Página Principal</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="profile">
                <img src="./Images/usuario.png" alt="Perfil">
                <?php
                session_start();

                if (isset($_SESSION["usuario"])) {
                    // Obtén el nombre del usuario de la sesión
                    $nombreUsuario = $_SESSION["usuario"];

                    // Conecta a la base de datos
                    $host = "127.0.0.1:3310";
                    $username = "root";
                    $password = "";
                    $dbname = "inisiodesesion";

                    $conexion = new mysqli($host, $username, $password, $dbname);

                    if ($conexion->connect_error) {
                        die("Error de conexión a la base de datos: " . $conexion->connect_error);
                    }

                    // Consulta SQL para obtener el NombreCompleto del usuario
                    $sql = "SELECT NombreCompleto FROM usuarios WHERE Usuario = '$nombreUsuario'";
                    $resultado = $conexion->query($sql);

                    if ($resultado->num_rows == 1) {
                        $fila = $resultado->fetch_assoc();
                        $nombreCompleto = $fila["NombreCompleto"];
                        echo "<span>$nombreCompleto</span>";
                    }

                    $conexion->close();
                }
                ?>
            </div>
            <nav>
                <ul>
                    <li><a href="menu.php">Menu/Inventario</a></li>
                    <li><a href="gestion_usuario.php">Gestion de Usuarios</a></li>  
                    <li><a href="#">Ventas</a></li>
                    <li><a href="#">Mapa</a></li>
                    <li><a href="cerrar_sesion.php">LogOut</a></li>
                </ul>
            </nav>
        </div>
    </header>

   

</body>
</html>
