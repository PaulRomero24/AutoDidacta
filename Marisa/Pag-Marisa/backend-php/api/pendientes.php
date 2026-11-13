<?php
require __DIR__ . '/comun.php';
require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responder_error(405, 'Método no permitido');
}
exigir_admin();

$st = db()->query(
    "SELECT id, nombre_autor, destino, url_foto, estado,
            DATE_FORMAT(fecha, '%Y-%m-%dT%H:%i:%s') AS fecha
     FROM fotos WHERE estado = 'pendiente' ORDER BY id ASC"
);
echo json_encode($st->fetchAll());