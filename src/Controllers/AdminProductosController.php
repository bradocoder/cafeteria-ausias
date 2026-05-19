<?php
declare(strict_types=1);

AuthService::requiereAdmin();

$titulo = 'Admin · Productos - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];
$user = AuthService::usuario();

try {
    $productos = ProductoAdmin::listarTodos();
} catch (Throwable $e) {
    $productos = [];
    $errorBD = $e->getMessage();
}

// Stats
$stats = [
    'total'      => count($productos),
    'activos'    => count(array_filter($productos, fn($p) => $p['activo'])),
    'destacados' => count(array_filter($productos, fn($p) => $p['destacado'])),
    'con_imagen' => count(array_filter($productos, fn($p) => $p['tiene_imagen'])),
];

// Mensajes flash
$flashOk = $_SESSION['flash_ok'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_ok'], $_SESSION['flash_error']);

require APP_CONFIG['paths']['templates'] . '/layout/admin-header.php';
require APP_CONFIG['paths']['templates'] . '/admin/productos.php';
require APP_CONFIG['paths']['templates'] . '/layout/admin-footer.php';
