<div class="admin-page">
    <nav class="admin-breadcrumb">
        <a href="/?r=admin-productos">← Productos</a>
        <span>/</span>
        <span><?= $editando ? 'Editar' : 'Nuevo' ?></span>
    </nav>

    <h1 class="admin-page__title">
        <?= $editando ? htmlspecialchars($producto['nombre']) : 'Nuevo producto' ?>
    </h1>

    <?php if (isset($errorBD)): ?>
        <div class="admin-alert admin-alert--error"><?= htmlspecialchars($errorBD) ?></div>
    <?php endif; ?>

    <form method="POST" action="/?r=admin-producto-guardar" enctype="multipart/form-data" class="admin-form">
        <?= Csrf::field() ?>
        <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">

        <div class="admin-form__grid">
            <div class="admin-card admin-form__main">
                <label class="admin-field">
                    <span class="admin-field__label">Nombre</span>
                    <input type="text" name="nombre" required maxlength="100"
                           value="<?= htmlspecialchars($producto['nombre']) ?>">
                </label>

                <label class="admin-field">
                    <span class="admin-field__label">Descripción</span>
                    <textarea name="descripcion" rows="4" maxlength="500"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                </label>

                <div class="admin-field__row">
                    <label class="admin-field" style="flex:1;">
                        <span class="admin-field__label">Precio (€)</span>
                        <input type="number" name="precio" required min="0" max="999.99" step="0.01"
                               value="<?= htmlspecialchars((string)$producto['precio']) ?>">
                    </label>

                    <label class="admin-field" style="flex:1;">
                        <span class="admin-field__label">Categoría</span>
                        <select name="categoria_id" required>
                            <option value="">—</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= (int)$c['id'] ?>"
                                    <?= (int)$producto['categoria_id'] === (int)$c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <div class="admin-checks">
                    <label class="admin-check">
                        <input type="checkbox" name="destacado" value="1"
                               <?= $producto['destacado'] ? 'checked' : '' ?>>
                        <span>Destacar en home</span>
                    </label>
                    <label class="admin-check">
                        <input type="checkbox" name="activo" value="1"
                               <?= $producto['activo'] ? 'checked' : '' ?>>
                        <span>Activo (visible para clientes)</span>
                    </label>
                </div>
            </div>

            <aside class="admin-card admin-form__side">
                <span class="admin-field__label">Imagen</span>
                <div class="admin-imgbox">
                    <?php if ($editando && $producto['tiene_imagen']): ?>
                        <img src="/?r=imagen&id=<?= (int)$producto['id'] ?>" alt="" class="admin-imgbox__preview">
                    <?php else: ?>
                        <div class="admin-imgbox__empty">Sin imagen</div>
                    <?php endif; ?>
                </div>
                <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp">
                <small class="admin-field__hint">JPG, PNG o WebP. Máx 3 MB. Se redimensiona y comprime automáticamente.</small>
            </aside>
        </div>

        <div class="admin-form__actions">
            <a href="/?r=admin-productos" class="admin-btn admin-btn--ghost">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">
                <?= $editando ? 'Guardar cambios' : 'Crear producto' ?>
            </button>
        </div>
    </form>
</div>
