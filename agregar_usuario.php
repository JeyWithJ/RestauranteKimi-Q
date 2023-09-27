<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombreCompleto = $_POST["nombreCompleto"];
    $nombreUsuario = $_POST["nombreUsuario"];
    $contrasena = $_POST["contrasena"];

    // Validar y procesar los datos (puedes agregar más validaciones)
    if (!empty($nombreCompleto) && !empty($nombreUsuario) && !empty($contrasena)) {
        // Conectarse a la base de datos (ajusta los datos de conexión)
        $host = "127.0.0.1:3310";
        $username = "root";
        $password = "";
        $dbname = "inisiodesesion";

        $conexion = new mysqli($host, $username, $password, $dbname);

        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        // Consulta SQL para insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (NombreCompleto, Usuario, Pass) VALUES ('$nombreCompleto', '$nombreUsuario', '$contrasena')";

        if ($conexion->query($sql) === TRUE) {
            // La inserción fue exitosa
            echo "exito";
        } else {
            // Error en la inserción
            echo "Error al agregar usuario: " . $conexion->error;
        }

        $conexion->close();
    } else {
        // Campos obligatorios vacíos
        echo "Por favor, completa todos los campos.";
    }
} else {
    // Redirigir si se accede directamente al archivo sin datos POST
    header("Location: gestion_usuarios.php");
    exit();
}
?>
