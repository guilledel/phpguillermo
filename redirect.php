<?php
// Este archivo es opcional, puedes usarlo para registrar quién hace clic en el botón
// antes de redirigir a la página vulnerable

// Registrar información del visitante
$fecha = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Desconocido';

$log = "Fecha: $fecha\nIP: $ip\nUser-Agent: $user_agent\nReferer: $referer\n\n";
file_put_contents('clicks.txt', $log, FILE_APPEND);

// Redirigir a la página vulnerable
header('Location: listado_productos.php');
exit;
?>