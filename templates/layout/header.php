<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Cafetería CIPFP') ?></title>
    <meta name="description" content="Sistema de pedidos online de la cafetería del CIPFP Àusias March">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar__inner">
        <!-- IZQUIERDA: Marca -->
        <a class="brand" href="/?r=home">
            <span class="brand__icon">☕</span>
            <span class="brand__text">Cafetería <strong>Àusias March</strong></span>
        </a>

        <!-- Hamburguesa móvil -->
        <button class="nav-toggle" aria-label="Abrir menú"
                onclick="document.body.classList.toggle('nav-open')">
            <span></span><span></span><span></span>
        </button>

        <!-- CENTRO: Navegación principal -->
        <nav class="nav">
            <a href="/?r=home" class="nav__link">Inicio</a>
            <a href="/?r=productos" class="nav__link">Productos</a>
            <?php if (AuthService::logueado()): ?>
                <a href="/?r=mis-pedidos" class="nav__link">Mis pedidos</a>
            <?php endif; ?>
        </nav>

        <!-- DERECHA: Acciones -->
        <div class="nav-actions">
            <?php if (AuthService::logueado()): ?>
                <?php $u = AuthService::usuario(); ?>

                <?php if (AuthService::esAdmin()): ?>
                    <a href="/?r=admin-productos" class="nav-admin-btn">
                        <span class="nav-admin-btn__icon">⚙</span>
                        Panel admin
                    </a>
                <?php endif; ?>

                <!-- Avatar con dropdown -->
                <details class="nav-user">
                    <summary class="nav-user__trigger" aria-label="Menú de usuario">
                        <span class="nav-user__avatar"><?= strtoupper(substr($u['nombre'], 0, 1)) ?></span>
                        <span class="nav-user__chevron">▾</span>
                    </summary>
                    <div class="nav-user__menu">
                        <div class="nav-user__head">
                            <strong><?= htmlspecialchars($u['nombre']) ?></strong>
                            <small><?= htmlspecialchars($u['username']) ?></small>
                        </div>
                        <a href="/?r=mis-pedidos" class="nav-user__item">Mis pedidos</a>
                        <?php if (AuthService::esAdmin()): ?>
                            <a href="/?r=admin-productos" class="nav-user__item">Panel admin</a>
                        <?php endif; ?>
                        <hr class="nav-user__sep">
                        <a href="/?r=logout" class="nav-user__item nav-user__item--danger">Salir</a>
                    </div>
                </details>

            <?php else: ?>
                <a href="/?r=login" class="nav__link">Entrar</a>
                <a href="/?r=registro" class="nav-register">Registrarse</a>
            <?php endif; ?>

            <!-- Carrito (siempre visible) -->
            <a href="/?r=carrito" class="nav-cart" aria-label="Ver carrito">
                <svg class="nav-cart__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4h2l2.5 13.5a1 1 0 0 0 1 .8h9.6a1 1 0 0 0 1-.78L20.5 8H6.5"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9.5" cy="20.5" r="1.4" fill="currentColor"/>
                    <circle cx="17"   cy="20.5" r="1.4" fill="currentColor"/>
                </svg>
                <span class="nav-cart__badge" id="cart-count" hidden>0</span>
            </a>
        </div>
    </div>
</header>
<main class="main">