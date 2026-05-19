<div class="admin-page">
    <div class="admin-page__head">
        <div>
            <h1 class="admin-page__title">Pedidos</h1>
            <p class="admin-page__lead">Gestión y seguimiento de pedidos.</p>
        </div>
    </div>

    <?php if (!empty($flashOk)): ?><div class="admin-alert admin-alert--ok"><?= htmlspecialchars($flashOk) ?></div><?php endif; ?>
    <?php if (!empty($flashError)): ?><div class="admin-alert admin-alert--error"><?= htmlspecialchars($flashError) ?></div><?php endif; ?>

    <div class="admin-stats">
        <div class="admin-stat">
            <span class="admin-stat__label">Total</span>
            <strong class="admin-stat__value"><?= (int)$stats['total'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Pendientes</span>
            <strong class="admin-stat__value" style="color:#fbbf24"><?= (int)$stats['pendientes'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Preparando</span>
            <strong class="admin-stat__value" style="color:#60a5fa"><?= (int)$stats['preparando'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Listos</span>
            <strong class="admin-stat__value admin-stat__value--ok"><?= (int)$stats['listos'] ?></strong>
        </div>
    </div>

    <div class="admin-filters">
        <a href="/?r=admin-pedidos" class="admin-filter <?= empty($estadoFiltro) ? 'is-active' : '' ?>">Todos</a>
        <a href="/?r=admin-pedidos&estado=pendiente"  class="admin-filter <?= $estadoFiltro==='pendiente'  ? 'is-active' : '' ?>">Pendientes</a>
        <a href="/?r=admin-pedidos&estado=preparando" class="admin-filter <?= $estadoFiltro==='preparando' ? 'is-active' : '' ?>">Preparando</a>
        <a href="/?r=admin-pedidos&estado=listo"      class="admin-filter <?= $estadoFiltro==='listo'      ? 'is-active' : '' ?>">Listos</a>
        <a href="/?r=admin-pedidos&estado=entregado"  class="admin-filter <?= $estadoFiltro==='entregado'  ? 'is-active' : '' ?>">Entregados</a>
        <a href="/?r=admin-pedidos&estado=cancelado"  class="admin-filter <?= $estadoFiltro==='cancelado'  ? 'is-active' : '' ?>">Cancelados</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="admin-alert">No hay pedidos<?= $estadoFiltro ? ' con ese estado' : '' ?>.</div>
    <?php else: ?>
        <div class="admin-card">
            <table class="admin-tbl">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="admin-tbl__center">Líneas</th>
                        <th class="admin-tbl__num">Total</th>
                        <th class="admin-tbl__center">Estado</th>
                        <th class="admin-tbl__actions"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pedidos as $p): $e = Format::estadoPedido($p['estado']); ?>
                    <tr>
                        <td><strong><?= Format::numeroPedido((int)$p['id']) ?></strong></td>
                        <td><?= Format::fecha($p['fecha']) ?></td>
                        <td>
                            <strong class="admin-tbl__name"><?= htmlspecialchars($p['cliente_nombre']) ?></strong>
                            <span class="admin-tbl__desc"><?= htmlspecialchars($p['cliente_username']) ?></span>
                        </td>
                        <td class="admin-tbl__center"><?= (int)$p['num_lineas'] ?></td>
                        <td class="admin-tbl__num"><?= Format::precio((float)$p['total']) ?></td>
                        <td class="admin-tbl__center"><span class="estado-tag <?= $e['class'] ?>"><?= $e['label'] ?></span></td>
                        <td class="admin-tbl__actions">
                            <a href="/?r=pedido&id=<?= (int)$p['id'] ?>" class="admin-btn admin-btn--sm admin-btn--ghost">Ver</a>
                            <details class="admin-dropdown">
                                <summary class="admin-btn admin-btn--sm admin-btn--ghost">Estado ▾</summary>
                                <div class="admin-dropdown__menu">
                                    <?php foreach (['pendiente','preparando','listo','entregado','cancelado'] as $s): ?>
                                        <?php if ($s !== $p['estado']): ?>
                                            <form method="POST" action="/?r=admin-pedido-estado">
                                                <?= Csrf::field() ?>
                                                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                                <input type="hidden" name="estado" value="<?= $s ?>">
                                                <button type="submit"><?= Format::estadoPedido($s)['label'] ?></button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
