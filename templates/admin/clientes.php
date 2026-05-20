<div class="admin-page">
    <div class="admin-page__head">
        <div>
            <h1 class="admin-page__title">Clientes</h1>
            <p class="admin-page__lead">Usuarios registrados en el sistema.</p>
        </div>
    </div>

    <div class="admin-stats">
        <div class="admin-stat">
            <span class="admin-stat__label">Total</span>
            <strong class="admin-stat__value"><?= (int)$stats['total'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Activos</span>
            <strong class="admin-stat__value admin-stat__value--ok"><?= (int)$stats['activos'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Admins</span>
            <strong class="admin-stat__value"><?= (int)$stats['admins'] ?></strong>
        </div>
        <div class="admin-stat">
            <span class="admin-stat__label">Nuevos (7 días)</span>
            <strong class="admin-stat__value" style="color:#60a5fa"><?= (int)$stats['nuevos_7d'] ?></strong>
        </div>
    </div>

    <?php if (isset($errorBD)): ?>
        <div class="admin-alert admin-alert--error"><?= htmlspecialchars($errorBD) ?></div>
    <?php elseif (empty($clientes)): ?>
        <div class="admin-alert">No hay clientes registrados todavía.</div>
    <?php else: ?>
        <div class="admin-card">
            <table class="admin-tbl">
                <thead>
                    <tr>
                        <th></th>
                        <th>Usuario</th>
                        <th>Curso/Grupo</th>
                        <th class="admin-tbl__center">Rol</th>
                        <th class="admin-tbl__center">Pedidos</th>
                        <th class="admin-tbl__num">Gastado</th>
                        <th>Registro</th>
                        <th>Última conexión</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $c): ?>
                    <tr class="<?= !$c['activo'] ? 'is-inactive' : '' ?>">
                        <td style="width:40px;">
                            <div class="admin-avatar"><?= strtoupper(substr($c['nombre'], 0, 1)) ?></div>
                        </td>
                        <td>
                            <strong class="admin-tbl__name"><?= htmlspecialchars($c['nombre']) ?></strong>
                            <span class="admin-tbl__desc"><?= htmlspecialchars($c['username']) ?></span>
                        </td>
                        <td>
                            <?php if (!empty($c['curso_grupo'])): ?>
                                <span class="admin-pill"><?= htmlspecialchars($c['curso_grupo']) ?></span>
                            <?php else: ?>
                                <span class="admin-mute">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-tbl__center">
                            <?php if ($c['rol'] === 'admin'): ?>
                                <span class="admin-tag admin-tag--admin">Admin</span>
                            <?php else: ?>
                                <span class="admin-tag admin-tag--off">Cliente</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-tbl__center">
                            <strong><?= (int)$c['total_pedidos'] ?></strong>
                        </td>
                        <td class="admin-tbl__num"><?= Format::precio((float)$c['total_gastado']) ?></td>
                        <td class="admin-tbl__small"><?= Format::fecha($c['created_at']) ?></td>
                        <td class="admin-tbl__small">
                            <?php if (!empty($c['ultimo_login'])): ?>
                                <?= Format::fecha($c['ultimo_login']) ?>
                            <?php else: ?>
                                <span class="admin-mute">Nunca</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>