<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$database = "cafeteria_db";
$username = "guillermo";
$password = "guillermo";

// Usando mysqli orientado a objetos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Verificar si el correo electrónico existe en la base de datos
    $query = "SELECT idUsuario, nombreUsuario, contraseñaUsuario FROM users WHERE correoUsuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_name, $hashed_pass);
        $stmt->fetch();

        // Verificar si la contraseña ingresada coincide con el hash almacenado
        if (password_verify($pass, $hashed_pass)) {
            // Si las credenciales son correctas, iniciar sesión
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            header("Location: pagprotect.php"); // Redirigir a la página protegida
            exit;
        } else {
            // Si la contraseña no coincide
            echo '<script>alert("Contraseña incorrecta."); window.location.href="iniciarsesion.html";</script>';
        }
    } else {
        // Si no se encuentra el correo en la base de datos
        echo '<script>alert("Correo electrónico no registrado."); window.location.href="iniciarsesion.html";</script>';
    }

    $stmt->close();
}

$conn->close();
?>
