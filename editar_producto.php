<?php
session_start();
require_once 'db.php';  // Archivo de conexión a la base de datos
require_once 'autenticacion.php';  // Archivo para verificar autenticación

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Verificar si el ID del producto está presente en la URL
if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    // Obtener los datos del producto para editar
    $query = "SELECT * FROM products WHERE idProducto = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($result);

    // Si no se encuentra el producto, redirigir
    if (!$producto) {
        header("Location: listado_productos.php");
        exit;
    }

    // Si el formulario se envía
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombreProducto = $_POST['nombreProducto'];
        $precioProducto = $_POST['precioProducto'];
        $cantidadProducto = $_POST['cantidadProducto'];

        // Actualizar el producto en la base de datos
        $query = "UPDATE products SET nombreProducto = ?, precioProducto = ?, cantidadProducto = ? WHERE idProducto = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sdii", $nombreProducto, $precioProducto, $cantidadProducto, $idProducto);
        mysqli_stmt_execute($stmt);

        // Redirigir al listado de productos
        header("Location: listado_productos.php");
        exit;
    }
} else {
    // Si no se pasa el ID del producto, redirigir
    header("Location: listado_productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="POST">
        <label for="nombreProducto">Nombre del Producto:</label><br>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo htmlspecialchars($producto['nombreProducto']); ?>" required><br><br>
        <label for="precioProducto">Precio:</label><br>
        <input type="number" id="precioProducto" name="precioProducto" value="<?php echo htmlspecialchars($producto['precioProducto']); ?>" required step="0.01"><br><br>
        <label for="cantidadProducto">Cantidad:</label><br>
        <input type="number" id="cantidadProducto" name="cantidadProducto" value="<?php echo htmlspecialchars($producto['cantidadProducto']); ?>" required><br><br>
        <button type="submit">Actualizar Producto</button>
    </form>
</body>
</html>
