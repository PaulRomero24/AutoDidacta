<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function crear_token(string $usuario): string {
    $ahora = time();
    return JWT::encode(
        ['sub' => $usuario, 'iat' => $ahora, 'exp' => $ahora + 24 * 3600],
        config()['jwt_secret'],
        'HS256'
    );
}

function exigir_admin(): string {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/^Bearer\s+(.+)$/i', $header, $m)) {
        responder_error(401, 'Token requerido');
    }
    try {
        $datos = JWT::decode($m[1], new Key(config()['jwt_secret'], 'HS256'));
        return $datos->sub;
    } catch (Throwable $e) {
        responder_error(401, 'Token inválido o expirado');
    }
}