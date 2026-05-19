<section class="section section--top">
    <?php if ($reciente): ?>
        <div class="confirm-banner">
            <div class="confirm-banner__icon">✓</div>
            <div>
                <h2 class="confirm-banner__title">Pedido confirmado</h2>
                <p class="confirm-banner__lead">Te avisaremos cuando esté listo para recoger.</p>
            </div>
        </div>
    <?php else: ?>
        <nav class="breadcrumb">
            <a href="/?r=mis-pedidos">← Volver a mis pedidos</a>
        </nav>
    <?php endif; ?>

    <?php $estado = Format::estadoPedido($pedido['estado']); ?>

    <div class="pedido-card">
        <header class="pedido-card__head">
            <div>
                <span class="pedido-card__label">Pedido</span>
                <h1 class="pedido-card__num"><?= Format::numeroPedido((int)$pedido['id']) ?></h1>
                <p class="pedido-card__meta">
                    <?= Format::fecha($pedido['fecha']) ?>
                    <?php if ($esAdmin ?? false): ?>
                        · <strong><?= htmlspecialchars($pedido['cliente_nombre']) ?></strong> (<?= htmlspecialchars($pedido['cliente_username']) ?>)
                        <?php if (!empty($pedido['curso_grupo'])): ?> · <?= htmlspecialchars($pedido['curso_grupo']) ?><?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>
            <span class="estado-tag <?= $estado['class'] ?>"><?= $estado['label'] ?></span>
        </header>

        <ul class="pedido-lineas">
            <?php foreach ($pedido['lineas'] as $l): ?>
                <li class="pedido-linea">
                    <div class="pedido-linea__qty"><?= (int)$l['cantidad'] ?>×</div>
                    <div class="pedido-linea__name"><?= htmlspecialchars($l['producto_nombre']) ?></div>
                    <div class="pedido-linea__unit"><?= Format::precio((float)$l['precio_unitario']) ?></div>
                    <div class="pedido-linea__sub"><?= Format::precio((float)$l['subtotal']) ?></div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!empty($pedido['observaciones'])): ?>
            <div class="pedido-obs">
                <strong>Observaciones:</strong>
                <p><?= nl2br(htmlspecialchars($pedido['observaciones'])) ?></p>
            </div>
        <?php endif; ?>

        <footer class="pedido-card__foot">
            <span>Total</span>
            <strong><?= Format::precio((float)$pedido['total']) ?></strong>
        </footer>
    </div>

    <p class="pedido-help">
        <?php if ($reciente): ?>
            Recoge tu pedido en la barra y paga en efectivo o tarjeta al retirarlo.
        <?php endif; ?>
        Puedes consultar todos tus pedidos en <a href="/?r=mis-pedidos">Mis pedidos</a>.
    </p>
</section>
