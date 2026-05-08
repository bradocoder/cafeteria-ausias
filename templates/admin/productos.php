<section class="section section--top">
    <div class="admin-head">
        <div>
            <h1 class="page-title">Gestión de productos</h1>
            <p class="page-lead">Administra el catálogo de la cafetería.</p>
        </div>
        <a href="/?r=admin-producto-form" class="btn btn--primary">+ Nuevo producto</a>
    </div>

    <?php if (!empty($flashOk)): ?>
        <div class="alert alert--ok"><?= htmlspecialchars($flashOk) ?></div>
    <?php endif; ?>
    <?php if (!empty($flashError)): ?>
        <div class="alert alert--error"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>

    <?php if (isset($errorBD)): ?>
        <div class="alert alert--error">
            <strong>Error de BD:</strong> <?= htmlspecialchars($errorBD) ?>
        </div>
    <?php elseif (empty($productos)): ?>
        <div class="alert">No hay productos registrados aún.</div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="admin-table__img">Img</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="admin-table__num">Precio</th>
                        <th class="admin-table__center">Destacado</th>
                        <th class="admin-table__center">Estado</th>
                        <th class="admin-table__actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr class="<?= !$p['activo'] ? 'is-inactive' : '' ?>">
                        <td>
                            <?php if ($p['tiene_imagen']): ?>
                                <img src="/?r=imagen&id=<?= (int)$p['id'] ?>"
                                     alt="" class="admin-thumb">
                            <?php else: ?>
                                <div class="admin-thumb admin-thumb--empty">—</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($p['nombre']) ?></strong>
                            <?php if (!empty($p['descripcion'])): ?>
                                <small class="admin-table__desc"><?= htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 60, '...')) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
                        <td class="admin-table__num"><?= number_format((float)$p['precio'], 2, ',', '.') ?> €</td>
                        <td class="admin-table__center">
                            <?= $p['destacado'] ? '★' : '' ?>
                        </td>
                        <td class="admin-table__center">
                            <?php if ($p['activo']): ?>
                                <span class="tag tag--ok">Activo</span>
                            <?php else: ?>
                                <span class="tag tag--off">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-table__actions">
                            <a href="/?r=admin-producto-form&id=<?= (int)$p['id'] ?>" class="btn btn--ghost btn--sm">Editar</a>
                            <form method="POST" action="/?r=admin-producto-toggle" style="display:inline;"
                                  onsubmit="return confirm('¿Cambiar el estado de este producto?');">
                                <?= Csrf::field() ?>
                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                <button type="submit" class="btn btn--ghost btn--sm">
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
</section>
