<?php
declare(strict_types=1);

AuthService::requiereLogin();

$titulo = 'Mis pedidos - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];
$user = AuthService::usuario();

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/mis-pedidos.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
