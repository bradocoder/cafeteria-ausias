<?php
declare(strict_types=1);

$titulo = 'Carrito - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];

// Si vienen IDs por POST (revalidación del carrito desde JS), los procesamos.
// Si no, la página se renderiza vacía y JS la rellena desde localStorage.
$productosCarrito = [];
$errorBD = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $input = json_decode(file_get_contents('php://input'), true);
    $ids = $input['ids'] ?? [];

    if (!is_array($ids)) {
        echo json_encode(['error' => 'Formato inválido']);
        exit;
    }

    try {
        $productos = Producto::obtenerPorIds($ids);
        echo json_encode(['productos' => $productos]);
    } catch (Throwable $e) {
        echo json_encode(['error' => 'Error al consultar productos']);
        error_log('[Carrito] ' . $e->getMessage());
    }
    exit;
}

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/carrito.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
