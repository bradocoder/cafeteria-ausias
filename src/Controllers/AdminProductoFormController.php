<?php
declare(strict_types=1);

AuthService::requiereAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$editando = $id > 0;

if ($editando) {
    $producto = ProductoAdmin::obtener($id);
    if ($producto === null) {
        $_SESSION['flash_error'] = 'El producto solicitado no existe.';
        header('Location: /?r=admin-productos');
        exit;
    }
    $titulo = 'Editar producto - ' . APP_CONFIG['app']['name'];
} else {
    $producto = [
        'id' => 0, 'nombre' => '', 'descripcion' => '', 'precio' => '',
        'categoria_id' => 0, 'destacado' => 0, 'activo' => 1, 'tiene_imagen' => 0,
    ];
    $titulo = 'Nuevo producto - ' . APP_CONFIG['app']['name'];
}

$servidor = APP_CONFIG['app']['server_id'];
$user = AuthService::usuario();

try {
    $categorias = Categoria::listarActivas();
} catch (Throwable $e) {
    $categorias = [];
    $errorBD = $e->getMessage();
}

require APP_CONFIG['paths']['templates'] . '/layout/admin-header.php';
require APP_CONFIG['paths']['templates'] . '/admin/producto-form.php';
require APP_CONFIG['paths']['templates'] . '/layout/admin-footer.php';
