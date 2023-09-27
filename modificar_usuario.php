<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $userID = $_POST["userID"];
    $nuevoNombreUsuario = $_POST["nuevoNombreUsuario"];
    $nuevaContrasena = $_POST["nuevaContrasena"];

    // Validar y procesar los datos (puedes agregar más validaciones)
    if (!empty($userID) && !empty($nuevoNombreUsuario) && !empty($nuevaContrasena)) {
        // Conectarse a la base de datos (ajusta los datos de conexión)
        $host = "127.0.0.1:3310";
        $username = "root";
        $password = "";
        $dbname = "inisiodesesion";

        $conexion = new mysqli($host, $username, $password, $dbname);

        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        // Consulta SQL para actualizar el usuario
        $sql = "UPDATE usuarios SET Usuario = '$nuevoNombreUsuario', Pass = '$nuevaContrasena' WHERE ID = $userID";

        if ($conexion->query($sql) === TRUE) {
            // La actualización fue exitosa
            echo "exito";
        } else {
            // Error en la actualización
            echo "Error al modificar usuario: " . $conexion->error;
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
