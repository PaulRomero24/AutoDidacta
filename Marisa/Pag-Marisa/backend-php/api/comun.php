<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$permitidos = ['https://marisaberdu.com', 'https://www.marisaberdu.com', 'http://localhost:5173'];
$origen = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origen, $permitidos, true)) {
    header("Access-Control-Allow-Origin: $origen");
    header('Vary: Origin');
}
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function responder_error(int $codigo, string $detalle): never {
    http_response_code($codigo);
    echo json_encode(['detail' => $detalle]);
    exit;
}