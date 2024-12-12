<?php
session_start();
require_once 'db.php'; // Archivo de conexión a la base de datos
require_once 'autenticacion.php'; // Archivo para verificar autenticación

// Verificar si el usuario está autenticado
verificar_autenticacion();

// Lógica para manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombreProducto = trim($_POST['nombreProducto']);
    $descripcionProducto = trim($_POST['descripcionProducto']);
    $precioProducto = $_POST['precioProducto'];
    $stockProducto = $_POST['stockProducto'];

    // Validar la entrada de datos
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

    // Si no hay errores, procesar los datos
    if (empty($errores)) {
        try {
            $query = "INSERT INTO products (nombreProducto, descripcionProducto, precioProducto, cantidadProducto) 
                      VALUES (:nombreProducto, :descripcionProducto, :precioProducto, :stockProducto)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':nombreProducto', $nombreProducto);
            $stmt->bindParam(':descripcionProducto', $descripcionProducto);
            $stmt->bindParam(':precioProducto', $precioProducto);
            $stmt->bindParam(':stockProducto', $stockProducto);

            $stmt->execute();

            // Redirigir al listado de productos
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
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            text-align: center;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .errores {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Agregar Nuevo Producto</h1>
    
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
        <label for="nombreProducto">Nombre del Producto:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo htmlspecialchars($_POST['nombreProducto'] ?? ''); ?>" required>

        <label for="descripcionProducto">Descripción del Producto:</label>
        <textarea id="descripcionProducto" name="descripcionProducto" rows="4" required><?php echo htmlspecialchars($_POST['descripcionProducto'] ?? ''); ?></textarea>

        <label for="precioProducto">Precio:</label>
        <input type="number" id="precioProducto" name="precioProducto" step="0.01" value="<?php echo htmlspecialchars($_POST['precioProducto'] ?? ''); ?>" required>

        <label for="stockProducto">Stock:</label>
        <input type="number" id="stockProducto" name="stockProducto" value="<?php echo htmlspecialchars($_POST['stockProducto'] ?? ''); ?>" required>

        <button type="submit">Agregar Producto</button>
    </form>
</body>
</html>
