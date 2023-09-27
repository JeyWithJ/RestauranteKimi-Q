<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "127.0.0.1:3310";
    $username = "root";
    $password = "";
    $dbname = "inisiodesesion";

    $conexion = new mysqli($host, $username, $password, $dbname);

    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Consulta SQL para verificar el usuario y contraseña
    $sql = "SELECT * FROM usuarios WHERE Usuario = '$usuario' AND Pass = '$password'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 1) {
        // Inicio de sesion exitoso
        $_SESSION["usuario"] = $usuario;
        header("Location: perfil.php"); // Redirigir a la pagina de inicio de sesión exitosa
        exit();
    } else {
        // Usuario o contraseña incorrectos
        header("Location: login.php?error=1");
        exit();
    }

    $conexion->close();
}
?>
