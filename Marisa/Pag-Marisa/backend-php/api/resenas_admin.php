<?php
require __DIR__ . '/comun.php';
require __DIR__ . '/auth.php';
exigir_admin();

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $estado = ($_GET['estado'] ?? 'pendiente') === 'aprobada' ? 'aprobada' : 'pendiente';
    $st = db()->prepare(
        "SELECT id, nombre, puntuacion, comentario, estado,
                DATE_FORMAT(fecha, '%Y-%m-%dT%H:%i:%s') AS fecha
         FROM resenas WHERE estado = ? ORDER BY id DESC"
    );
    $st->execute([$estado]);
    echo json_encode($st->fetchAll());
    exit;
}

if (!in_array($metodo, ['PUT', 'DELETE'], true)) {
    responder_error(405, 'Método no permitido');
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    responder_error(400, 'Datos inválidos');
}

$st = db()->prepare('SELECT id FROM resenas WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) {
    responder_error(404, 'Reseña no encontrada');
}

if ($metodo === 'PUT') {
    $estados = ['aprobar' => 'aprobada', 'rechazar' => 'rechazada'];
    $accion = $_GET['accion'] ?? '';
    if (!isset($estados[$accion])) {
        responder_error(400, 'Acción inválida');
    }
    $st = db()->prepare('UPDATE resenas SET estado = ? WHERE id = ?');
    $st->execute([$estados[$accion], $id]);
    echo json_encode(['mensaje' => $accion === 'aprobar' ? 'Reseña aprobada' : 'Reseña rechazada']);
    exit;
}

$st = db()->prepare('DELETE FROM resenas WHERE id = ?');
$st->execute([$id]);
echo json_encode(['mensaje' => 'Reseña eliminada']);