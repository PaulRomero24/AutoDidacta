<?php
// Administración de noticias (solo admin).
//   GET                      -> lista todas (activas y ocultas)
//   POST                     -> crea (multipart: titulo, texto, activa, imagen1, imagen2)
//   POST ?id=N               -> edita (mismos campos; quitar_imagen1=1 / quitar_imagen2=1 para borrar una imagen)
//   PUT  ?id=N&accion=ocultar|mostrar
//   DELETE ?id=N             -> borra la noticia y sus imágenes
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/comun.php'; // CORS + OPTIONS
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');

const UPLOADS_DIR = __DIR__ . '/../uploads/';
const UPLOADS_URL = 'https://marisaberdu.com/uploads/';
const MAX_BYTES   = 5 * 1024 * 1024;
const MAX_TITULO  = 120;
const MAX_TEXTO   = 2000;

class ErrorUsuario extends Exception {}

function responder($datos, int $codigo = 200): void {
    http_response_code($codigo);
    echo json_encode($datos);
    exit;
}

// Guarda una imagen subida en el campo $campo. Devuelve su URL o null si no se envió.
function guardar_imagen(string $campo): ?string {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES[$campo];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        throw new ErrorUsuario('Error al subir la imagen');
    }
    if ($f['size'] > MAX_BYTES) {
        throw new ErrorUsuario('La imagen supera los 5 MB');
    }
    $info = @getimagesize($f['tmp_name']);
    $tipos = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($tipos[$info[2]])) {
        throw new ErrorUsuario('Formato no permitido (solo JPG, PNG o WEBP)');
    }
    $nombre = bin2hex(random_bytes(16)) . '.' . $tipos[$info[2]];
    if (!move_uploaded_file($f['tmp_name'], UPLOADS_DIR . $nombre)) {
        throw new ErrorUsuario('No se pudo guardar la imagen');
    }
    return UPLOADS_URL . $nombre;
}

// Borra un archivo de uploads/ a partir de su URL (solo si es de nuestra carpeta).
function borrar_archivo(?string $url): void {
    if ($url && str_starts_with($url, UPLOADS_URL)) {
        $ruta = UPLOADS_DIR . basename($url);
        if (is_file($ruta)) {
            @unlink($ruta);
        }
    }
}

function obtener(int $id): ?array {
    $st = db()->prepare('SELECT * FROM noticias WHERE id = ?');
    $st->execute([$id]);
    $fila = $st->fetch();
    return $fila ?: null;
}

function validar_campos(): array {
    $titulo = trim($_POST['titulo'] ?? '');
    $texto  = trim($_POST['texto'] ?? '');
    if ($titulo === '' || mb_strlen($titulo) > MAX_TITULO) {
        throw new ErrorUsuario('El título es obligatorio (máx. ' . MAX_TITULO . ' caracteres)');
    }
    if ($texto === '' || mb_strlen($texto) > MAX_TEXTO) {
        throw new ErrorUsuario('El texto es obligatorio (máx. ' . MAX_TEXTO . ' caracteres)');
    }
    return [$titulo, $texto];
}

exigir_admin();

$metodo = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    // ---------- LISTAR ----------
    if ($metodo === 'GET') {
        $st = db()->query('SELECT id, titulo, texto, imagen1, imagen2, activa, fecha
                           FROM noticias ORDER BY fecha DESC, id DESC');
        $filas = array_map(function ($n) {
            $n['activa'] = (int) $n['activa'];
            $n['fecha']  = str_replace(' ', 'T', $n['fecha']);
            return $n;
        }, $st->fetchAll());
        responder($filas);
    }

    // ---------- CREAR / EDITAR ----------
    if ($metodo === 'POST') {
        [$titulo, $texto] = validar_campos();
        $guardadas = []; // para limpiar si algo falla

        try {
            if ($id === 0) {
                // CREAR
                $img1 = guardar_imagen('imagen1');
                if ($img1) $guardadas[] = $img1;
                $img2 = guardar_imagen('imagen2');
                if ($img2) $guardadas[] = $img2;

                $activa = (($_POST['activa'] ?? '1') === '0') ? 0 : 1;
                $st = db()->prepare(
                    'INSERT INTO noticias (titulo, texto, imagen1, imagen2, activa) VALUES (?, ?, ?, ?, ?)'
                );
                $st->execute([$titulo, $texto, $img1, $img2, $activa]);
                responder(obtener((int) db()->lastInsertId()), 201);
            }

            // EDITAR
            $actual = obtener($id);
            if (!$actual) {
                responder(['detail' => 'Noticia no encontrada'], 404);
            }
            $imagenes = ['imagen1' => $actual['imagen1'], 'imagen2' => $actual['imagen2']];
            $a_borrar = [];

            foreach (['imagen1', 'imagen2'] as $campo) {
                $nueva = guardar_imagen($campo);
                if ($nueva) {
                    $guardadas[] = $nueva;
                    $a_borrar[] = $imagenes[$campo];
                    $imagenes[$campo] = $nueva;
                } elseif (($_POST['quitar_' . $campo] ?? '') === '1') {
                    $a_borrar[] = $imagenes[$campo];
                    $imagenes[$campo] = null;
                }
            }

            $activa = isset($_POST['activa']) ? (($_POST['activa'] === '0') ? 0 : 1) : (int) $actual['activa'];
            $st = db()->prepare(
                'UPDATE noticias SET titulo = ?, texto = ?, imagen1 = ?, imagen2 = ?, activa = ? WHERE id = ?'
            );
            $st->execute([$titulo, $texto, $imagenes['imagen1'], $imagenes['imagen2'], $activa, $id]);

            foreach ($a_borrar as $url) borrar_archivo($url); // recién ahora, con el UPDATE hecho
            responder(obtener($id));
        } catch (ErrorUsuario $e) {
            foreach ($guardadas as $url) borrar_archivo($url);
            throw $e;
        }
    }

    // ---------- OCULTAR / MOSTRAR ----------
    if ($metodo === 'PUT') {
        $accion = $_GET['accion'] ?? '';
        if ($id === 0 || !in_array($accion, ['ocultar', 'mostrar'], true)) {
            responder(['detail' => 'Parámetros inválidos'], 400);
        }
        $st = db()->prepare('UPDATE noticias SET activa = ? WHERE id = ?');
        $st->execute([$accion === 'mostrar' ? 1 : 0, $id]);
        responder(['ok' => true]);
    }

    // ---------- BORRAR ----------
    if ($metodo === 'DELETE') {
        $actual = $id ? obtener($id) : null;
        if (!$actual) {
            responder(['detail' => 'Noticia no encontrada'], 404);
        }
        db()->prepare('DELETE FROM noticias WHERE id = ?')->execute([$id]);
        borrar_archivo($actual['imagen1']);
        borrar_archivo($actual['imagen2']);
        responder(['ok' => true]);
    }

    responder(['detail' => 'Método no permitido'], 405);

} catch (ErrorUsuario $e) {
    responder(['detail' => $e->getMessage()], 400);
} catch (Throwable $e) {
    responder(['detail' => 'Error interno'], 500);
}