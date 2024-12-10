<?php
session_start();
require_once 'db.php';  // Archivo de conexión a la base de datos
require_once 'autenticacion.php';  // Archivo para verificar autenticación

// Verificar si el usuario está autenticado
verificar_autenticacion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombreProducto = $_POST['nombreProducto'];
    $precioProducto = $_POST['precioProducto'];
    $cantidadProducto = $_POST['cantidadProducto'];

    // Validar datos (aquí puedes agregar más validaciones)
    if (empty($nombreProducto) || empty($precioProducto) || empty($cantidadProducto)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Insertar el producto en la base de datos utilizando PDO
        try {
            $query = "INSERT INTO products (nombreProducto, precioProducto, cantidadProducto) 
                      VALUES (:nombreProducto, :precioProducto, :cantidadProducto)";
            
            // Preparar la consulta
            $stmt = $conn->prepare($query);
            
            // Vincular parámetros
            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':precioProducto', $precioProducto);
            $stmt->bindParam(':cantidadProducto', $cantidadProducto);
            
            // Ejecutar la consulta
            $stmt->execute();

            // Redirigir al listado de productos si la inserción es exitosa
            header("Location: listado_productos.php");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
</head>
<body>
    <h1>Agregar Nuevo Producto</h1>
    <form method="POST">
        <label for="nombreProducto">Nombre del Producto:</label><br>
        <input type="text" id="nombreProducto" name="nombreProducto" required><br><br>
        
        <label for="precioProducto">Precio:</label><br>
        <input type="number" id="precioProducto" name="precioProducto" required step="0.01"><br><br>
        
        <label for="cantidadProducto">Cantidad:</label><br>
        <input type="number" id="cantidadProducto" name="cantidadProducto" required><br><br>
        
        <button type="submit">Agregar Producto</button>
    </form>
</body>
</html>
