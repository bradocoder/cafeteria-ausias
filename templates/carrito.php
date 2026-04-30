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
                <button type="button" id="cart-checkout" class="btn btn--primary btn--lg btn--block" disabled>
                    Confirmar pedido
                </button>
                <button type="button" id="cart-clear" class="btn btn--ghost btn--block">
                    Vaciar carrito
                </button>
                <p class="cart__note">El pago se realiza en barra al recoger. (Confirmación pendiente de implementar con backend)</p>
            </aside>
        </div>
    </div>
</section>
