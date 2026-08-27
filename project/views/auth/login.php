<link rel="stylesheet" href="/SweetLolly_new/Project/webroot/styles/auth.css">
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
                    <a class="beauty-btn" href="/SweetLolly_new/login/">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1">
                            </path>
                        </svg>
                        Вход
                    </a>
                    <a class="beauty-btn secondary purple" href="/SweetLolly_new/register/">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 11h-3V8h-2v3h-3v2h3v3h2v-3h3zM8 4a4 4 0 1 0 0 8 4 4 0 1 0 0-8M3 20h10c.55 0 1-.45 1-1v-1c0-2.76-2.24-5-5-5H7c-2.76 0-5 2.24-5 5v1c0 .55.45 1 1 1">
                            </path>
                        </svg>
                        Регистрация
                    </a>
                </div>
                <h2>С возвращением !</h2>
                <p class="muted">Введи ник и пароль для входа в аккаунт.</p>
                <form method="POST" action="/SweetLolly_new/login/" class="auth-match-form">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                    <?php if (!empty($error)): ?>
                        <div class="login-auth-message" style="color: #e74c3c;"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="auth-match-field">
                        <label>Ник или email</label>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1">
                            </path>
                        </svg>
                        <input name="login" placeholder="Например: Steve" required autocomplete="username">
                    </div>

                    <div class="auth-match-field">
                        <label>Пароль</label>
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M6 22h12c1.1 0 2-.9 2-2v-9c0-1.1-.9-2-2-2h-1V7c0-2.76-2.24-5-5-5S7 4.24 7 7v2H6c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2M9 7c0-1.65 1.35-3 3-3s3 1.35 3 3v2H9z">
                            </path>
                        </svg>
                        <input name="password" type="password" placeholder="Ваш пароль" required
                            autocomplete="current-password">
                    </div>

                    <button class="beauty-btn" type="submit" style="width: 100%;">Войти</button>
                </form>
                <div class="auth-match-links">
                    <a href="/SweetLolly_new/register/">
                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 11h-3V8h-2v3h-3v2h3v3h2v-3h3zM8 4a4 4 0 1 0 0 8 4 4 0 1 0 0-8M3 20h10c.55 0 1-.45 1-1v-1c0-2.76-2.24-5-5-5H7c-2.76 0-5 2.24-5 5v1c0 .55.45 1 1 1">
                            </path>
                        </svg>
                        Создать аккаунт
                    </a>
                    <a href="/SweetLolly_new/forum/login/">
                        <svg width="19" height="19" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2M6 9h3v2H6zm7 6H6v-2h7zm5 0h-3v-2h3zm0-4h-7V9h7z">
                            </path>
                        </svg>
                        Вход на форум
                    </a>
                </div>
                <div class="auth-match-note">
                    <b style="color: var(--footer-gray-color);">Важно:</b>
                    аккаунт сайта и аккаунт форума могут отличаться. Для форума используй отдельный вход.
                </div>
            </section>

            <aside class="auth-match-side">
                <div class="auth-match-mini">
                    <h3>Что откроется после входа?</h3>
                    <div class="auth-match-benefits">
                        <div class="auth-match-benefit">
                            <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z">
                                </path>
                                <path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                            </svg>
                            <div><b>Леденцы</b><span>Баланс, пополнение и бонусы проекта.</span></div>
                        </div>
                        <div class="auth-match-benefit">
                            <svg width="21" height="21" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1">
                                </path>
                            </svg>
                            <div><b>Кабинет</b><span>Профиль игрока и быстрые ссылки.</span></div>
                        </div>
                        <div class="auth-match-benefit">
                            <svg width="21" height="21" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z">
                                </path>
                            </svg>
                            <div><b>Сервер</b><span>Быстрый доступ к режимам и гайдам.</span></div>
                        </div>
                    </div>
                    <div class="auth-match-ip">
                        <div>
                            <small class="muted">IP сервера</small><br>
                            <code>play.swetlolly.net</code>
                        </div>
                        <button class="beauty-btn secondary" type="button"
                            data-copy="play.swetlolly.net">Скопировать</button>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>