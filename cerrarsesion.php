<?php
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario al formulario de login
header('Location: iniciarsesion.html');
exit;
?>
