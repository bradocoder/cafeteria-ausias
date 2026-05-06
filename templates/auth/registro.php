<section class="auth">
    <div class="auth__card">
        <h1 class="auth__title">Crear cuenta</h1>
        <p class="auth__lead">Regístrate para empezar a hacer pedidos.</p>

        <?php if (!empty($errores)): ?>
            <div class="alert alert--error">
                <ul style="margin:0;padding-left:1.2rem;">
                    <?php foreach ($errores as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/?r=registro" class="form" novalidate>
            <?= Csrf::field() ?>
            <label class="form__field">
                <span class="form__label">Nombre completo</span>
                <input type="text" name="nombre" required maxlength="100"
                       value="<?= htmlspecialchars($datos['nombre']) ?>">
            </label>
            <label class="form__field">
                <span class="form__label">Usuario</span>
                <input type="text" name="username" required minlength="3" maxlength="50"
                       pattern="[a-zA-Z0-9_.\-]+"
                       value="<?= htmlspecialchars($datos['username']) ?>">
                <small class="form__hint">Letras, números, punto, guion y guion bajo.</small>
            </label>
            <label class="form__field">
                <span class="form__label">Curso o grupo (opcional)</span>
                <input type="text" name="curso_grupo" maxlength="50"
                       placeholder="Ej: 2º ASIR"
                       value="<?= htmlspecialchars($datos['curso_grupo'] ?? '') ?>">
            </label>
            <label class="form__field">
                <span class="form__label">Contraseña</span>
                <input type="password" name="password" required minlength="8" autocomplete="new-password">
                <small class="form__hint">Mínimo 8 caracteres.</small>
            </label>
            <label class="form__field">
                <span class="form__label">Repetir contraseña</span>
                <input type="password" name="password2" required minlength="8" autocomplete="new-password">
            </label>
            <button type="submit" class="btn btn--primary btn--block">Crear cuenta</button>
        </form>

        <p class="auth__footer">¿Ya tienes cuenta? <a href="/?r=login">Iniciar sesión</a></p>
    </div>
</section>
