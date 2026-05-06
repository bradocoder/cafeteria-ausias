<section class="auth">
    <div class="auth__card">
        <h1 class="auth__title">Iniciar sesión</h1>
        <p class="auth__lead">Accede para hacer tu pedido y consultar tu historial.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/?r=login" class="form" novalidate>
            <?= Csrf::field() ?>
            <label class="form__field">
                <span class="form__label">Usuario</span>
                <input type="text" name="username" required autofocus autocomplete="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </label>
            <label class="form__field">
                <span class="form__label">Contraseña</span>
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <button type="submit" class="btn btn--primary btn--block">Entrar</button>
        </form>

        <p class="auth__footer">¿No tienes cuenta? <a href="/?r=registro">Crear una</a></p>
    </div>
</section>
