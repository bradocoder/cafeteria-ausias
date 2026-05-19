<?php
declare(strict_types=1);

AuthService::requiereAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::verify($_POST['_csrf'] ?? null)) {
    $_SESSION['flash_error'] = 'Petición inválida.';
    header('Location: /?r=admin-pedidos');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$estado = $_POST['estado'] ?? '';

if ($id > 0) {
    try {
        Pedido::cambiarEstado($id, $estado);
        $_SESSION['flash_ok'] = "Pedido " . Format::numeroPedido($id) . " → " . $estado;
    } catch (Throwable $e) {
        $_SESSION['flash_error'] = 'No se pudo cambiar el estado: ' . $e->getMessage();
    }
}

header('Location: /?r=admin-pedidos');
exit;
