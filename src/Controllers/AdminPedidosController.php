<?php
declare(strict_types=1);

AuthService::requiereAdmin();

$titulo = 'Admin · Pedidos - ' . APP_CONFIG['app']['name'];
$estadoFiltro = $_GET['estado'] ?? null;

try {
    $pedidos = Pedido::listarTodos($estadoFiltro);

    $stats = [
        'total'      => count($pedidos),
        'pendientes' => count(array_filter($pedidos, fn($p) => $p['estado'] === 'pendiente')),
        'preparando' => count(array_filter($pedidos, fn($p) => $p['estado'] === 'preparando')),
        'listos'     => count(array_filter($pedidos, fn($p) => $p['estado'] === 'listo')),
    ];
} catch (Throwable $e) {
    $pedidos = [];
    $stats = ['total' => 0, 'pendientes' => 0, 'preparando' => 0, 'listos' => 0];
    $errorBD = $e->getMessage();
}

$flashOk = $_SESSION['flash_ok'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_ok'], $_SESSION['flash_error']);

require APP_CONFIG['paths']['templates'] . '/layout/admin-header.php';
require APP_CONFIG['paths']['templates'] . '/admin/pedidos.php';
require APP_CONFIG['paths']['templates'] . '/layout/admin-footer.php';
