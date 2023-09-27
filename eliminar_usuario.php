<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger el ID del usuario a eliminar
    $userID = $_POST["userID"];

    // Conectarse a la base de datos (ajusta los datos de conexión)
    $host = "127.0.0.1:3310";
    $username = "root";
    $password = "";
    $dbname = "inisiodesesion";

    $conexion = new mysqli($host, $username, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    // Consulta SQL para eliminar el usuario por su ID
    $sql = "DELETE FROM usuarios WHERE ID = $userID";

    if ($conexion->query($sql) === TRUE) {
        // La eliminación fue exitosa
        echo "exito";
    } else {
        // Error en la eliminación
        echo "Error al eliminar usuario: " . $conexion->error;
    }

    $conexion->close();
} else {
    // Redirigir si se accede directamente al archivo sin datos POST
    header("Location: gestion_usuarios.php");
    exit();
}
?>
