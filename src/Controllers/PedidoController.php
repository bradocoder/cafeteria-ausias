<?php
declare(strict_types=1);

AuthService::requiereLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

$user = AuthService::usuario();
$esAdmin = AuthService::esAdmin();

try {
    // Admin puede ver cualquier pedido; cliente solo los suyos
    $pedido = Pedido::obtener($id, $esAdmin ? null : (int)$user['id']);
} catch (Throwable $e) {
    error_log('[Ver pedido] ' . $e->getMessage());
    $pedido = null;
}

if ($pedido === null) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

$titulo = 'Pedido ' . Format::numeroPedido((int)$pedido['id']) . ' - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];

// Flag para distinguir si venimos de "acabo de confirmar"
$reciente = isset($_GET['nuevo']) && $_GET['nuevo'] === '1';

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/pedido-confirmado.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
