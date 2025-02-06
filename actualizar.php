<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    $repetir_clave = $_POST['repetir_clave'];

    // Verificar si las claves coinciden
    if ($clave !== $repetir_clave) {
        echo "Las claves no coinciden.";
    } else {
        // Encriptar la clave si coincide
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

        try {
            // Actualizar la información en la base de datos
            // Suponiendo que el email es único y se usa para identificar al usuario
            $sql = "UPDATE users SET nombreUsuario = :nombre, correoUsuario = :email, contrasenaUsuario = :clave WHERE correoUsuario = :email";
            $stmt = $conn->prepare($sql);

            // Vincular parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':clave', $clave_hash);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Datos actualizados correctamente";
            } else {
                echo "Error al actualizar los datos";
                // Depuración: Ver el número de filas afectadas
                echo " Filas afectadas: " . $stmt->rowCount();
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
    <title>Actualizar Datos</title>
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
    <form action="actualizar.php" method="POST">
        <h2>Actualizar Información</h2>
        <input type="text" name="nombre" placeholder="Nuevo Nombre" required>
        <input type="email" name="email" placeholder="Nuevo Email" required>
        <input type="password" name="clave" placeholder="Nueva Clave" required>
        <input type="password" name="repetir_clave" placeholder="Repetir Clave" required>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
