<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header('Location: iniciarsesion.html');
    exit;
}

// Si está autenticado, mostrar la página protegida
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Protegida</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p>Esta es una página protegida. Solo los usuarios autenticados pueden acceder.</p>
        <a href="cerrarsesion.php">Cerrar sesión</a>
    </div>
</body>
</html>
