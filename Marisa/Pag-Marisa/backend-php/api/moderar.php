<?php
require __DIR__ . '/comun.php';
require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    responder_error(405, 'Método no permitido');
}
exigir_admin();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$estados = ['aprobar' => 'aprobada', 'rechazar' => 'rechazada'];
$accion = $_GET['accion'] ?? '';

if (!$id || !isset($estados[$accion])) {
    responder_error(400, 'Datos inválidos');
}

$st = db()->prepare('SELECT id FROM fotos WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) {
    responder_error(404, 'Foto no encontrada');
}

$st = db()->prepare('UPDATE fotos SET estado = ? WHERE id = ?');
$st->execute([$estados[$accion], $id]);

echo json_encode(['mensaje' => $accion === 'aprobar' ? 'Foto aprobada' : 'Foto rechazada']);