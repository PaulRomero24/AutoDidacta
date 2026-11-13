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
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db.php';
$cfg = config();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents('php://input'), true);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username = $cfg['mail_user'];
    $mail->Password = $cfg['mail_pass'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('contacto@marisaberdu.com', 'Marisa Berdu Web');
    $mail->addAddress('contacto@marisaberdu.com');
    $mail->Subject = isset($data['asunto']) ? $data['asunto'] : 'Consulta desde marisaberdu.com';
    $mail->isHTML(true);
    $mail->Body = isset($data['cuerpo']) ? $data['cuerpo'] : 'Sin contenido';

    $mail->send();
    echo json_encode(['ok' => true, 'mensaje' => 'Email enviado correctamente']);
} catch (Exception $e) {
    error_log('Error de correo: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo enviar el mensaje']);
}
