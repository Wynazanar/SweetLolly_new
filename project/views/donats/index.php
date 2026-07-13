<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Донат | SweetLolly</title>
    <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/donats.css">
</head>

<body class="">
    <main style="Margin-top: 50px;">
        <div class="container donate-common">
            <section class="beauty-hero donate-hero3">
                <span class="beauty-eyebrow">
                    <svg  xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24" >
                        <path d="M20.33 3.06a1 1 0 0 0-1.11.32L16 7.4l-3.22-4.02c-.38-.47-1.18-.47-1.56 0L8 7.4 4.78 3.38c-.27-.33-.71-.46-1.11-.32S3 3.58 3 4v11h18V4c0-.42-.27-.8-.67-.94M3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2H3z"></path>
                    </svg>
                    Магазин привилегий
                </span>
                <h1>Донат SweetLolly</h1>
                <p>Красивые candy-привилегии с фирменными артами, в едином стиле сайта. Косметика, статус и поддержка
                    проекта без pay-to-win.</p>
                <div class="beauty-actions">
                    <a class="beauty-btn" href="#privileges">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" >
                            <path d="M20.33 3.06a1 1 0 0 0-1.11.32L16 7.4l-3.22-4.02c-.38-.47-1.18-.47-1.56 0L8 7.4 4.78 3.38c-.27-.33-.71-.46-1.11-.32S3 3.58 3 4v11h18V4c0-.42-.27-.8-.67-.94M3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2H3z"></path>
                        </svg>
                        Привилегии
                    </a>
                    <a class="beauty-btn secondary" href="#candies">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" >
                            <path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path><path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                        </svg>
                        Леденцы
                    </a>
                    <a class="beauty-btn secondary" href="#">
                        <i class="fa fa-user"></i>
                        Войти
                    </a>
                </div>
            </section>

            <section class="donate-block" id="privileges">
                <h2 class="donate-title">Привилегии</h2>
                <p class="donate-sub">Каждая привилегия — с отдельным красивым candy-артом.</p>
                <div class="priv3-grid">

                    <?php foreach($subs as $sub): ?>
                        <article class="priv3 lapis">
                            <div class="priv3-media"
                                style="background-image:url(/SweetLolly_new/project/webroot/images/subs/<?= $sub['path_image'] ?>)">
                                <span class="priv3-badge">
                                    Скидка <?= $sub['discount'] ?>л.!
                                </span>
                                <span class="priv3-period">
                                    30 дн. / навсегда
                                </span>
                            </div>
                            <div class="priv3-body">
                                <h3><?= $sub['name'] ?></h3>
                                <div class="priv3-price two">
                                    <div class="pp">
                                        <strong><?= $sub['priceMonth'] - $sub['discount'] ?></strong>
                                        <span>руб. / 30 дней</span>
                                    </div>
                                    <div class="pp">
                                        <strong><?= $sub['priceAlways'] ?></strong>
                                        <span>руб. / навсегда</span>
                                    </div>
                                </div>
                                <a class="beauty-btn" href="#">Купить</a>
                            </div>
                        </article>
                    <?php endforeach; ?>

                </div>
            </section>

            <section class="donate-block" id="candies">
                <h2 class="donate-title">Леденцы</h2>
                <p class="donate-sub">Пакеты валюты для пополнения баланса.</p>
                <div class="beauty-grid pack-grid2">
                    <article class="beauty-card pack2"><b>100</b><span>леденцов</span><em>≈ 10 ₽</em><a
                            class="beauty-btn secondary" href="/lollipopmc/login/">Выбрать</a></article>
                    <article class="beauty-card pack2 popular"><b>500</b><span>+50 бонусом</span><em>≈ 50 ₽</em><a
                            class="beauty-btn" href="/lollipopmc/login/">Выбрать</a></article>
                    <article class="beauty-card pack2"><b>1000</b><span>+150 бонусом</span><em>≈ 100 ₽</em><a
                            class="beauty-btn secondary" href="/lollipopmc/login/">Выбрать</a></article>
                </div>
            </section>

            <section class="donate-block">
                <h2 class="donate-title">Как купить</h2>
                <div class="beauty-grid steps-grid2">
                    <article class="beauty-card"><i class="fa fa-user"></i>
                        <h3>1. Войди</h3>
                        <p>Авторизуйся в аккаунте SweetLolly.</p>
                    </article>
                    <article class="beauty-card">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 24 24" >
                            <path d="M20.33 3.06a1 1 0 0 0-1.11.32L16 7.4l-3.22-4.02c-.38-.47-1.18-.47-1.56 0L8 7.4 4.78 3.38c-.27-.33-.71-.46-1.11-.32S3 3.58 3 4v11h18V4c0-.42-.27-.8-.67-.94M3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2H3z"></path>
                        </svg>
                        <h3>2. Выбери</h3>
                        <p>Привилегия или пакет леденцов.</p>
                    </article>
                    <article class="beauty-card">
                        <i class="fa fa-receipt"></i>
                        <h3>3. Оплати</h3>
                        <p>Проверь ник перед оплатой.</p>
                    </article>
                    <article class="beauty-card">
                        <svg  xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 24 24" >
                            <path d="M9 15.59 4.71 11.3 3.3 12.71l5 5c.2.2.45.29.71.29s.51-.1.71-.29l11-11-1.41-1.41L9.02 15.59Z"></path>
                        </svg>
                        <h3>4. Готово</h3>
                        <p>Статус появится в кабинете.</p>
                    </article>
                </div>
            </section>
        </div>
    </main>
</body>

</html>