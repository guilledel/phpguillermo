<?php
// Iniciar sesión si no está ya activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';  // Archivo de conexión a la base de datos
require_once 'autenticacion.php';  // Archivo para verificar autenticación

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si la sesión no está activa, mostrar el mensaje de advertencia
    echo '
    <div style="text-align: center; color: white; font-size: 100px; font-weight: bold; background-color: red; padding: 50px; 
    animation: shake 0.5s infinite; text-transform: uppercase; letter-spacing: 10px;">
        no deberías estar aquí, se donde vives. corre.
    </div>
    <audio autoplay>
        <source src="alerta.mp3" type="audio/mpeg">
    </audio>';
    header("refresh:3; url=iniciarsesion.html"); // Redirige después de 3 segundos
    exit;
}

// Consulta para obtener todos los productos usando PDO
$query = "SELECT idProducto, nombreProducto, precioProducto, cantidadProducto FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #007bff;
            margin: 0 10px;
        }
        a:hover {
            color: #0056b3;
        }
        .btn {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border-radius: 3px;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons a {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            margin-right: 10px;
        }
        .nav-buttons a:hover {
            background-color: #0056b3;
        }

        /* Efecto shake para el mensaje */
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
            100% { transform: translateX(10px); }
        }
    </style>
</head>
<body>
    <h1>CRUD CAFETERIA</h1>

    <!-- Barra de navegación con botones -->
    <div class="nav-buttons">
        <a href="iniciarsesion.html">Iniciar sesión</a>
        <a href="index.html">Registrarse</a>
        <a href="cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['idProducto']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombreProducto']); ?></td>
                        <td><?php echo number_format($producto['precioProducto'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidadProducto']); ?></td>
                        <td>
                            <a class="btn" href="editar_producto.php?id=<?php echo $producto['idProducto']; ?>">Editar</a>
                            <a class="btn btn-danger" href="eliminar_producto.php?id=<?php echo $producto['idProducto']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay productos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="btn" href="agregar_producto.php">Agregar Producto</a>
</body>
</html>
