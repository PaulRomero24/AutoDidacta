<?php
require __DIR__ . '/comun.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responder_error(405, 'Método no permitido');
}

$st = db()->query(
    "SELECT id, nombre_autor, destino, url_foto, estado,
            DATE_FORMAT(fecha, '%Y-%m-%dT%H:%i:%s') AS fecha
     FROM fotos WHERE estado = 'aprobada' ORDER BY id DESC"
);
echo json_encode($st->fetchAll());