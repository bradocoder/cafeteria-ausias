<?php
declare(strict_types=1);

AuthService::requiereAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::verify($_POST['_csrf'] ?? null)) {
    $_SESSION['flash_error'] = 'Petición inválida.';
    header('Location: /?r=admin-productos');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    try {
        ProductoAdmin::toggleActivo($id);
        $_SESSION['flash_ok'] = 'Estado del producto actualizado.';
    } catch (Throwable $e) {
        $_SESSION['flash_error'] = 'No se pudo cambiar el estado.';
    }
}

header('Location: /?r=admin-productos');
exit;
