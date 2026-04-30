<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

try {
    $producto = Producto::obtenerPorId($id);
} catch (Throwable $e) {
    $producto = null;
    $errorBD = $e->getMessage();
}

if (!isset($errorBD) && $producto === null) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

$titulo = ($producto['nombre'] ?? 'Producto') . ' - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/producto.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
