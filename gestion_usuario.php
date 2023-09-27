<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylegestion.css">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <header>
    <div class="container_header">
            <div class="profile">
                <img src="./Images/usuario.png" alt="Perfil">
                <?php
                session_start();

                if (isset($_SESSION["usuario"])) {
                    // Obtener el nombre del usuario de la sesión
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
        <h1>Gestión de Usuarios</h1>

        <!-- Botón para agregar usuario siempre visible -->
        <button onclick="mostrarFormularioUsuario()">Agregar Usuario</button>

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

        // Consulta SQL para obtener la información de los usuarios
        $sql = "SELECT ID, NombreCompleto, Usuario FROM usuarios";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            // Mostrar la lista de usuarios en una tabla
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombre Completo</th><th>Nombre de Usuario</th><th>Acciones</th></tr>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$fila['ID']}</td>";
                echo "<td>{$fila['NombreCompleto']}</td>";
                echo "<td>{$fila['Usuario']}</td>";
                echo "<td>";
                echo "<select onchange='seleccionarAccion(this, {$fila['ID']})'>";
                echo "<option value=''>Seleccionar</option>";
                echo "<option value='modificar'>Modificar</option>";
                echo "<option value='eliminar'>Eliminar</option>";
                echo "</select>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            // Si no hay usuarios, muestra un mensaje
            echo "<p>No hay usuarios registrados.</p>";
        }
        ?>
    </div>

    <!-- Contenido de la página -->

    <!-- Formulario emergente para agregar usuarios (similar al de agregar producto) -->
    <div id="formularioUsuarioModal" class="modal">
    <div class="modal-content">
        <span class="cerrar" onclick="cerrarFormularioUsuario()">&times;</span>
        <h2>Agregar Usuario</h2>
        <form id="agregarUsuarioForm" method="POST">
            <label>Nombre Completo</label>
            <input type="text" name="nombreCompleto" required>

            <label>Nombre de Usuario</label>
            <input type="text" name="nombreUsuario" required>

            <label>Contraseña</label>
            <input type="password" name="contrasena" required>

            <button type="button" onclick="agregarUsuario()">Agregar</button>
        </form>
    </div>
</div>

<!-- Formulario emergente para modificar usuario -->
<div id="formularioModificarUsuarioModal" class="modal">
    <div class="modal-content">
        <span class="cerrar" onclick="cerrarFormularioModificarUsuario()">&times;</span>
        <h2>Modificar Usuario</h2>
        <form id="modificarUsuarioForm" method="POST">
            <input type="hidden" name="userID" id="userID"> <!-- Campo oculto para almacenar el ID del usuario -->
            
            <label>Nombre de Usuario</label>
            <input type="text" name="nuevoNombreUsuario" id="nuevoNombreUsuario" required>

            <label>Contraseña</label>
            <input type="password" name="nuevaContrasena" id="nuevaContrasena" required>

            <button type="button" onclick="modificarUsuario()">Guardar Cambios</button>
        </form>
    </div>
</div>


    <script>
        //Funcion para agregar usuario y mostrar mensaje
        function agregarUsuario() {
        var form = document.getElementById("agregarUsuarioForm");
        var formData = new FormData(form);

        // Realizar una solicitud AJAX para agregar el usuario
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "agregar_usuario.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // La solicitud se completo con exito
                var respuesta = xhr.responseText;
                if (respuesta === "exito") {
                    alert("Usuario agregado correctamente.");
                    cerrarFormularioUsuario();
                } else {
                    alert("Error al agregar usuario. Por favor, inténtalo de nuevo.");
                }
            }
        };
        xhr.send(formData);
    }
        // Función para mostrar el formulario emergente de usuario
        function mostrarFormularioUsuario() {
            var modal = document.getElementById("formularioUsuarioModal");
            modal.style.display = "block";
        }

        // Función para cerrar el formulario emergente de usuario
        function cerrarFormularioUsuario() {
            var modal = document.getElementById("formularioUsuarioModal");
            modal.style.display = "none";
        }

     // Función para seleccionar acción (modificar o eliminar) en el menú desplegable
    function seleccionarAccion(select, userID) {
        var accion = select.value;
        if (accion === "modificar") {
            abrirFormularioModificar(userID); // Llama a la función para abrir el formulario de modificación
        } else if (accion === "eliminar") {
            if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
                // Llamar al archivo PHP para eliminar usuario
                eliminarUsuario(userID);
            }
        }
    }

    // Función para eliminar usuario mediante AJAX
    function eliminarUsuario(userID) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar_usuario.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var respuesta = xhr.responseText;
                if (respuesta === "exito") {
                    alert("Usuario eliminado correctamente.");
                    // Actualizar la pagina o realizar otras acciones si es necesario
                    window.location.reload();
                } else {
                    alert("Error al eliminar usuario. Por favor, inténtalo de nuevo.");
                }
            }
        };
        xhr.send("userID=" + userID);
    }
    // Función para abrir el formulario de modificación de usuario
    function abrirFormularioModificar(userID) {
        // Obtener datos del usuario mediante una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "obtener_usuario.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var datosUsuario = JSON.parse(xhr.responseText);

                // Llenar el formulario con los datos del usuario
                document.getElementById("modificarUsuarioForm").nombreUsuario.value = datosUsuario.nombreUsuario;
                document.getElementById("modificarUsuarioForm").contrasena.value = datosUsuario.contrasena;

                // Mostrar el formulario emergente
                document.getElementById("formularioModificarUsuarioModal").style.display = "block";
            }
        };
        xhr.send("userID=" + userID);
    }

    // Función para cerrar el formulario de modificación de usuario
    function cerrarFormularioModificarUsuario() {
        document.getElementById("formularioModificarUsuarioModal").style.display = "none";
    }

    // Función para modificar el usuario mediante AJAX
    function modificarUsuario() {
        // Obtener los datos del formulario
        var userID = obtenerParametroURL("userID"); // Puedes implementar esta función para obtener el userID de la URL
        var nuevoNombreUsuario = document.getElementById("modificarUsuarioForm").nombreUsuario.value;
        var nuevaContrasena = document.getElementById("modificarUsuarioForm").contrasena.value;

        // Realizar una solicitud AJAX para modificar el usuario
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "modificar_usuario.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var respuesta = xhr.responseText;
                if (respuesta === "exito") {
                    alert("Usuario modificado correctamente.");
                    cerrarFormularioModificarUsuario();
                    // Puedes realizar alguna acción adicional si es necesario
                } else {
                    alert("Error al modificar usuario. Por favor, inténtalo de nuevo.");
                }
            }
        };
        xhr.send("userID=" + userID + "&nuevoNombreUsuario=" + nuevoNombreUsuario + "&nuevaContrasena=" + nuevaContrasena);
    }

    // Función para obtener un parámetro de la URL
    function obtenerParametroURL(nombre) {
        var url = window.location.search.substring(1);
        var parametros = url.split('&');
        for (var i = 0; i < parametros.length; i++) {
            var parametro = parametros[i].split('=');
            if (parametro[0] === nombre) {
                return parametro[1];
            }
        }
        return null;
    }

    </script>
</body>
</html>
