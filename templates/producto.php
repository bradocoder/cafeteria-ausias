<?php if (isset($errorBD)): ?>
    <div class="alert alert--error">
        <strong>No se pudo cargar el producto.</strong>
        <?php if (APP_CONFIG['app']['debug']): ?><pre><?= htmlspecialchars($errorBD) ?></pre><?php endif; ?>
    </div>
<?php else: ?>

<nav class="breadcrumb">
    <a href="/?r=home">Inicio</a>
    <span>/</span>
    <a href="/?r=productos">Carta</a>
    <span>/</span>
    <a href="/?r=productos&cat=<?= (int)$producto['categoria_id'] ?>">
        <?= htmlspecialchars($producto['categoria_nombre']) ?>
    </a>
</nav>

<article class="detail">
    <div class="detail__media">
        <img src="<?= htmlspecialchars($producto['imagen'] ?: $producto['imagen_thumb'] ?: '/assets/img/placeholder.svg') ?>"
             alt="<?= htmlspecialchars($producto['nombre']) ?>"
             onerror="this.src='/assets/img/placeholder.svg'">
        <?php if ((int)$producto['destacado'] === 1): ?>
            <span class="card__badge detail__badge">Destacado</span>
        <?php endif; ?>
    </div>

    <div class="detail__info">
        <span class="card__cat"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
        <h1 class="detail__title"><?= htmlspecialchars($producto['nombre']) ?></h1>

        <?php if (!empty($producto['descripcion'])): ?>
            <p class="detail__desc"><?= htmlspecialchars($producto['descripcion']) ?></p>
        <?php endif; ?>

        <div class="detail__price">
            <?= number_format((float)$producto['precio'], 2, ',', '.') ?> €
        </div>

        <div class="detail__actions">
            <div class="qty-input">
                <button type="button" class="qty-btn" data-qty="-1" aria-label="Restar">−</button>
                <input type="number" id="qty" value="1" min="1" max="20" inputmode="numeric">
                <button type="button" class="qty-btn" data-qty="1" aria-label="Sumar">+</button>
            </div>

            <button type="button"
                    class="btn btn--primary btn--lg"
                    data-add-to-cart
                    data-id="<?= (int)$producto['id'] ?>"
                    data-nombre="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES) ?>"
                    data-precio="<?= (float)$producto['precio'] ?>">
                Añadir al pedido
            </button>
        </div>

        <p class="detail__note">El pedido se prepara y se recoge en la barra. No es entrega a domicilio.</p>
    </div>
</article>

<?php endif; ?>
