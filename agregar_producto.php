<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: inicio_sesion.php"); // Redirigir si no hay sesión activa
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $host = "127.0.0.1:3310";
        $username = "root";
        $password = "";
        $dbname = "inisiodesesion"; //Cambio de nombre a la sesion 2

    $conexion = new mysqli($host, $username, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    // Obtener los datos del formulario
    $nombreProducto = $_POST["nombreProducto"];
    $categoria = $_POST["categoria"];
    $precio = $_POST["precio"];

    // Consulta SQL para agregar el producto a la tabla "menu"
    $sql = "INSERT INTO menu (NombreProducto, Categoria, Precio) VALUES ('$nombreProducto', '$categoria', '$precio')";

    if ($conexion->query($sql) === TRUE) {
        // Producto agregado exitosamente
        header("Location: menu.php"); // Redirigir de nuevo a la página del menú
        exit();
    } else {
        // Error en la inserción
        echo "Error al agregar el producto: " . $conexion->error;
    }

    $conexion->close();
}
?>
