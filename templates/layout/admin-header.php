<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Admin') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-theme">

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="admin-sidebar__brand">
            <span class="admin-sidebar__icon">☕</span>
            <div>
                <strong>Cafetería</strong>
                <small>Panel admin</small>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="/?r=admin-productos"
               class="admin-nav__link <?= in_array($_GET['r'] ?? '', ['admin-productos', 'admin-producto-form'], true) ? 'is-active' : '' ?>">
                <span class="admin-nav__dot"></span>
                Productos
            </a>
            <a href="/?r=admin-pedidos"
  		 class="admin-nav__link <?= ($_GET['r'] ?? '') === 'admin-pedidos' ? 'is-active' : '' ?>">
    		<span class="admin-nav__dot"></span>
    		Pedidos
		</a>
<a href="/?r=admin-clientes"
   class="admin-nav__link <?= ($_GET['r'] ?? '') === 'admin-clientes' ? 'is-active' : '' ?>">
    <span class="admin-nav__dot"></span>
    Clientes
</a>
        </nav>

        <div class="admin-sidebar__foot">
            <a href="/?r=home" class="admin-sidebar__back">← Volver al sitio</a>
        </div>
    </aside>

    <main class="admin-main">

        <header class="admin-topbar">
            <button class="admin-burger" onclick="document.body.classList.toggle('admin-nav-open')" aria-label="Menú">☰</button>
            <div class="admin-topbar__right">
                <?php $u = AuthService::usuario(); ?>
                <span class="admin-user">
                    <span class="admin-user__avatar"><?= strtoupper(substr($u['nombre'], 0, 1)) ?></span>
                    <span class="admin-user__info">
                        <strong><?= htmlspecialchars($u['nombre']) ?></strong>
                        <small>Administrador</small>
                    </span>
                </span>
                <a href="/?r=logout" class="admin-logout">Salir</a>
            </div>
        </header>

        <div class="admin-content">
