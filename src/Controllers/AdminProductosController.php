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

// Mensajes flash
$flashOk = $_SESSION['flash_ok'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_ok'], $_SESSION['flash_error']);

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/admin/productos.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
