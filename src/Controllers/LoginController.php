<?php
declare(strict_types=1);

$titulo = 'Iniciar sesión - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
        $error = 'Token de seguridad inválido. Recarga la página.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Rellena ambos campos.';
        } else {
            $r = AuthService::login($username, $password);
            if ($r['ok']) {
                header('Location: /?r=home');
                exit;
            }
            $error = $r['error'];
        }
    }
}

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/auth/login.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
