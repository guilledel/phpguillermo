<?php
// Conexión a la base de datos
$servername = "db";
$database = "guillermo";
$username = "root";
$password = "guillermo";

// Usando mysqli orientado a objetos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Verificar que las contraseñas coincidan
    if ($pass !== $confirm_pass) {
        echo '<script>alert("Las contraseñas no coinciden."); window.location.href="index.html";</script>';
        exit;
    }

    // Verificar si el correo electrónico ya está registrado
    $query = "SELECT COUNT(*) FROM users WHERE correoUsuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo '<script>alert("El correo electrónico ya está registrado."); window.location.href="index.html";</script>';
        exit;
    }

    // Hashear la contraseña con PASSWORD_DEFAULT
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO users (nombreUsuario, correoUsuario, contrasenaUsuario) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $user, $email, $hashed_pass);

    if ($stmt->execute()) {
        // Si se registra correctamente, redirigir al formulario de inicio de sesión
        echo 'Usuario registrado correctamente.<br>';
        echo '<a href="iniciarsesion.html"><button>Ir al inicio de sesión</button></a>';
    } else {
        // Si hay un error en el registro, volver al formulario de registro
        echo 'Error al registrar el usuario. Por favor, intente nuevamente.<br>';
        echo '<a href="index.html"><button>Volver al registro</button></a>';
    }
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
