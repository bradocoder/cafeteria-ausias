<?php
declare(strict_types=1);

AuthService::requiereAdmin();

$titulo = 'Admin · Clientes - ' . APP_CONFIG['app']['name'];

try {
    $clientes = Cliente::listarTodos();
    $stats    = Cliente::stats();
} catch (Throwable $e) {
    $clientes = [];
    $stats    = ['total' => 0, 'activos' => 0, 'admins' => 0, 'nuevos_7d' => 0];
    $errorBD  = $e->getMessage();
}

require APP_CONFIG['paths']['templates'] . '/layout/admin-header.php';
require APP_CONFIG['paths']['templates'] . '/admin/clientes.php';
require APP_CONFIG['paths']['templates'] . '/layout/admin-footer.php';