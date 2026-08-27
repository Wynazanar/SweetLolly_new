<main>
    <div class="container">
        <div class="auth-shell">
            <section class="auth-card">
                <div class="auth-tabs">
                    <a class="auth-tab" href="/SweetLolly_new/login/">Вход</a>
                    <a class="auth-tab active" href="/SweetLolly_new/register/">Регистрация</a>
                </div>

                <h1 class="section-title">Регистрация игрока</h1>
                <p class="auth-help">Создайте профиль для личного кабинета SweetLolly.</p>

                <?php if (!empty($errors)): ?>
                    <div class="auth-help" style="color: #e74c3c; margin-bottom: 1rem;">
                        <?php foreach ($errors as $err): ?>
                            <div><?= htmlspecialchars($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/SweetLolly_new/register/" id="serverReg">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                    <input class="auth-input" type="text" name="username" placeholder="Ник на сервере" required
                        minlength="3" maxlength="32" autocomplete="username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

                    <input class="auth-input" type="email" name="email" placeholder="Email" required
                        autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

                    <input class="auth-input" type="password" name="password" placeholder="Пароль (минимум 8 символов)"
                        required minlength="8" autocomplete="new-password">

                    <input class="auth-input" type="password" name="password_confirm" placeholder="Повторите пароль"
                        required minlength="8" autocomplete="new-password">

                    <div class="auth-actions">
                        <button class="auth-btn" type="submit">Зарегистрироваться</button>
                        <a class="auth-btn secondary" href="/SweetLolly_new/login/">Уже есть аккаунт</a>
                    </div>
                </form>
            </section>

            <aside class="auth-side">
                <h2>После регистрации</h2>
                <p>Откроется личный кабинет, баланс сладостей и быстрые переходы по сайту.</p>
                <div class="auth-note">Форумный аккаунт можно создать отдельно на странице форума.</div>
            </aside>
        </div>
    </div>
</main>