<?php
require __DIR__ . '/comun.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder_error(405, 'Método no permitido');
}

$nombre  = trim($_POST['nombre_autor'] ?? '');
$destino = trim($_POST['destino'] ?? '');
$f = $_FILES['foto'] ?? null;

if ($nombre === '' || mb_strlen($nombre) > 100 || $destino === '' || mb_strlen($destino) > 100) {
    responder_error(400, 'Datos inválidos');
}
if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
    responder_error(400, 'No se recibió la imagen');
}
if ($f['size'] > 5 * 1024 * 1024) {
    responder_error(400, 'La imagen no puede superar 5MB');
}

// Verifica el contenido real del archivo, sin fiarse de su extensión
$tipos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$info = @getimagesize($f['tmp_name']);
if (!$info || !isset($tipos[$info['mime']])) {
    responder_error(400, 'Solo se permiten imágenes JPG, PNG o WEBP');
}

// Nombre aleatorio: el que mandó el usuario no se usa nunca
$archivo = bin2hex(random_bytes(16)) . '.' . $tipos[$info['mime']];
$ruta = dirname(__DIR__) . '/uploads/' . $archivo;

if (!move_uploaded_file($f['tmp_name'], $ruta)) {
    error_log('No se pudo mover la imagen subida a ' . $ruta);
    responder_error(500, 'No se pudo guardar la imagen');
}

try {
    $st = db()->prepare('INSERT INTO fotos (nombre_autor, destino, url_foto) VALUES (?, ?, ?)');
    $st->execute([$nombre, $destino, 'https://marisaberdu.com/uploads/' . $archivo]);
} catch (Throwable $e) {
    unlink($ruta); // que no quede una imagen huérfana sin registro
    error_log('Error al guardar la foto: ' . $e->getMessage());
    responder_error(500, 'No se pudo registrar la foto');
}

echo json_encode(['mensaje' => 'Foto recibida, pendiente de aprobación', 'id' => (int) db()->lastInsertId()]);