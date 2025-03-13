<?php
session_start();
require "db.php";

// Función para limpiar tokens antiguos
function cleanOldTokens() {
    if (isset($_SESSION['old_tokens'])) {
        // Mantener solo los últimos 5 tokens
        if (count($_SESSION['old_tokens']) > 5) {
            array_shift($_SESSION['old_tokens']);
        }
    }
}

// Generar token para cada petición
function generateToken() {
    // Inicializar array de tokens antiguos si no existe
    if (!isset($_SESSION['old_tokens'])) {
        $_SESSION['old_tokens'] = array();
    }
    
    // Guardar el token actual si existe
    if (isset($_SESSION['token'])) {
        $_SESSION['current_token'] = $_SESSION['token'];
        // Guardar en el historial de tokens
        $_SESSION['old_tokens'][] = $_SESSION['token'];
        cleanOldTokens();
    }
    
    // Siempre generar un nuevo token
    $_SESSION['token'] = bin2hex(random_bytes(32));
    return $_SESSION['token'];
}

// Validar token
function validateToken($token) {
    // Verificar si el token coincide con el actual o alguno de los últimos tokens válidos
    $valid = false;
    
    if (isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token)) {
        $valid = true;
    } elseif (isset($_SESSION['old_tokens'])) {
        foreach ($_SESSION['old_tokens'] as $old_token) {
            if (hash_equals($old_token, $token)) {
                $valid = true;
                break;
            }
        }
    }
    
    if (!$valid) {
        die('Error de validación del token');
    }
    
    $_SESSION['current_token'] = $_SESSION['token'];
    // Generar nuevo token después de la validación
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Verificar token en todas las peticiones excepto GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    if (!isset($_POST['token'])) {
        die('Token no proporcionado');
    }
    validateToken($_POST['token']);
}

// Generar nuevo token para la siguiente petición
$token = generateToken();

// Función para obtener el token actual de forma segura
function getCurrentToken() {
    return isset($_SESSION['token']) ? $_SESSION['token'] : generateToken();
}

// Verificar token CSRF en solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Error de validación CSRF');
    }
}

// Generar nuevo token CSRF en cada solicitud
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Inicializar variables
$search_get = isset($_GET['search_get']) ? $_GET['search_get'] : '';
$search_post = isset($_POST['search_post']) ? $_POST['search_post'] : '';
$productos = [];

try {
    // Consulta base para productos
    $base_query = "SELECT * FROM products";

    // Procesar búsqueda GET
    if (!empty($search_get)) {
        $query = $base_query . " WHERE nombreProducto LIKE :search OR descripcionProducto LIKE :search";
        $stmt = $conn->prepare($query);
        $search_term = "%" . $search_get . "%";
        $stmt->bindParam(':search', $search_term);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Procesar búsqueda POST
    elseif (!empty($search_post)) {
        $query = $base_query . " WHERE nombreProducto LIKE :search OR descripcionProducto LIKE :search";
        $stmt = $conn->prepare($query);
        $search_term = "%" . $search_post . "%";
        $stmt->bindParam(':search', $search_term);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Sin búsqueda, mostrar todos los productos
    else {
        $stmt = $conn->query($base_query);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>
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
            background: linear-gradient(135deg, #B5EAD7 0%, #C7CEEA 100%);
            color: #4A4A4A;
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
            color: #4A4A4A;
            font-size: 2.8rem;
            margin-bottom: 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            animation: softGlow 2s ease-in-out infinite alternate;
        }

        @keyframes softGlow {
            from {
                text-shadow: 0 0 10px #fff, 0 0 20px #B5EAD7;
            }
            to {
                text-shadow: 0 0 20px #fff, 0 0 30px #C7CEEA;
            }
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
            background: rgba(255, 255, 255, 0.4);
            color: #4A4A4A;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
        }

        .nav-buttons a:hover {
            background: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        /* Formularios de búsqueda */
        .search-form {
            background: rgba(255, 255, 255, 0.3);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.4);
        }

        .search-form h2 {
            margin-top: 0;
            color: #4A4A4A;
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .search-form input[type="text"] {
            width: calc(100% - 140px);
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 25px;
            font-size: 1rem;
            color: #4A4A4A;
            background: rgba(255,255,255,0.4);
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .search-form input[type="text"]::placeholder {
            color: rgba(74,74,74,0.7);
        }

        .search-form input[type="text"]:focus {
            outline: none;
            border-color: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.5);
        }

        .search-form button {
            padding: 1rem 1.5rem;
            background: rgba(255,255,255,0.4);
            color: #4A4A4A;
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            backdrop-filter: blur(5px);
        }

        .search-form button:hover {
            background: rgba(255,255,255,0.6);
            transform: translateY(-2px);
        }

        /* Resultados de búsqueda */
        .results {
            background: rgba(255,255,255,0.3);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.05);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.4);
            margin-bottom: 2rem;
        }

        .results h3 {
            color: #4A4A4A;
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1.5rem;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        th {
            background: rgba(255,255,255,0.3);
            color: #4A4A4A;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background: rgba(255,255,255,0.3);
            transition: background-color 0.3s ease;
        }

        /* Botones de acción */
        .btn {
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.4);
            color: #4A4A4A;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.6);
            backdrop-filter: blur(5px);
            display: inline-block;
            margin: 0.25rem;
        }

        .btn:hover {
            background: rgba(255,255,255,0.6);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-danger {
            background: rgba(255, 182, 193, 0.4);
            border-color: rgba(255, 182, 193, 0.6);
            color: #D84A4A;
        }

        .btn-danger:hover {
            background: rgba(255, 182, 193, 0.6);
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

            table {
                display: block;
                overflow-x: auto;
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

        <!-- Formulario de búsqueda GET -->
        <form method="get" class="search-form">
            <h2>Búsqueda por GET</h2>
            <input type="text" name="search_get" value="<?php echo htmlspecialchars($search_get); ?>" placeholder="Buscar productos...">
            <button type="submit">Buscar</button>
        </form>

        <!-- Formulario de búsqueda POST -->
        <form method="post" class="search-form">
            <h2>Búsqueda por POST</h2>
            <input type="hidden" name="token" value="<?php echo getCurrentToken(); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="search_post" value="<?php echo htmlspecialchars($search_post); ?>" placeholder="Buscar productos...">
            <button type="submit">Buscar</button>
        </form>

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
                                        <a class="btn" href="editar_producto.php?id=<?php echo $producto['idProducto']; ?>&token=<?php echo getCurrentToken(); ?>">Editar</a>
                                        <form method="POST" action="eliminar_producto.php" style="display: inline;">
                                            <input type="hidden" name="token" value="<?php echo getCurrentToken(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $producto['idProducto']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</button>
                                        </form>
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
                                        <a class="btn" href="editar_producto.php?id=<?php echo $producto['idProducto']; ?>&token=<?php echo getCurrentToken(); ?>">Editar</a>
                                        <form method="POST" action="eliminar_producto.php" style="display: inline;">
                                            <input type="hidden" name="token" value="<?php echo getCurrentToken(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $producto['idProducto']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</button>
                                        </form>
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
                                        <a class="btn" href="editar_producto.php?id=<?php echo $producto['idProducto']; ?>&token=<?php echo getCurrentToken(); ?>">Editar</a>
                                        <form method="POST" action="eliminar_producto.php" style="display: inline;">
                                            <input type="hidden" name="token" value="<?php echo getCurrentToken(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $producto['idProducto']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</button>
                                        </form>
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
</body>
</html>