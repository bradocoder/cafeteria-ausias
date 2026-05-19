<section class="section section--top">
    <h1 class="page-title">Mis pedidos</h1>
    <p class="page-lead">Hola <strong><?= htmlspecialchars($user['nombre']) ?></strong>, aquí tienes tu historial.</p>

    <?php if (isset($errorBD)): ?>
        <div class="alert alert--error">No se pudo cargar el historial.</div>
    <?php elseif (empty($pedidos)): ?>
        <div class="alert">
            Aún no has hecho ningún pedido. <a href="/?r=productos">Ver carta →</a>
        </div>
    <?php else: ?>
        <ul class="historial">
            <?php foreach ($pedidos as $p): $e = Format::estadoPedido($p['estado']); ?>
                <li class="historial__item">
                    <a href="/?r=pedido&id=<?= (int)$p['id'] ?>" class="historial__link">
                        <div class="historial__main">
                            <strong class="historial__num"><?= Format::numeroPedido((int)$p['id']) ?></strong>
                            <span class="historial__fecha"><?= Format::fecha($p['fecha']) ?></span>
                        </div>
                        <div class="historial__lineas"><?= (int)$p['num_lineas'] ?> <?= $p['num_lineas'] == 1 ? 'producto' : 'productos' ?></div>
                        <div class="historial__total"><?= Format::precio((float)$p['total']) ?></div>
                        <span class="estado-tag <?= $e['class'] ?>"><?= $e['label'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
