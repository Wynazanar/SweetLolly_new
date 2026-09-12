<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/profile.css">
<main>
    <div class="container">

        <?php if (empty($user)): ?>
            <section class="beauty-hero">
                <span class="beauty-eyebrow">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1"></path>
                    </svg>
                    Профиль игрока
                </span>
                <?php if (!empty($bridgeError)): ?>
                    <h1>Данные временно недоступны</h1>
                    <p>Не удалось связаться с сервером игровых данных. Попробуйте позже.</p>
                <?php else: ?>
                    <h1>Игрок не найден</h1>
                    <p>Пользователь с ником «<?= htmlspecialchars($nickname ?? '') ?>» не найден на сервере.</p>
                <?php endif; ?>
                <div class="beauty-actions">
                    <a class="beauty-btn" href="/SweetLolly_new/">На главную</a>
                </div>
            </section>
        <?php else: ?>           
            <div class="container">
                <h2 class="section-title">Профиль игрока</h2>
                <div class="grid-2">
                    <div class="left-panel">
                        <div class="avatar">
                            <?php
                            $skinUuid = $user['mojang_uuid'] ?? $user['unique_id'] ?? '';
                            $skinUrl = $skinUuid !== ''
                                ? 'https://nmsr.nickac.dev/fullbody/' . rawurlencode($skinUuid)
                                : 'https://nmsr.nickac.dev/fullbody/' . rawurlencode($user['nickname'] ?? 'Steve');
                            ?>
                            <img src="<?= htmlspecialchars($skinUrl) ?>" alt="">
                        </div>
                        <h2 class="nickname"><?= htmlspecialchars($user['nickname'] ?? '') ?></h2>
                        <h3 class="player-role-pill">
                            <svg  xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24" >
                                <path d="M20.33 3.06a1 1 0 0 0-1.11.32L16 7.4l-3.22-4.02c-.38-.47-1.18-.47-1.56 0L8 7.4 4.78 3.38c-.27-.33-.71-.46-1.11-.32S3 3.58 3 4v11h18V4c0-.42-.27-.8-.67-.94M3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2H3z"></path>
                            </svg>
                            developer
                        </h3>
                        <nav class="player-info">
                            <div style="background: var(--border); height: 2px; border-radius: 25px; margin-top: 10px;"></div>
                            <a href="#">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="M19 4h-2V2h-2v2H9V2H7v2H5c-1.1 0-2 .9-2 2v1h18V6c0-1.1-.9-2-2-2M3 20c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8H3zm12-8h2v2h-2zm0 4h2v2h-2zm-4-4h2v2h-2zm0 4h2v2h-2zm-4-4h2v2H7zm0 4h2v2H7z"></path>
                                </svg>
                                <div class="info-titles">
                                    <p>Регистрация</p>
                                    <h4><?php
                                    $created = $user['creation_date'] ?? $user['created_at'] ?? null;
                                    echo $created ? date('d.m.Y', strtotime($created)) : '—';
                                    ?></h4>
                                </div>
                            </a>
                            <a href="/SweetLolly_new/candies/">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z"></path>
                                    <path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                                </svg>
                                <div class="info-titles">
                                    <p>Леденцы</p>
                                    <h4><?php
                                    if (array_key_exists('points', $user) && $user['points'] !== null) {
                                        echo number_format((int) $user['points'], 0, '', ' ');
                                    } else {
                                        echo '—';
                                    }
                                    ?></h4>
                                </div>
                            </a>
                            <a href="#">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="M12 5a3 3 0 1 0 0 6 3 3 0 1 0 0-6m1 7h-2c-2.76 0-5 2.24-5 5v.5c0 .83.67 1.5 1.5 1.5h9c.83 0 1.5-.67 1.5-1.5V17c0-2.76-2.24-5-5-5m-6.5-1c.47 0 .9-.12 1.27-.33a5.03 5.03 0 0 1-.42-4.52C7.09 6.06 6.8 6 6.5 6 5.06 6 4 7.06 4 8.5S5.06 11 6.5 11m-.39 1H5.5C3.57 12 2 13.57 2 15.5v1c0 .28.22.5.5.5H4c0-1.96.81-3.73 2.11-5m11.39-1c1.44 0 2.5-1.06 2.5-2.5S18.94 6 17.5 6c-.31 0-.59.06-.85.15a5.03 5.03 0 0 1-.42 4.52c.37.21.79.33 1.27.33m1 1h-.61A6.97 6.97 0 0 1 20 17h1.5c.28 0 .5-.22.5-.5v-1c0-1.93-1.57-3.5-3.5-3.5"></path>
                                </svg>
                                <div class="info-titles">
                                    <p>Друзья</p>
                                    <h4>85</h4>
                                </div>
                            </a>
                            <a href="#">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="m6.87 14.33-1.83 6.4c-.12.4.03.84.37 1.08.34.25.8.26 1.14.02L12 18.2l5.45 3.63a.99.99 0 0 0 1.14-.02c.34-.25.49-.68.37-1.08l-1.83-6.4 4.54-4.08c.3-.27.41-.69.28-1.06-.13-.38-.47-.64-.87-.68l-5.7-.45-2.47-5.46a.998.998 0 0 0-1.82 0L8.62 8.06l-5.7.45c-.4.03-.74.3-.87.68s-.02.8.28 1.06z"></path>
                                </svg>
                                <div class="info-titles">
                                    <p>Любимый режим</p>
                                    <h4>TNT Run</h4>
                                </div>
                            </a>
                            <div style="background: var(--border); height: 2px; border-radius: 25px;"></div>
                        </nav>
                    </div>

                    <div class="right-panel">
                        <h2>Статистика</h2>
                        <div class="">Карточки</div>
                        <h3>Статистика по мини-играм</h3>
                        <div class="">Карточки</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>