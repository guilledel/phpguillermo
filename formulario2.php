<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['email'];
    $clave = $_POST['clave'];
    $repetir_clave = $_POST['repetir_clave'];

    // Verificar si las claves coinciden
    if ($clave !== $repetir_clave) {
        echo "Las claves no coinciden.";
    } else {
        // Verificar si el correo ya está registrado
        try {
            $sql = "SELECT * FROM users WHERE correoUsuario = :correo";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                echo "Este correo ya está registrado.";
            } else {
                // Encriptar la clave
                $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

                // Insertar en la base de datos
                $sql = "INSERT INTO users (nombreUsuario, correoUsuario, contrasenaUsuario) VALUES (:nombre, :correo, :clave)";
                $stmt = $conn->prepare($sql);

                // Vincular parámetros
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':clave', $clave_hash);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    echo "Usuario registrado con éxito";
                } else {
                    echo "Error al registrar el usuario";
                }
            }
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
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: white;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <form action="formulario2.php" method="POST">
        <h2>Registro de Usuario</h2>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="clave" placeholder="Clave" required>
        <input type="password" name="repetir_clave" placeholder="Repetir Clave" required>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
