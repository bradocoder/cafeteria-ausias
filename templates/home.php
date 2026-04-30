<section class="hero">
    <div class="hero__content">
        <span class="hero__eyebrow">Pide y recoge sin esperas</span>
        <h1 class="hero__title">Tu pedido,<br><em>listo cuando llegues</em>.</h1>
        <p class="hero__lead">Sistema de pedidos online de la cafetería del instituto. Elige tus productos, paga en barra y recógelo sin pasar por la cola.</p>
        <a href="/?r=productos" class="btn btn--primary">Ver carta</a>
    </div>
</section>

<?php if (!empty($destacados)): ?>
<section class="section">
    <div class="section__head">
        <h2 class="section__title">Destacados de hoy</h2>
        <a href="/?r=productos" class="section__link">Ver todos →</a>
    </div>
    <div class="grid">
        <?php foreach ($destacados as $p): ?>
        <article class="card">
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
</section>
<?php elseif (isset($errorBD)): ?>
<div class="alert alert--error">
    <strong>No se pudo conectar con la base de datos.</strong>
    <?php if (APP_CONFIG['app']['debug']): ?>
        <pre><?= htmlspecialchars($errorBD) ?></pre>
    <?php endif; ?>
</div>
<?php endif; ?>
