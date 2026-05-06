<?php
declare(strict_types=1);

final class AuthService
{
    private const MAX_INTENTOS = 5;
    private const BLOQUEO_MINUTOS = 15;

    public static function login(string $username, string $password): array
    {
        $cliente = Cliente::porUsername($username);
        if ($cliente === null) {
            return ['ok' => false, 'error' => 'Usuario o contraseña incorrectos.'];
        }
        if (!$cliente['activo']) {
            return ['ok' => false, 'error' => 'Esta cuenta está deshabilitada.'];
        }
        if (self::estaBloqueado($cliente)) {
            return ['ok' => false, 'error' => 'Demasiados intentos fallidos. Espera unos minutos.'];
        }
        if (!password_verify($password, $cliente['password_hash'])) {
            Cliente::registrarLoginFallido((int) $cliente['id']);
            return ['ok' => false, 'error' => 'Usuario o contraseña incorrectos.'];
        }

        Cliente::registrarLoginOk((int) $cliente['id']);
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'       => (int) $cliente['id'],
            'nombre'   => $cliente['nombre'],
            'username' => $cliente['username'],
            'rol'      => $cliente['rol'],
        ];
        return ['ok' => true];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function usuario(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function logueado(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function esAdmin(): bool
    {
        return self::logueado() && $_SESSION['user']['rol'] === 'admin';
    }

    public static function requiereLogin(): void
    {
        if (!self::logueado()) {
            header('Location: /?r=login');
            exit;
        }
    }

    public static function requiereAdmin(): void
    {
        if (!self::esAdmin()) {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }
    }

    private static function estaBloqueado(array $cliente): bool
    {
        if ((int) $cliente['intentos_login'] < self::MAX_INTENTOS) return false;
        if (empty($cliente['ultimo_intento'])) return false;
        $tiempo = strtotime($cliente['ultimo_intento']);
        return (time() - $tiempo) < (self::BLOQUEO_MINUTOS * 60);
    }
}
