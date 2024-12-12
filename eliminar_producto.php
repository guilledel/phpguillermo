<?php
session_start();
require_once 'db.php';
require_once 'autenticacion.php';

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Verificar si el ID es válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_productos.php");
    exit;
}

$idProducto = $_GET['id'];

try {
    // Verificar si el producto existe
    $query = "SELECT * FROM products WHERE idProducto = :idProducto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();
    $producto = $stmt->fetch();

    // Si el producto no existe, redirigir al listado
    if (!$producto) {
        header("Location: listado_productos.php");
        exit;
    }

    // Eliminar el producto de la base de datos
    $query = "DELETE FROM products WHERE idProducto = :idProducto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();

    // Redirigir al listado de productos después de la eliminación
    header("Location: listado_productos.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
