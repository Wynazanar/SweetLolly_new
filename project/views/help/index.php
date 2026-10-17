<?php
require_once $projectRoot . '/project/components/Accordion.php';
?>

<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/help.css">
<main>
    <div class="container">
        <div></div>

        <div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

        <div style="margin-top: 28px;">
            <div class="div-title">
                <h2>Популярные вопросы</h2>
                <div class="div-quick-questions">
                    <p>Все вопросы</p>
                    <p>Подключение</p>
                    <p>Аккаунт</p>
                </div>
            </div>
            <div class="layout">
                <div style="display: grid; gap: 14px; width: 100%;">
                    <?php
                        echo Accordion::make(
                            title: 'Не могу подключиться. Что проверить?',
                            description: 'Проверь адрес play.sweetlolly.net и интернет-соединение. Перезапусти игру. Если ошибка остаётся, сделай скриншот сообщения и уточни совместимую версию у поддержки.',
                            open: true
                        );
                        echo Accordion::make(
                            title: 'Какая версия Minecraft поддерживается?',
                            description: 'Подтверждённый список версий пока не указан. Перед подключением уточни актуальную совместимость в объявлениях сообщества или у поддержки.',
                            open: true
                        );
                        echo Accordion::make(
                            title: 'Как восстановить доступ к аккаунту?',
                            description: '',
                            open: false
                        );
                        echo Accordion::make(
                            title: 'Как сообщить о нарушении?',
                            description: '',
                            open: false
                        );
                        echo Accordion::make(
                            title: 'Почему не вижу пополнение баланса?',
                            description: '',
                            open: false
                        );
                        echo Accordion::make(
                            title: 'Где найти друзей для совместной игры?',
                            description: '',
                            open: false
                        );
                    ?>
                </div>

                <div class="aside-help">
                    <h3 class="aside-help-title">Не нашёл ответ?</h3>
                    <p class="aside-help-descr">Напиши нам. Приложи никнейм и короткое описание ситуации.</p>
                    <a style="display: flex; margin-block: 0px;" href="sweetauth.project@gmail.com" class="aside-help-descr purple">sweetauth.project@gmail.com</a>
                    <a class="aside-help-link purple">Написать на email</a>
                    <p class="aside-help-descr" style="margin-block: 0px;">Не отправляй пароли и платёжные данные. Срок ответа не фиксирован.</p>
                    <a class="aside-help-link">Открыть форум</a>
                </div>
            </div>
        </div>

        <div class="bottom-help">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M11.6667 23.3333C18.095 23.3333 23.3333 18.095 23.3333 11.6667C23.3333 5.23833 18.095 0 11.6667 0C5.23833 0 0 5.23833 0 11.6667C0 18.095 5.23833 23.3333 11.6667 23.3333ZM10.5 5.83333H12.8333V8.16667H10.5V5.83333ZM10.5 10.5H12.8333V17.5H10.5V10.5Z" fill="#8B57FF"/>
            </svg>
            <div>
                <p class="bottom-help-title">Как составить полезное обращение</p>
                <p class="bottom-help-descr">Никнейм → режим → дата и время → что произошло → скриншот ошибки. Для заказа добавь его номер. Так нам будет проще разобраться.</p>
            </div>
        </div>
    </div>
</main>