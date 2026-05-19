<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

if (!AuthService::logueado()) {
    http_response_code(401);
    echo json_encode(['error' => 'Debes iniciar sesión para confirmar el pedido.', 'redirect' => '/?r=login']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!Csrf::verify($input['_csrf'] ?? null)) {
    http_response_code(403);
    echo json_encode(['error' => 'Token de seguridad inválido.']);
    exit;
}

$items = $input['items'] ?? [];
$observaciones = trim((string)($input['observaciones'] ?? ''));

if (!is_array($items) || empty($items)) {
    http_response_code(400);
    echo json_encode(['error' => 'El carrito está vacío.']);
    exit;
}

// Limitar observaciones
if (mb_strlen($observaciones) > 500) {
    $observaciones = mb_substr($observaciones, 0, 500);
}

try {
    $user = AuthService::usuario();
    $pedidoId = Pedido::crear(
        (int)$user['id'],
        $items,
        $observaciones !== '' ? $observaciones : null
    );

    echo json_encode([
        'ok'        => true,
        'pedido_id' => $pedidoId,
        'redirect'  => '/?r=pedido&id=' . $pedidoId,
    ]);
    exit;

} catch (RuntimeException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} catch (Throwable $e) {
    error_log('[Pedido confirmar] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear el pedido. Inténtalo de nuevo.']);
    exit;
}
