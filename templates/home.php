<section class="hero">
    <div class="hero__content">
        <span class="hero__eyebrow">Pide y recoge sin esperas</span>
        <h1 class="hero__title">Tu pedido,<br><em>listo cuando llegues</em>.</h1>
        <p class="hero__lead">Sistema de pedidos online de la cafetería del instituto. Elige tus productos, paga en barra y recógelo sin pasar por la cola.</p>
        <a href="/?r=productos" class="btn btn--primary">Ver carta</a>
    </div>
    <div class="hero__art" aria-hidden="true">
        <svg viewBox="0 0 320 280" xmlns="http://www.w3.org/2000/svg">
            <!-- Plato -->
            <ellipse cx="160" cy="230" rx="115" ry="14" fill="#a8132c" opacity="0.08"/>
            <!-- Taza -->
            <g transform="translate(80,90)">
                <path d="M 0,20 Q 0,0 20,0 L 130,0 Q 150,0 150,20 L 150,90 Q 150,130 110,130 L 40,130 Q 0,130 0,90 Z"
                      fill="#fff" stroke="#a8132c" stroke-width="3.5"/>
                <path d="M 0,30 L 0,75 L 5,77 L 5,30 Z M 150,30 Q 175,30 175,55 Q 175,80 150,80 L 150,75 Q 168,75 168,55 Q 168,37 150,37 Z"
                      fill="#a8132c"/>
                <ellipse cx="75" cy="22" rx="65" ry="8" fill="#7a3a2a"/>
                <ellipse cx="75" cy="22" rx="50" ry="5" fill="#5c2a1f" opacity="0.6"/>
                <!-- Espuma latte art -->
                <path d="M 50,18 Q 75,12 100,18 M 60,24 Q 75,20 90,24" stroke="#f5e6d3" stroke-width="2" fill="none" stroke-linecap="round"/>
            </g>
            <!-- Vapor -->
            <g stroke="#a8132c" stroke-width="2.5" fill="none" stroke-linecap="round" opacity="0.45">
                <path d="M 130,75 Q 125,55 135,40 Q 140,25 130,10"/>
                <path d="M 160,75 Q 155,55 165,40 Q 170,25 160,10"/>
                <path d="M 190,75 Q 185,55 195,40 Q 200,25 190,10"/>
            </g>
            <!-- Granos de café decorativos -->
            <g fill="#5c2a1f">
                <ellipse cx="240" cy="210" rx="9" ry="6" transform="rotate(20 240 210)"/>
                <path d="M 232,209 Q 240,205 248,209" stroke="#3a1812" stroke-width="1" fill="none" transform="rotate(20 240 210)"/>
            </g>
            <g fill="#5c2a1f">
                <ellipse cx="260" cy="225" rx="9" ry="6" transform="rotate(-15 260 225)"/>
                <path d="M 252,224 Q 260,220 268,224" stroke="#3a1812" stroke-width="1" fill="none" transform="rotate(-15 260 225)"/>
            </g>
            <g fill="#5c2a1f">
                <ellipse cx="60" cy="225" rx="9" ry="6" transform="rotate(35 60 225)"/>
                <path d="M 52,224 Q 60,220 68,224" stroke="#3a1812" stroke-width="1" fill="none" transform="rotate(35 60 225)"/>
            </g>
        </svg>
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
                    <img src="/?r=imagen&id=<?= (int)$p['id'] ?>"
                         alt="<?= htmlspecialchars($p['nombre']) ?>" loading="lazy">
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
