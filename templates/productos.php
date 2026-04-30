<section class="section section--top">
    <h1 class="page-title">Carta</h1>
    <p class="page-lead">Selecciona una categoría para filtrar.</p>

    <?php if (!empty($categorias)): ?>
    <nav class="filters">
        <a href="/?r=productos" class="filter <?= $categoriaId === null ? 'is-active' : '' ?>">Todos</a>
        <?php foreach ($categorias as $c): ?>
            <a href="/?r=productos&cat=<?= (int)$c['id'] ?>"
               class="filter <?= $categoriaId === (int)$c['id'] ? 'is-active' : '' ?>">
                <?= htmlspecialchars($c['nombre']) ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <?php if (isset($errorBD)): ?>
        <div class="alert alert--error">
            <strong>No se pudo conectar con la base de datos.</strong>
            <?php if (APP_CONFIG['app']['debug']): ?><pre><?= htmlspecialchars($errorBD) ?></pre><?php endif; ?>
        </div>
    <?php elseif (empty($productos)): ?>
        <div class="alert">No hay productos disponibles en esta categoría.</div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($productos as $p): ?>
           <article class="card">
    <?php if ((int)$p['destacado'] === 1): ?>
        <span class="card__badge">Destacado</span>
    <?php endif; ?>
    <a class="card__link" href="/?r=producto&id=<?= (int)$p['id'] ?>">
        <div class="card__media">
            <img src="<?= htmlspecialchars($p['imagen_thumb'] ?: '/assets/img/placeholder.svg') ?>"
                 alt="<?= htmlspecialchars($p['nombre']) ?>"
                 onerror="this.src='/assets/img/placeholder.svg'"
                 loading="lazy">
        </div>
        <div class="card__body">
            <span class="card__cat"><?= htmlspecialchars($p['categoria_nombre']) ?></span>
            <h3 class="card__title"><?= htmlspecialchars($p['nombre']) ?></h3>
            <?php if (!empty($p['descripcion'])): ?>
                <p class="card__desc"><?= htmlspecialchars($p['descripcion']) ?></p>
            <?php endif; ?>
        </div>
    </a>
    <div class="card__foot">
        <span class="card__price"><?= number_format((float)$p['precio'], 2, ',', '.') ?> €</span>
        <button class="btn btn--ghost btn--add"
                data-add-to-cart
                data-id="<?= (int)$p['id'] ?>"
                data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                data-precio="<?= (float)$p['precio'] ?>">
            + Añadir
        </button>
    </div>
</article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
