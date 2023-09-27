<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylemenu.css">
    <title>Menú</title>
</head>
<body>
<header>
        <div class="container_header">
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
                        echo "<a href='perfil.php'>$nombreCompleto</a>";
                    }

                    $conexion->close();
                }
                ?>
            </div>
            <nav class= "nav_header">
                <ul>
                    <li class ="li_header"><a class="a_header" href="menu.php">Menu/Inventario</a></li>
                    <li class ="li_header"><a class="a_header" href="#">Gestion de Usuarios</a></li>
                    <li class ="li_header"><a class="a_header" href="#">Ventas</a></li>
                    <li class ="li_header"><a class="a_header" href="#">Mapa</a></li>
                    <li class ="li_header"><a class="a_header" href="cerrar_sesion.php">LogOut</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Menú</h1>

        <?php
        // Conexión a la base de datos
        $host = "127.0.0.1:3310";
        $username = "root";
        $password = "";
        $dbname = "inisiodesesion";

        $conexion = new mysqli($host, $username, $password, $dbname);

        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        // Consulta SQL para obtener los productos del menú
        $sql = "SELECT NombreProducto, Categoria, Precio FROM menu";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Mostrar la lista de productos
            echo "<ul>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<li>{$fila['NombreProducto']} ({$fila['Categoria']}): $ {$fila['Precio']}</li>";
            }
            echo "</ul>";
        } else {
            // Si no hay productos en el menú, muestra un mensaje
            echo "<p>No tienes ningún producto agregado. Agrégalos ahora.</p>";
        }
        ?>
        
        <!-- Botón para agregar producto siempre visible -->
        <button onclick="mostrarFormulario()">Agregar Producto</button>
    </div>

    <!-- Contenido de la página -->

    <!-- Formulario emergente para agregar productos (el código del modal permanece igual) -->
    <div id="formularioModal" class="modal">
        <div class="modal-content">
            <span class="cerrar" onclick="cerrarFormulario()">&times;</span>
            <h2>Agregar Producto</h2>
            <form action="agregar_producto.php" method="POST">
                <label>Nombre del Producto</label>
                <input type="text" name="nombreProducto" required>

                <label>Categoría</label>
                <select name="categoria" required>
                    <option value="comida">Comida</option>
                    <option value="bebida">Bebida</option>
                    <option value="licor">Licor</option>
                </select>

                <label>Precio</label>
                <input type="number" name="precio" step="0.01" required>

                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>

    <script>
        // Función para mostrar el formulario emergente
        function mostrarFormulario() {
            var modal = document.getElementById("formularioModal");
            modal.style.display = "block";
        }

        // Función para cerrar el formulario emergente
        function cerrarFormulario() {
            var modal = document.getElementById("formularioModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
