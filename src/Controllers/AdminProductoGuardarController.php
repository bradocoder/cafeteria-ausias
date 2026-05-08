<?php
declare(strict_types=1);

AuthService::requiereAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /?r=admin-productos');
    exit;
}

if (!Csrf::verify($_POST['_csrf'] ?? null)) {
    $_SESSION['flash_error'] = 'Token de seguridad inválido. Recarga e inténtalo de nuevo.';
    header('Location: /?r=admin-productos');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$editando = $id > 0;

// --- Validar datos ---
$errores = [];

$nombre = trim($_POST['nombre'] ?? '');
if (strlen($nombre) < 2 || strlen($nombre) > 100) {
    $errores[] = 'El nombre debe tener entre 2 y 100 caracteres.';
}

$descripcion = trim($_POST['descripcion'] ?? '');

$precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
if ($precio === false || $precio < 0 || $precio > 999.99) {
    $errores[] = 'El precio debe ser un número entre 0 y 999.99.';
}

$categoriaId = (int) ($_POST['categoria_id'] ?? 0);
if ($categoriaId <= 0) {
    $errores[] = 'Debes seleccionar una categoría.';
}

$destacado = isset($_POST['destacado']) ? 1 : 0;
$activo    = isset($_POST['activo']) ? 1 : 0;

if (!empty($errores)) {
    $_SESSION['flash_error'] = implode(' ', $errores);
    header('Location: /?r=admin-producto-form' . ($editando ? '&id=' . $id : ''));
    exit;
}

$datos = compact('nombre', 'descripcion', 'precio', 'categoriaId', 'destacado', 'activo');
// Renombrar la clave para que coincida con el modelo
$datos['categoria_id'] = $categoriaId;
unset($datos['categoriaId']);

// --- Guardar ---
try {
    if ($editando) {
        ProductoAdmin::actualizar($id, $datos);
        $productoId = $id;
    } else {
        $productoId = ProductoAdmin::crear($datos);
    }

    // --- Imagen (si se ha subido una nueva) ---
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            $img = ImageHelper::procesarSubida($_FILES['imagen']);
            ProductoAdmin::guardarImagen($productoId, $img['contenido'], $img['mime']);
        } catch (RuntimeException $e) {
            $_SESSION['flash_error'] = 'Producto guardado, pero la imagen falló: ' . $e->getMessage();
            header('Location: /?r=admin-producto-form&id=' . $productoId);
            exit;
        }
    }

    $_SESSION['flash_ok'] = $editando
        ? 'Producto actualizado correctamente.'
        : 'Producto creado correctamente.';

    header('Location: /?r=admin-productos');
    exit;

} catch (Throwable $e) {
    error_log('[Admin guardar] ' . $e->getMessage());
    $_SESSION['flash_error'] = 'No se pudo guardar el producto.';
    header('Location: /?r=admin-productos');
    exit;
}
