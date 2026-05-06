<?php
declare(strict_types=1);

$config = require '/etc/cafeteria/config.php';
define('APP_ROOT', $config['paths']['root']);
define('APP_CONFIG', $config);

if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $config['paths']['storage'] . '/logs/php_errors.log');
}

// Autoload (todas las subcarpetas relevantes de src/)
spl_autoload_register(function ($class) {
    $rutas = [
        APP_ROOT . '/src/' . $class . '.php',
        APP_ROOT . '/src/Models/' . $class . '.php',
        APP_ROOT . '/src/Controllers/' . $class . '.php',
        APP_ROOT . '/src/Services/' . $class . '.php',
        APP_ROOT . '/src/Helpers/' . $class . '.php',
    ];
    foreach ($rutas as $f) {
        if (is_file($f)) { require_once $f; return; }
    }
});

// Sesión en BD (necesario para balanceador)
session_set_save_handler(new DbSessionHandler(), true);
session_name($config['session']['name']);
session_set_cookie_params([
    'lifetime' => $config['session']['lifetime'],
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

$rutas = [
    'home'        => 'Controllers/HomeController.php',
    'productos'   => 'Controllers/ProductosController.php',
    'producto'    => 'Controllers/ProductoController.php',
    'carrito'     => 'Controllers/CarritoController.php',
    'login'       => 'Controllers/LoginController.php',
    'registro'    => 'Controllers/RegistroController.php',
    'logout'      => 'Controllers/LogoutController.php',
    'mis-pedidos' => 'Controllers/MisPedidosController.php',
];

$route = $_GET['r'] ?? 'home';
if (!isset($rutas[$route])) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

require APP_ROOT . '/src/' . $rutas[$route];
