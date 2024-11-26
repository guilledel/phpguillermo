<?php
session_start();

// Suponiendo que ya se haya validado el usuario
$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $user_name;

// Redirigir al CRUD o a la página de bienvenida
header("Location: bienvenida.php"); // Página de bienvenida o al CRUD
exit;
?>
