<main>
    <div class="container auth-match-wrap">

        <section class="beauty-hero">
            <span class="beauty-eyebrow">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="m16 12-6-5v4H3v2h7v4z"></path>
                    <path d="M19 3h-7v2h7v14h-7v2h7c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2"></path>
                </svg>
                Авторизация
            </span>
            <h1>Вход в аккаунт</h1>
            <p>Статическая страница входа теперь выглядит как основа сайта.</p>
        </section>

        <div class="auth-match-grid">

            <section class="auth-match-card">
                <div class="auth-match-tabs">
                    <a class="auth-match-tab active" href="/SweetLolly_new/login/">
                        <i class="fa fa-user"></i>
                        Вход
                    </a>
                    <a class="auth-match-tab" href="/SweetLolly_new/register/">
                        <i class="fa fa-user-plus"></i>
                        Регистрация
                    </a>
                </div>
                <h2>С возвращением</h2>
                <p class="muted">Введи ник и пароль.</p>
                <form method="POST" action="/SweetLolly_new/login/" class="auth-match-form">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                    <?php if (!empty($error)): ?>
                        <div class="login-auth-message" style="color: #e74c3c;"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="auth-match-field">
                        <label>Ник или email</label>
                        <i class="fa fa-user"></i>
                        <input name="login" placeholder="Например: Steve" required autocomplete="username">
                    </div>

                    <div class="auth-match-field">
                        <label>Пароль</label>
                        <i class="fa fa-lock"></i>
                        <input name="password" type="password" placeholder="Ваш пароль" required
                            autocomplete="current-password">
                    </div>

                    <button class="auth-match-submit" type="submit">Войти</button>
                </form>
                <div class="auth-match-links"><a href="/sweetbcp/lollipopmc/register/"><i class="fa fa-user-plus"></i>
                        Создать аккаунт</a><a href="/sweetbcp/lollipopmc/forum/login/"><i class="fa fa-comments"></i>
                        Вход на
                        форум</a></div>
            </section>

            <aside class="auth-match-side">
                <div class="auth-match-mini">
                    <h3>IP сервера</h3>
                    <div class="auth-match-ip">
                        <div>
                            <small class="muted">IP</small><br>
                            <code>play.swetlolly.net</code>
                        </div>
                        <button type="button" data-copy="play.swetlolly.net">Скопировать</button>
                    </div>
                </div>
            </aside>

        </div>

    </div>
</main>