<?php
declare(strict_types=1);

$titulo = 'Crear cuenta - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];
$errores = [];
$datos = ['nombre' => '', 'username' => '', 'curso_grupo' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
        $errores[] = 'Token de seguridad inválido.';
    } else {
        $datos['nombre']      = trim($_POST['nombre'] ?? '');
        $datos['username']    = trim($_POST['username'] ?? '');
        $datos['curso_grupo'] = trim($_POST['curso_grupo'] ?? '') ?: null;
        $pass1 = $_POST['password'] ?? '';
        $pass2 = $_POST['password2'] ?? '';

        if (strlen($datos['nombre']) < 2 || strlen($datos['nombre']) > 100) {
            $errores[] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }
        if (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $datos['username'])) {
            $errores[] = 'El usuario debe tener entre 3 y 50 caracteres (letras, números, . _ -).';
        }
        if (strlen($pass1) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($pass1 !== $pass2) {
            $errores[] = 'Las contraseñas no coinciden.';
        }
        if (empty($errores) && Cliente::existeUsername($datos['username'])) {
            $errores[] = 'Ese nombre de usuario ya está en uso.';
        }

        if (empty($errores)) {
            try {
                $id = Cliente::crear($datos['nombre'], $datos['username'], $pass1, $datos['curso_grupo']);
                AuthService::login($datos['username'], $pass1);
                header('Location: /?r=home');
                exit;
            } catch (Throwable $e) {
                error_log('[Registro] ' . $e->getMessage());
                $errores[] = 'No se pudo crear la cuenta. Inténtalo más tarde.';
            }
        }
    }
}

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/auth/registro.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
