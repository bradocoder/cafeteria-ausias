<?php
declare(strict_types=1);

// Cargar configuración
$config = require '/etc/cafeteria/config.php';

// Constantes globales
define('APP_ROOT', $config['paths']['root']);
define('APP_CONFIG', $config);

// Manejo de errores según entorno
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $config['paths']['storage'] . '/logs/php_errors.log');
}

// Sesión (importante: ver nota sobre balanceador más abajo)
session_name($config['session']['name']);
session_start();

// Autoload manual: busca la clase en src/ y src/Models/
spl_autoload_register(function ($class) {
    $rutas = [
        APP_ROOT . '/src/' . $class . '.php',
        APP_ROOT . '/src/Models/' . $class . '.php',
        APP_ROOT . '/src/Controllers/' . $class . '.php',
        APP_ROOT . '/src/Services/' . $class . '.php',
        APP_ROOT . '/src/Helpers/' . $class . '.php',
    ];
    foreach ($rutas as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// Router muy simple basado en query string
$route = $_GET['r'] ?? 'home';

// Whitelist de rutas para evitar inclusión arbitraria

$rutas = [
    'home'      => 'Controllers/HomeController.php',
    'productos' => 'Controllers/ProductosController.php',
    'producto'  => 'Controllers/ProductoController.php',
    'carrito'   => 'Controllers/CarritoController.php',
    'pedido'    => 'Controllers/PedidoController.php',
];

if (!isset($rutas[$route])) {
    http_response_code(404);
    require APP_ROOT . '/templates/404.php';
    exit;
}

require APP_ROOT . '/src/' . $rutas[$route];
