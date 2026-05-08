<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(404);
    exit;
}

try {
    $img = ProductoAdmin::obtenerImagen($id);
} catch (Throwable $e) {
    error_log('[Imagen] ' . $e->getMessage());
    http_response_code(500);
    exit;
}

if ($img === null) {
    // Redirigir al placeholder estático
    header('Location: /assets/img/placeholder.svg');
    exit;
}

// Cabeceras de caché: las imágenes raramente cambian
$etag = '"' . md5($img['contenido']) . '"';

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
    http_response_code(304);
    exit;
}

header('Content-Type: ' . $img['mime_type']);
header('Content-Length: ' . strlen($img['contenido']));
header('Cache-Control: public, max-age=3600');
header('ETag: ' . $etag);
echo $img['contenido'];
