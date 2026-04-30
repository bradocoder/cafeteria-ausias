<?php
declare(strict_types=1);

$titulo = 'Inicio - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];

try {
    $destacados = Producto::destacados(4);
} catch (Throwable $e) {
    $destacados = [];
    $errorBD = $e->getMessage();
}

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/home.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
