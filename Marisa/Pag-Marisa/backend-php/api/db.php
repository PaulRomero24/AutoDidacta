<?php
function config(): array {
    static $cfg = null;
    if ($cfg === null) {
        // sube 4 niveles desde public_html/api hasta tu carpeta personal
        $cfg = require dirname(__DIR__, 4) . '/marisa-config.php';
    }
    return $cfg;
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $c = config();
        $dsn = "mysql:host={$c['db_host']};dbname={$c['db_name']};charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, $c['db_user'], $c['db_pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['detail' => 'Error de base de datos']);
            exit;
        }
    }
    return $pdo;
}
