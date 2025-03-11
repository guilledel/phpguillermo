<?php
session_start();
require_once 'db.php'; // Conexión a la base de datos
require_once 'autenticacion.php'; // Verificación de autenticación

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar el token CSRF para la petición GET
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Error: Token CSRF inválido");
}

// Obtener el ID del producto a editar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de producto no válido.");
}

$idProducto = intval($_GET['id']);
$producto = null;

try {
    // Consultar los datos actuales del producto
    $query = "SELECT * FROM products WHERE idProducto = :idProducto";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        die("Producto no encontrado.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Lógica para manejar el formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar el token CSRF para la petición POST
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Token CSRF inválido");
    }

    // Obtener los datos del formulario
    $nombreProducto = trim($_POST['nombreProducto']);
    $descripcionProducto = trim($_POST['descripcionProducto']);
    $precioProducto = $_POST['precioProducto'];
    $stockProducto = $_POST['stockProducto'];

    // Validar los datos
    $errores = [];
    if (empty($nombreProducto)) {
        $errores[] = "El nombre del producto es obligatorio.";
    }
    if (empty($descripcionProducto)) {
        $errores[] = "La descripción del producto es obligatoria.";
    }
    if (!is_numeric($precioProducto) || $precioProducto <= 0) {
        $errores[] = "El precio debe ser un número positivo.";
    }
    if (!filter_var($stockProducto, FILTER_VALIDATE_INT) || $stockProducto < 0) {
        $errores[] = "El stock debe ser un número entero no negativo.";
    }

    if (empty($errores)) {
        try {
            // Actualizar los datos en la base de datos
            $query = "UPDATE products SET 
                        nombreProducto = :nombreProducto, 
                        descripcionProducto = :descripcionProducto, 
                        precioProducto = :precioProducto, 
                        cantidadProducto = :stockProducto 
                      WHERE idProducto = :idProducto";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':descripcionProducto', $descripcionProducto);
            $stmt->bindParam(':precioProducto', $precioProducto);
            $stmt->bindParam(':stockProducto', $stockProducto);
            $stmt->bindParam(':idProducto', $idProducto);

            $stmt->execute();

            // Redirigir al listado de productos
            header("Location: listado_productos.php");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al actualizar el producto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <style>
        /* Agrega aquí tus estilos */
    </style>
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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="nombreProducto">Nombre del Producto:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo htmlspecialchars($producto['nombreProducto']); ?>" required>

        <label for="descripcionProducto">Descripción del Producto:</label>
        <textarea id="descripcionProducto" name="descripcionProducto" rows="4" required><?php echo htmlspecialchars($producto['descripcionProducto']); ?></textarea>

        <label for="precioProducto">Precio:</label>
        <input type="number" id="precioProducto" name="precioProducto" step="0.01" value="<?php echo htmlspecialchars($producto['precioProducto']); ?>" required>

        <label for="stockProducto">Stock:</label>
        <input type="number" id="stockProducto" name="stockProducto" value="<?php echo htmlspecialchars($producto['cantidadProducto']); ?>" required>

        <button type="submit">Actualizar Producto</button>
    </form>
</body>
</html>
