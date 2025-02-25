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

// Inicializar variables
$search_get = "";
$search_post = "";

// Procesar la búsqueda con GET (vulnerable a XSS)
if (isset($_GET['search_get'])) {
    $search_get = $_GET['search_get'];  // No se sanitiza la entrada (vulnerabilidad XSS)
}

// Procesar la búsqueda con POST (vulnerable a XSS)
if (isset($_POST['search_post'])) {
    $search_post = $_POST['search_post'];  // No se sanitiza la entrada (vulnerabilidad XSS)
}

// Limpiar búsqueda
if (isset($_GET['clear_search'])) {
    $search_get = "";
    $search_post = "";
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Redirige sin parámetros de búsqueda
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Productos</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a2e;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #e94560;
            font-size: 2.5rem;
            margin-bottom: 40px;
            font-weight: 600;
        }

        /* Formularios de búsqueda */
        .search-form {
            background: #16213e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .search-form:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .search-form h2 {
            margin-top: 0;
            color: #e94560;
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            width: calc(100% - 140px);
            padding: 12px;
            border: 1px solid #0f3460;
            border-radius: 8px;
            font-size: 1rem;
            color: #e0e0e0;
            background-color: #1a1a2e;
            transition: border-color 0.2s ease;
        }

        .search-form input[type="text"]:focus {
            border-color: #e94560;
            outline: none;
        }

        .search-form button {
            padding: 12px 24px;
            background-color: #e94560;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .search-form button:hover {
            background-color: #c2334d;
        }

        /* Resultados de búsqueda */
        .results {
            background: #16213e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .results h3 {
            margin-top: 0;
            color: #e94560;
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #0f3460;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #1a1a2e;
            color: #e94560;
            font-weight: 600;
        }

        tr:hover {
            background-color: #0f3460;
        }

        /* Botones */
        .btn {
            padding: 8px 16px;
            background-color: #e94560;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background-color: #c2334d;
        }

        .btn-danger {
            background-color: #ff4c4c;
        }

        .btn-danger:hover {
            background-color: #e63939;
        }

        /* Barra de navegación */
        .nav-buttons {
            text-align: center;
            margin-bottom: 40px;
        }

        .nav-buttons a {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #e94560;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .nav-buttons a:hover {
            background-color: #c2334d;
        }

        /* Animación de shake */
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
    <div class="container">
        <h1>CRUD CAFETERIA</h1>

        <!-- Barra de navegación con botones -->
        <div class="nav-buttons">
            <a href="iniciarsesion.html" class="btn">Iniciar sesión</a>
            <a href="index.html" class="btn">Registrarse</a>
            <a href="cerrarsesion.php" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <!-- Formulario de búsqueda con GET (vulnerable a XSS) -->
        <div class="search-form">
            <h2>Búsqueda con GET (Vulnerable a XSS)</h2>
            <form method="GET" action="">
                <input type="text" name="search_get" placeholder="Buscar productos..." value="<?php echo $search_get; ?>">
                <button type="submit">Buscar</button>
                <?php if (!empty($search_get)): ?>
                    <a href="?clear_search=true" class="btn btn-danger">Limpiar Búsqueda</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Resultados de la búsqueda con GET -->
        <?php if (!empty($search_get)): ?>
            <div class="results">
                <h3>Resultados para: <?php echo $search_get; ?></h3>
                <p>Aquí irían los resultados de la búsqueda...</p>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda con POST (vulnerable a XSS) -->
        <div class="search-form">
            <h2>Búsqueda con POST (Vulnerable a XSS)</h2>
            <form method="POST" action="">
                <input type="text" name="search_post" placeholder="Buscar productos..." value="<?php echo $search_post; ?>">
                <button type="submit">Buscar</button>
                <?php if (!empty($search_post)): ?>
                    <a href="?clear_search=true" class="btn btn-danger">Limpiar Búsqueda</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Resultados de la búsqueda con POST -->
        <?php if (!empty($search_post)): ?>
            <div class="results">
                <h3>Resultados para: <?php echo $search_post; ?></h3>
                <p>Aquí irían los resultados de la búsqueda...</p>
            </div>
        <?php endif; ?>

        <!-- Listado completo de productos (si no hay búsqueda) -->
        <?php if (empty($search_get) && empty($search_post)): ?>
            <div class="results">
                <h2>Listado Completo de Productos</h2>
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
                        <?php
                        $query = "SELECT * FROM products";
                        $stmt = $conn->query($query);
                        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
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
            </div>
        <?php endif; ?>

        <a class="btn" href="agregar_producto.php">Agregar Producto</a>
    </div>

    <!-- Vulnerabilidad XSS -->
    <?php if (!empty($search_get) || !empty($search_post)): ?>
        <script>
            // Mostrar una alerta con el valor de la búsqueda
            alert("XSS: <?php echo !empty($search_get) ? $search_get : $search_post; ?>");

            // Mostrar las cookies en una alerta
            alert("Cookies: " + document.cookie);

            // Enviar las cookies a un archivo PHP para guardarlas en cookies.txt
            fetch('guardar_cookies.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cookie: document.cookie }),
            });
        </script>
    <?php endif; ?>
</body>
</html>