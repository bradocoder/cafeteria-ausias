<?php
/**
 * Plantilla de configuración del sistema.
 *
 * COPIAR este archivo a /etc/cafeteria/config.php
 * y rellenar con los valores reales del entorno.
 *
 *   sudo cp config.example.php /etc/cafeteria/config.php
 *   sudo chown root:www-data /etc/cafeteria/config.php
 *   sudo chmod 640 /etc/cafeteria/config.php
 */
return [
    'app' => [
        'name'      => 'Cafetería CIPFP Àusias March',
        'env'       => 'development',  // 'production' en despliegue
        'debug'     => true,            // false en producción
        'server_id' => gethostname(),   // identifica WEB1/WEB2 en footer
    ],
    'paths' => [
        'root'      => '/var/www/cafeteria',
        'storage'   => '/var/www/cafeteria/storage',
        'templates' => '/var/www/cafeteria/templates',
        'data'      => '/var/www/cafeteria/storage/data',
    ],
    'database' => [
        'host'     => '172.16.2.XX',     // IP de la BD en LAN
        'name'     => 'cafeteria',
        'user'     => 'webuser',
        'password' => 'CAMBIAR',         // NUNCA commitear con valor real
        'charset'  => 'utf8mb4',
    ],
    'session' => [
        'name'     => 'CAFE_SID',
        'lifetime' => 3600,
    ],
];
