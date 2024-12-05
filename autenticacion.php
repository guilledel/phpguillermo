<?php
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Iniciar la sesión si no está activa
}
require_once 'db.php';  // Archivo de conexión a la base de datos

function verificar_autenticacion($role_required = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: iniciarsesion.php");
        exit;
    }

    if ($role_required && $_SESSION['role'] !== $role_required) {
        header("Location: acceso_denegado.php");
        exit;
    }
}
?>
