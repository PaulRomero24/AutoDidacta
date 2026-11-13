<?php
require __DIR__ . '/comun.php';
require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder_error(405, 'Método no permitido');
}

$cfg = config();
$usuario  = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$usuario_ok  = hash_equals($cfg['admin_user'], $usuario);
$password_ok = password_verify($password, $cfg['admin_hash']);

if (!$usuario_ok || !$password_ok) {
    usleep(500000); // frena un poco los intentos de adivinar la contraseña
    responder_error(401, 'Credenciales incorrectas');
}

echo json_encode(['access_token' => crear_token($usuario), 'token_type' => 'bearer']);