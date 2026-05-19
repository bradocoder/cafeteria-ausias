<div class="admin-page">
    <div class="admin-page__head">
        <div>
            <h1 class="admin-page__title">Productos</h1>
            <p class="admin-page__lead">Gestiona el catálogo de la cafetería.</p>
        </div>
        <a href="/?r=admin-producto-form" class="admin-btn admin-btn--primary">+ Nuevo producto</a>
    </div>

    <?php if (!empty($flashOk)): ?>
        <div class="admin-alert admin-alert--ok"><?= htmlspecialchars($flashOk) ?></div>
    <?php endif; ?>
    <?php if (!empty($flashError)): ?>
        <div class="admin-alert admin-alert--error"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>

    <div class="admin-stats">
        <div class="admin-stat">
            <span class="admin-stat__label">Total productos</span>
            <strong class="admin-stat__value"><?= (int)$stats['total'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Activos</span>
            <strong class="admin-stat__value admin-stat__value--ok"><?= (int)$stats['activos'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Destacados</span>
            <strong class="admin-stat__value"><?= (int)$stats['destacados'] ?> <small>★</small></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Con imagen</span>
            <strong class="admin-stat__value"><?= (int)$stats['con_imagen'] ?> <small>/ <?= (int)$stats['total'] ?></small></strong>
        </div>
    </div>

    <?php if (isset($errorBD)): ?>
        <div class="admin-alert admin-alert--error">
            <strong>Error de BD:</strong> <?= htmlspecialchars($errorBD) ?>
        </div>
    <?php elseif (empty($productos)): ?>
        <div class="admin-alert">No hay productos registrados aún.</div>
    <?php else: ?>
        <div class="admin-card">
            <table class="admin-tbl">
                <thead>
                    <tr>
                        <th class="admin-tbl__img"></th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="admin-tbl__num">Precio</th>
                        <th class="admin-tbl__center">★</th>
                        <th class="admin-tbl__center">Estado</th>
                        <th class="admin-tbl__actions"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr class="<?= !$p['activo'] ? 'is-inactive' : '' ?>">
                        <td>
                            <?php if ($p['tiene_imagen']): ?>
                                <img src="/?r=imagen&id=<?= (int)$p['id'] ?>" alt="" class="admin-thumb">
                            <?php else: ?>
                                <div class="admin-thumb admin-thumb--empty">—</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong class="admin-tbl__name"><?= htmlspecialchars($p['nombre']) ?></strong>
                            <?php if (!empty($p['descripcion'])): ?>
                                <span class="admin-tbl__desc"><?= htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 55, '...')) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="admin-pill"><?= htmlspecialchars($p['categoria_nombre']) ?></span></td>
                        <td class="admin-tbl__num"><?= number_format((float)$p['precio'], 2, ',', '.') ?> €</td>
                        <td class="admin-tbl__center">
                            <?= $p['destacado'] ? '<span class="admin-star">★</span>' : '<span class="admin-mute">·</span>' ?>
                        </td>
                        <td class="admin-tbl__center">
                            <?php if ($p['activo']): ?>
                                <span class="admin-tag admin-tag--ok">Activo</span>
                            <?php else: ?>
                                <span class="admin-tag admin-tag--off">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-tbl__actions">
                            <a href="/?r=admin-producto-form&id=<?= (int)$p['id'] ?>" class="admin-btn admin-btn--sm admin-btn--ghost">Editar</a>
                            <form method="POST" action="/?r=admin-producto-toggle" style="display:inline;"
                                  onsubmit="return confirm('¿Cambiar el estado de este producto?');">
                                <?= Csrf::field() ?>
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button type="submit" class="admin-btn admin-btn--sm admin-btn--ghost-danger">
                                    <?= $p['activo'] ? 'Desactivar' : 'Activar' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
