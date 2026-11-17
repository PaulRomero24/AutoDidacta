<?php
// GET público: devuelve las 3 noticias activas más recientes.
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/comun.php'; // CORS + respuesta a OPTIONS

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['detail' => 'Método no permitido']);
    exit;
}

try {
    $st = db()->query(
        'SELECT id, titulo, texto, imagen1, imagen2, fecha
         FROM noticias
         WHERE activa = 1
         ORDER BY fecha DESC, id DESC
         LIMIT 3'
    );

    $noticias = array_map(function ($n) {
        $n['fecha'] = str_replace(' ', 'T', $n['fecha']); // mismo formato ISO que el resto
        return $n;
    }, $st->fetchAll());

    echo json_encode($noticias);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['detail' => 'Error al cargar las noticias']);
}