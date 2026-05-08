<section class="section section--top">
    <nav class="breadcrumb">
        <a href="/?r=admin-productos">← Volver a la lista</a>
    </nav>

    <h1 class="page-title">
        <?= $editando ? 'Editar producto' : 'Nuevo producto' ?>
    </h1>

    <?php if (isset($errorBD)): ?>
        <div class="alert alert--error"><?= htmlspecialchars($errorBD) ?></div>
    <?php endif; ?>

    <form method="POST" action="/?r=admin-producto-guardar" enctype="multipart/form-data" class="form admin-form">
        <?= Csrf::field() ?>
        <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">

        <div class="admin-form__grid">
            <div class="admin-form__col">
                <label class="form__field">
                    <span class="form__label">Nombre</span>
                    <input type="text" name="nombre" required maxlength="100"
                           value="<?= htmlspecialchars($producto['nombre']) ?>">
                </label>

                <label class="form__field">
                    <span class="form__label">Descripción</span>
                    <textarea name="descripcion" rows="4" maxlength="500"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                </label>

                <div class="form__row">
                    <label class="form__field" style="flex:1;">
                        <span class="form__label">Precio (€)</span>
                        <input type="number" name="precio" required min="0" max="999.99" step="0.01"
                               value="<?= htmlspecialchars((string)$producto['precio']) ?>">
                    </label>

                    <label class="form__field" style="flex:1;">
                        <span class="form__label">Categoría</span>
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

                <div class="form__row form__checks">
                    <label class="form__check">
                        <input type="checkbox" name="destacado" value="1"
                               <?= $producto['destacado'] ? 'checked' : '' ?>>
                        <span>Destacado en home</span>
                    </label>
                    <label class="form__check">
                        <input type="checkbox" name="activo" value="1"
                               <?= $producto['activo'] ? 'checked' : '' ?>>
                        <span>Activo (visible para clientes)</span>
                    </label>
                </div>
            </div>

            <aside class="admin-form__col admin-form__col--side">
                <div class="form__field">
                    <span class="form__label">Imagen</span>
                    <div class="admin-imgbox">
                        <?php if ($editando && $producto['tiene_imagen']): ?>
                            <img src="/?r=imagen&id=<?= (int)$producto['id'] ?>" alt="" class="admin-imgbox__preview">
                        <?php else: ?>
                            <div class="admin-imgbox__empty">Sin imagen</div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp">
                    <small class="form__hint">JPG, PNG o WebP. Máximo 3 MB. Se redimensiona y comprime automáticamente.</small>
                </div>
            </aside>
        </div>

        <div class="form__actions">
            <a href="/?r=admin-productos" class="btn btn--ghost">Cancelar</a>
            <button type="submit" class="btn btn--primary">
                <?= $editando ? 'Guardar cambios' : 'Crear producto' ?>
            </button>
        </div>
    </form>
</section>
