<?php
declare(strict_types=1);

/**
 * Conexión PDO singleton a MariaDB.
 * Usa configuración centralizada (/etc/cafeteria/config.php).
 */
final class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $cfg = APP_CONFIG['database'];

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['name'],
                $cfg['charset']
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5,
            ];

            try {
                self::$instance = new PDO($dsn, $cfg['user'], $cfg['password'], $options);
            } catch (PDOException $e) {
                // En producción no expongas el mensaje real
                if (APP_CONFIG['app']['debug']) {
                    throw new RuntimeException('Error de conexión BD: ' . $e->getMessage());
                }
                error_log('[DB] ' . $e->getMessage());
                throw new RuntimeException('Servicio no disponible. Inténtalo más tarde.');
            }
        }

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
