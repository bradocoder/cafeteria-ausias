<?php
declare(strict_types=1);

$titulo = 'Productos - ' . APP_CONFIG['app']['name'];
$servidor = APP_CONFIG['app']['server_id'];

// Filtro por categoría (sanitizado)
$categoriaId = isset($_GET['cat']) ? (int) $_GET['cat'] : null;
if ($categoriaId !== null && $categoriaId <= 0) {
    $categoriaId = null;
}

try {
    $categorias = Categoria::listarActivas();
    $productos  = Producto::listar($categoriaId);
} catch (Throwable $e) {
    $categorias = [];
    $productos  = [];
    $errorBD = $e->getMessage();
}

require APP_CONFIG['paths']['templates'] . '/layout/header.php';
require APP_CONFIG['paths']['templates'] . '/productos.php';
require APP_CONFIG['paths']['templates'] . '/layout/footer.php';
