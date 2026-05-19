<section class="section section--top">
    <h1 class="page-title">Tu pedido</h1>
    <p class="page-lead">Revisa los productos antes de confirmar.</p>

    <div id="cart-empty" class="alert" hidden>
        Tu carrito está vacío. <a href="/?r=productos">Ver carta →</a>
    </div>

    <div id="cart-loading" class="alert">
        Cargando…
    </div>

    <div id="cart-error" class="alert alert--error" hidden>
        Error al cargar los productos del carrito.
    </div>

    <div id="cart-content" hidden>
        <div class="cart">
            <ul id="cart-items" class="cart__list"></ul>

           <aside class="cart__summary">
    <h3 class="cart__summary-title">Resumen</h3>
    <div class="cart__row">
        <span>Subtotal</span>
        <span id="cart-subtotal">0,00 €</span>
    </div>
    <div class="cart__row cart__row--total">
        <span>Total</span>
        <span id="cart-total">0,00 €</span>
    </div>

    <label class="cart__obs">
        <span class="form__label">Observaciones (opcional)</span>
        <textarea id="cart-observaciones" maxlength="500" rows="3"
                  placeholder="Ej: sin gluten, para el aula 203..."></textarea>
    </label>

    <div id="cart-error-msg" class="alert alert--error" hidden></div>

    <?php if (!AuthService::logueado()): ?>
        <div class="alert alert--info">
            Para confirmar el pedido <a href="/?r=login">inicia sesión</a> o
            <a href="/?r=registro">crea una cuenta</a>.
        </div>
    <?php endif; ?>

    <button type="button" id="cart-checkout" class="btn btn--primary btn--lg btn--block" disabled>
        Confirmar pedido
    </button>
    <button type="button" id="cart-clear" class="btn btn--ghost btn--block">
        Vaciar carrito
    </button>
    <p class="cart__note">El pago se realiza en barra al recoger.</p>

    <input type="hidden" id="cart-csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
    <input type="hidden" id="cart-logged" value="<?= AuthService::logueado() ? '1' : '0' ?>">
</aside>
        </div>
    </div>
</section>
