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
        <a class="brand" href="/?r=home">
            <span class="brand__icon">☕</span>
            <span class="brand__text">Cafetería <strong>Àusias March</strong></span>
        </a>
        <button class="nav-toggle" aria-label="Abrir menú" onclick="document.body.classList.toggle('nav-open')">
            <span></span><span></span><span></span>
        </button>
        <nav class="nav">
            <a href="/?r=home">Inicio</a>
            <a href="/?r=productos">Productos</a>
            <a href="/?r=carrito" class="nav__cart">
    	       Carrito <span class="nav__cart-badge" id="cart-count" hidden>0</span>
	    </a>
        </nav>
    </div>
</header>
<main class="main">
