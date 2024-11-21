<?php
// Conexión a la base de datos
$servername = "localhost";
$database = "cafeteria_db";
$username = "guillermo";
$password = "guillermo";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Verificar que las contraseñas coincidan
    if ($pass !== $confirm_pass) {
        echo 'Las contraseñas no coinciden.';
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
        echo 'El correo electrónico ya está registrado.';
        exit;
    }

    // Hash de la contraseña
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO users (nombreUsuario, correoUsuario, contraseñaUsuario) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $user, $email, $hashed_pass);
    
    if ($stmt->execute()) {
        echo 'Usuario registrado correctamente.';
    } else {
        echo 'Error al registrar el usuario.';
    }
    $stmt->close();
}

// Cerrar la conexión
mysqli_close($conn);
?>
