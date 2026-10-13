
<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/profile.css">
<main>
    <div class="container">
        <p class="main-page">SweetLolly / #Настройки</p>
        <h1 class="main-title">Привет, Wynazanar!</h1>
        <p class="main-subtitle">Твой аккаунт, игровой баланс и настройки — всё под рукой.</p>
    
        <div class="settings-layout">
            <aside class="settings-sidebar">
                <div class="player-settings-info">
                    <div class="player-settings-card">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="settings-skin">
                                <img src="https://nmsr.nickac.dev/fullbody/d8cc6cb6-e884-47a0-a35b-80f41699f17c" alt="">
                            </div>
                            <div>
                                <h5><?= $user['nickname'] ?></h5>
                                <p><?= $user['prefix'] ?></p>
                            </div>
                        </div>

                        <span class="settings-online"></span>
                    </div>
    
                    <button class="settings-button">Обзор аккаунта</button>
                    <button class="settings-button">Безопасность</button>
                    <button class="settings-button">Уведомления</button>
                    <button class="settings-button">История операций</button>
                    <button class="settings-button">Выйти</button>
                </div>

                <div class="aside-help">
                    <p class="aside-help-title">Твоя статистика</p>
                    <p class="aside-help-descr">746 побед, 28 друзей и ещё много приключений впереди.</p>
                    <a class="aside-help-link" href="#">Профиль игрока</a>
                </div>
            </aside>

            <div>
                <div></div>

                <div>
                    <h4>Данные аккаунта</h4>

                    
                </div>

            </div>
        </div>

        <div class="bottom-help">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6667 23.3333C18.095 23.3333 23.3333 18.095 23.3333 11.6667C23.3333 5.23833 18.095 0 11.6667 0C5.23833 0 0 5.23833 0 11.6667C0 18.095 5.23833 23.3333 11.6667 23.3333ZM10.5 5.83333H12.8333V8.16667H10.5V5.83333ZM10.5 10.5H12.8333V17.5H10.5V10.5Z" fill="#8B57FF"/>
          </svg>
          <div>
            <p class="bottom-help-title">Заметил незнакомую операцию?</p>
            <p class="bottom-help-descr">Смени пароль и свяжись с поддержкой: sweetauth.project@gmail.com. Не публикуй личные данные в открытом чате.</p>
          </div>
        </div>

    </div>
</main>