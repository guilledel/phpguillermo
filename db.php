<?php
$servername = "localhost";
$database = "cafeteria_db";
$username = "guillermo";
$password = "guillermo";

// Creamos la conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Comprobamos la conexión
if (!$conn) {
    die("Conexión a la base de datos fallida: " .  mysqli_connect_error());
}
echo "Conectado con éxito!!";
mysqli_close($conn);
?>