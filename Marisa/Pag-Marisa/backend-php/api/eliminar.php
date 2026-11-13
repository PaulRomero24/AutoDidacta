<?php
require __DIR__ . '/comun.php';
require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    responder_error(405, 'Método no permitido');
}
exigir_admin();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    responder_error(400, 'Datos inválidos');
}

$st = db()->prepare('SELECT url_foto FROM fotos WHERE id = ?');
$st->execute([$id]);
$foto = $st->fetch();
if (!$foto) {
    responder_error(404, 'Foto no encontrada');
}

$st = db()->prepare('DELETE FROM fotos WHERE id = ?');
$st->execute([$id]);

// Si la imagen está en nuestro servidor, borra también el archivo
$prefijo = 'https://marisaberdu.com/uploads/';
if (str_starts_with($foto['url_foto'], $prefijo)) {
    $nombre = substr($foto['url_foto'], strlen($prefijo));
    if (preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $nombre)) {
        @unlink(dirname(__DIR__) . '/uploads/' . $nombre);
    }
}

echo json_encode(['mensaje' => 'Foto eliminada']);
