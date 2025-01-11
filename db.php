<?php
$servername = "db";
$database = "guillermo";
$username = "root";
$password = "guillermo";

// Crear la conexión PDO
try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Configurar el modo de error de PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si lo deseas, puedes agregar un mensaje de éxito para verificar la conexión
    // echo "Conectado con éxito!";
} catch (PDOException $e) {
    // Si hay un error de conexión, muestra un mensaje
    echo "Error de conexión: " . $e->getMessage();
    die();
}
?>
