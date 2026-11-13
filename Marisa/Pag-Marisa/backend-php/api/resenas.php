<?php
require __DIR__ . '/comun.php';
require_once __DIR__ . '/db.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $st = db()->query(
        "SELECT id, nombre, puntuacion, comentario,
                DATE_FORMAT(fecha, '%Y-%m-%dT%H:%i:%s') AS fecha
         FROM resenas WHERE estado = 'aprobada' ORDER BY id DESC LIMIT 50"
    );
    echo json_encode($st->fetchAll());
    exit;
}

if ($metodo !== 'POST') {
    responder_error(405, 'Método no permitido');
}

$datos = json_decode(file_get_contents('php://input'), true);
if (!is_array($datos)) {
    responder_error(400, 'Datos inválidos');
}

// Campo trampa: los humanos no lo ven, los bots lo rellenan
if (!empty($datos['sitio_web'])) {
    echo json_encode(['mensaje' => 'Reseña recibida, pendiente de aprobación']);
    exit;
}

$nombre     = is_string($datos['nombre'] ?? null) ? trim($datos['nombre']) : '';
$comentario = is_string($datos['comentario'] ?? null) ? trim($datos['comentario']) : '';
$puntuacion = filter_var($datos['puntuacion'] ?? null, FILTER_VALIDATE_INT,
                         ['options' => ['min_range' => 1, 'max_range' => 5]]);

if ($nombre === '' || mb_strlen($nombre) > 100) {
    responder_error(400, 'Ingresá tu nombre (hasta 100 caracteres)');
}
if ($puntuacion === false) {
    responder_error(400, 'La puntuación debe ser de 1 a 5');
}
if (mb_strlen($comentario) < 10 || mb_strlen($comentario) > 1000) {
    responder_error(400, 'El comentario debe tener entre 10 y 1000 caracteres');
}

// Tope de pendientes: si alguien intenta inundar la cola, se frena
$pendientes = (int) db()->query("SELECT COUNT(*) FROM resenas WHERE estado = 'pendiente'")->fetchColumn();
if ($pendientes >= 200) {
    responder_error(429, 'Hay muchas reseñas en revisión. Intentá de nuevo más tarde.');
}

$st = db()->prepare('INSERT INTO resenas (nombre, puntuacion, comentario) VALUES (?, ?, ?)');
$st->execute([$nombre, $puntuacion, $comentario]);

echo json_encode(['mensaje' => 'Reseña recibida, pendiente de aprobación']);

