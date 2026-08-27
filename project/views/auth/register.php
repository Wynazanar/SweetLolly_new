<main>
    <div class="container">
        <div class="auth-shell">
            <section class="auth-card">
                <div class="auth-tabs"><a class="auth-tab" href="/sweetbcp/lollipopmc/login/">Вход</a><a
                        class="auth-tab active" href="/sweetbcp/lollipopmc/register/">Регистрация сайта</a><a
                        class="auth-tab" href="/sweetbcp/lollipopmc/forum/register/">Регистрация форума</a></div>
                <h1 class="section-title">Регистрация игрока</h1>
                <p class="auth-help">Создайте профиль для личного кабинета SweetLolly. Это демо-функционал сайта:
                    данные сохраняются в браузере.</p>
                <form id="serverReg"><input class="auth-input" id="regName" placeholder="Ник на сервере" required><input
                        class="auth-input" id="regMail" type="email" placeholder="Email"><input class="auth-input"
                        id="regPass" type="password" placeholder="Пароль" required><input class="auth-input"
                        id="regPass2" type="password" placeholder="Повторите пароль" required>
                    <div id="regMsg" class="auth-help"></div>
                    <div class="auth-actions"><button class="auth-btn" type="submit">Зарегистрироваться</button><a
                            class="auth-btn secondary" href="/sweetbcp/lollipopmc/login/">Уже есть аккаунт</a></div>
                </form>
            </section>
            <aside class="auth-side">
                <h2>После регистрации</h2>
                <p>Откроется личный кабинет, баланс сладостей и быстрые переходы по сайту.</p>
                <div class="auth-note">Форумный аккаунт можно создать отдельно на странице форума.</div>
            </aside>
        </div>
    </div>
    <script>serverReg.addEventListener('submit', function (e) { e.preventDefault(); if (regPass.value !== regPass2.value) { regMsg.textContent = 'Пароли не совпадают.'; return; } LolliAccount.registerServer(regName.value.trim(), regPass.value, regMail.value); location.href = '/sweetbcp/lollipopmc/cabinet/'; });</script>
</main>