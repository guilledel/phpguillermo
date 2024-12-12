<?php
session_start();
require_once 'db.php';
require_once 'autenticacion.php';

// Verificar si el usuario está autenticado
verificar_autenticacion();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_productos.php");
    exit;
}

$idProducto = $_GET['id'];

try {
    $query = "DELETE FROM products WHERE idProducto = :idProducto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();

    header("Location: listado_productos.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
