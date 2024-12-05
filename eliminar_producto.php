<?php
session_start();
require_once 'db.php';  // Archivo de conexión a la base de datos
require_once 'autenticacion.php';  // Archivo para verificar autenticación

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Verificar si el ID del producto está presente en la URL
if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    // Eliminar el producto de la base de datos
    $query = "DELETE FROM products WHERE idProducto = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    mysqli_stmt_execute($stmt);

    // Redirigir al listado de productos
    header("Location: listado_productos.php");
    exit;
} else {
    // Si no se pasa el ID del producto, redirigir
    header("Location: listado_productos.php");
    exit;
}
?>
