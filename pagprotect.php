<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al formulario de inicio de sesión
    header("Location: iniciarsesion.html");
    exit;
}

// Obtener el nombre del usuario desde la sesión
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sistema de Cafetería</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Fondo llamativo */
        body {
            background: linear-gradient(135deg, #FF5F6D, #FFC371);
            font-family: 'Roboto', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* Estilo del contenedor principal */
        .container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            padding: 40px;
            width: 70%;
            max-width: 800px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            animation: bounceIn 2s ease;
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-size: 3em;
            color: #FFF;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            animation: fadeIn 3s ease;
        }

        p {
            font-size: 1.5em;
            margin: 20px 0;
            animation: fadeIn 4s ease;
        }

        a {
            text-decoration: none;
            color: #FFC371;
            background-color: #FF5F6D;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1.2em;
            margin-top: 30px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        a:hover {
            background-color: #FFC371;
            color: #FF5F6D;
            transform: scale(1.1);
        }

        /* Animaciones */
        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Fondo animado con partículas */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/45-degree-fabric-weave.png');
            animation: moveBackground 5s linear infinite;
            z-index: -1;
        }

        @keyframes moveBackground {
            0% { background-position: 0 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>
<body>
    <!-- Fondo animado -->
    <div class="background"></div>

    <!-- Contenedor principal -->
    <div class="container">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Has iniciado sesión correctamente.</p>
        <a href="cerrarsesion.php">Cerrar sesión</a>
    </div>
</body>
</html>
