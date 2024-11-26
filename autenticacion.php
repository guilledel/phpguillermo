<?php
// auth.php

function verificar_autenticacion() {
    // Iniciar sesión
    session_start();
    
    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        // Si no está autenticado, redirigir al formulario de inicio de sesión
        header("Location: iniciarsesion.php");
        exit; // Detener la ejecución del código
    }
}
?>
