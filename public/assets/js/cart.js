/* =========================================================
   Carrito de pedidos — almacenamiento en localStorage.
   El carrito vive 100% en cliente. La validación de precios
   y stock se hace siempre en servidor antes de confirmar.
   ========================================================= */
(function () {
    'use strict';

    const STORAGE_KEY = 'cafeteria_cart_v1';
    const MAX_QTY = 20;

    /* ---------- Almacén ---------- */

    function getCart() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch {
            return [];
        }
    }

    function saveCart(items) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        updateCartCount();
    }

    function addItem(id, nombre, precio, qty) {
        const items = getCart();
        const existing = items.find(i => i.id === id);

        if (existing) {
            existing.qty = Math.min(MAX_QTY, existing.qty + qty);
        } else {
            items.push({ id, nombre, precio, qty: Math.min(MAX_QTY, qty) });
        }
        saveCart(items);
    }

    function updateQty(id, qty) {
        const items = getCart();
        const item = items.find(i => i.id === id);
        if (!item) return;

        if (qty <= 0) {
            saveCart(items.filter(i => i.id !== id));
        } else {
            item.qty = Math.min(MAX_QTY, qty);
            saveCart(items);
        }
    }

    function clearCart() {
        localStorage.removeItem(STORAGE_KEY);
        updateCartCount();
    }

    /* ---------- UI: contador en la barra ---------- */

    function updateCartCount() {
        const badge = document.getElementById('cart-count');
        if (!badge) return;

        const total = getCart().reduce((sum, i) => sum + i.qty, 0);
        if (total > 0) {
            badge.textContent = total;
            badge.hidden = false;
        } else {
            badge.hidden = true;
        }
    }

    /* ---------- UI: feedback visual al añadir ---------- */

    function flashButton(btn) {
        const original = btn.textContent;
        btn.textContent = '✓ Añadido';
        btn.classList.add('is-added');
        setTimeout(() => {
            btn.textContent = original;
            btn.classList.remove('is-added');
        }, 1200);
    }

    /* ---------- Botones "Añadir" en cards y detalle ---------- */

    function bindAddButtons() {
        document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const id = parseInt(btn.dataset.id, 10);
                const nombre = btn.dataset.nombre;
                const precio = parseFloat(btn.dataset.precio);

                // ¿Hay un input de cantidad cerca? (página detalle)
                const qtyInput = document.getElementById('qty');
                const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value, 10) || 1) : 1;

                if (!Number.isInteger(id) || id <= 0 || isNaN(precio)) return;

                addItem(id, nombre, precio, qty);
                flashButton(btn);
            });
        });
    }

    /* ---------- Detalle: botones +/- ---------- */

    function bindQtyButtons() {
        const qtyInput = document.getElementById('qty');
        if (!qtyInput) return;

        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const delta = parseInt(btn.dataset.qty, 10);
                const current = parseInt(qtyInput.value, 10) || 1;
                qtyInput.value = Math.max(1, Math.min(MAX_QTY, current + delta));
            });
        });
    }

    /* ---------- Página de carrito ---------- */

    function formatPrice(num) {
        return num.toFixed(2).replace('.', ',') + ' €';
    }

    async function renderCartPage() {
    const cartContent = document.getElementById('cart-content');
    if (!cartContent) return; // No estamos en la página del carrito

    const empty = document.getElementById('cart-empty');
    const loading = document.getElementById('cart-loading');
    const errorEl = document.getElementById('cart-error');

    const items = getCart();

    // Si el carrito ya está vacío, ocultar todo lo demás
    if (items.length === 0) {
        if (loading) loading.hidden = true;
        if (errorEl) errorEl.hidden = true;
        cartContent.hidden = true;
        if (empty) empty.hidden = false;
        // Vaciar lista por si quedaban items pintados
        const list = document.getElementById('cart-items');
        if (list) list.innerHTML = '';
        return;
    }

    // Revalidar contra el servidor
    let serverProducts;
    try {
        const resp = await fetch('/?r=carrito', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: items.map(i => i.id) })
        });
        const data = await resp.json();
        if (data.error) throw new Error(data.error);
        serverProducts = data.productos;
    } catch (err) {
        if (loading) loading.hidden = true;
        if (errorEl) errorEl.hidden = false;
        console.error(err);
        return;
    }

    // Cruzar carrito con datos del servidor (precios y nombres autoritativos)
    const validItems = [];
    for (const cartItem of items) {
        const server = serverProducts.find(p => p.id === cartItem.id);
        if (!server) continue;
        validItems.push({
            id: server.id,
            nombre: server.nombre,
            precio: parseFloat(server.precio),
            categoria: server.categoria_nombre,
            qty: cartItem.qty
        });
    }

    if (validItems.length !== items.length) {
        saveCart(validItems.map(({ id, nombre, precio, qty }) => ({ id, nombre, precio, qty })));
    }

    if (validItems.length === 0) {
        if (loading) loading.hidden = true;
        cartContent.hidden = true;
        if (empty) empty.hidden = false;
        const list = document.getElementById('cart-items');
        if (list) list.innerHTML = '';
        return;
    }

    renderItems(validItems);
    if (loading) loading.hidden = true;
    if (empty) empty.hidden = true;
    if (errorEl) errorEl.hidden = true;
    cartContent.hidden = false;
}

    function renderItems(items) {
    const list = document.getElementById('cart-items');
    list.innerHTML = '';

    let subtotal = 0;

    items.forEach(item => {
        const lineTotal = item.precio * item.qty;
        subtotal += lineTotal;

        const li = document.createElement('li');
        li.className = 'cart__item';
        li.innerHTML = `
            <img class="cart__img"
                 src="/?r=imagen&id=${item.id}"
                 alt="">
            <div class="cart__info">
                <span class="cart__cat">${escapeHtml(item.categoria || '')}</span>
                <h4 class="cart__name">${escapeHtml(item.nombre)}</h4>
                <span class="cart__unit">${formatPrice(item.precio)} / ud.</span>
            </div>
            <div class="cart__qty">
                <button type="button" class="qty-btn" data-action="dec" data-id="${item.id}" aria-label="Restar">−</button>
                <span class="cart__qty-num">${item.qty}</span>
                <button type="button" class="qty-btn" data-action="inc" data-id="${item.id}" aria-label="Sumar">+</button>
            </div>
            <div class="cart__line-total">${formatPrice(lineTotal)}</div>
            <button type="button" class="cart__remove" data-action="del" data-id="${item.id}" aria-label="Eliminar">×</button>
        `;
        list.appendChild(li);
    });

    document.getElementById('cart-subtotal').textContent = formatPrice(subtotal);
    document.getElementById('cart-total').textContent = formatPrice(subtotal);
    document.getElementById('cart-checkout').disabled = false;

    // Bind acciones de la lista
    list.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id, 10);
            const action = btn.dataset.action;
            const cart = getCart();
            const item = cart.find(i => i.id === id);
            if (!item) return;

            if (action === 'inc') updateQty(id, item.qty + 1);
            else if (action === 'dec') updateQty(id, item.qty - 1);
            else if (action === 'del') updateQty(id, 0);

            renderCartPage();
        });
    });
}

    /* ---------- Vaciar carrito + checkout ---------- */

    function bindCartActions() {
    const clearBtn = document.getElementById('cart-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (confirm('¿Vaciar el carrito?')) {
                clearCart();
                renderCartPage();
            }
        });
    }

    const checkoutBtn = document.getElementById('cart-checkout');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', async () => {
            const logged = document.getElementById('cart-logged')?.value === '1';
            if (!logged) {
                window.location.href = '/?r=login';
                return;
            }

            const items = getCart();
            if (items.length === 0) return;

            const csrf = document.getElementById('cart-csrf')?.value || '';
            const observaciones = document.getElementById('cart-observaciones')?.value || '';
            const errEl = document.getElementById('cart-error-msg');
            if (errEl) errEl.hidden = true;

            // Estado de carga
            const originalText = checkoutBtn.textContent;
            checkoutBtn.disabled = true;
            checkoutBtn.textContent = 'Procesando…';

            try {
                const resp = await fetch('/?r=pedido-confirmar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        _csrf: csrf,
                        items: items.map(i => ({ id: i.id, qty: i.qty })),
                        observaciones: observaciones,
                    }),
                });

                const data = await resp.json();

                if (resp.ok && data.ok) {
                    clearCart();
                    window.location.href = data.redirect + '&nuevo=1';
                } else {
                    if (errEl) {
                        errEl.textContent = data.error || 'No se pudo confirmar el pedido.';
                        errEl.hidden = false;
                    }
                    checkoutBtn.disabled = false;
                    checkoutBtn.textContent = originalText;
                    if (data.redirect) {
                        setTimeout(() => { window.location.href = data.redirect; }, 1500);
                    }
                }
            } catch (e) {
                console.error(e);
                if (errEl) {
                    errEl.textContent = 'Error de conexión. Inténtalo de nuevo.';
                    errEl.hidden = false;
                }
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = originalText;
            }
        });
    }
}

    /* ---------- Helpers ---------- */

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    function escapeAttr(s) { return escapeHtml(s); }

    /* ---------- Init ---------- */

    document.addEventListener('DOMContentLoaded', () => {
        updateCartCount();
        bindAddButtons();
        bindQtyButtons();
        bindCartActions();
        renderCartPage();
    });
})();
