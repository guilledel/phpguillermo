<?php
session_start();
require_once 'db.php';
require_once 'autenticacion.php';

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Verificar si se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_productos.php");
    exit;
}

$idProducto = $_GET['id'];

// Lógica para obtener datos del producto
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM products WHERE idProducto = :idProducto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header("Location: listado_productos.php");
        exit;
    }
}

// Lógica para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreProducto = trim($_POST['nombreProducto']);
    $descripcionProducto = trim($_POST['descripcionProducto']);
    $precioProducto = $_POST['precioProducto'];
    $stockProducto = $_POST['stockProducto'];

    $errores = [];
    if (empty($nombreProducto)) $errores[] = "El nombre del producto es obligatorio.";
    if (empty($descripcionProducto)) $errores[] = "La descripción del producto es obligatoria.";
    if (!is_numeric($precioProducto) || $precioProducto <= 0) $errores[] = "El precio debe ser un número positivo.";
    if (!filter_var($stockProducto, FILTER_VALIDATE_INT) || $stockProducto < 0) $errores[] = "El stock debe ser un número entero no negativo.";

    if (empty($errores)) {
        try {
            $query = "UPDATE products 
                      SET nombreProducto = :nombreProducto, descripcionProducto = :descripcionProducto, 
                          precioProducto = :precioProducto, cantidadProducto = :stockProducto
                      WHERE idProducto = :idProducto";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':descripcionProducto', $descripcionProducto);
            $stmt->bindParam(':precioProducto', $precioProducto);
            $stmt->bindParam(':stockProducto', $stockProducto);
            $stmt->bindParam(':idProducto', $idProducto);

            $stmt->execute();

            header("Location: listado_productos.php");
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<!-- Formulario de edición -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>/* Estilos similares al archivo agregar_producto.php */</style>
</head>
<body>
    <h1>Editar Producto</h1>
    <?php if (!empty($errores)): ?>
        <div class="errores">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="nombreProducto">Nombre:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo htmlspecialchars($producto['nombreProducto']); ?>" required>
        <label for="descripcionProducto">Descripción:</label>
        <textarea id="descripcionProducto" name="descripcionProducto" required><?php echo htmlspecialchars($producto['descripcionProducto']); ?></textarea>
        <label for="precioProducto">Precio:</label>
        <input type="number" id="precioProducto" name="precioProducto" step="0.01" value="<?php echo htmlspecialchars($producto['precioProducto']); ?>" required>
        <label for="stockProducto">Stock:</label>
        <input type="number" id="stockProducto" name="stockProducto" value="<?php echo htmlspecialchars($producto['cantidadProducto']); ?>" required>
        <button type="submit">Actualizar Producto</button>
    </form>
</body>
</html>
