<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/profile.css">
<main>
    <div class="container">

        <?php if (empty($user)): ?>
            <section class="beauty-hero">
                <span class="beauty-eyebrow">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1"></path>
                    </svg>
                    Профиль игрока</span>
                <h1>Игрок не найден</h1>
                <p>Пользователь с ником «<?= htmlspecialchars($nickname ?? '') ?>» не зарегистрирован на сайте.</p>
                <div class="beauty-actions">
                    <a class="beauty-btn" href="/SweetLolly_new/">На главную</a>
                </div>
            </section>
        <?php else: ?>
            <section class="beauty-hero">
                <span class="beauty-eyebrow">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a5 5 0 1 0 0 10 5 5 0 1 0 0-10M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1"></path>
                    </svg>
                    Профиль игрока
                </span>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <h1><?= htmlspecialchars($user['nickname']) ?></h1>
                    <p>[Роль]</p>
                </div>
                <p class="muted">
                    На сайте с <?= date('d.m.Y', strtotime($user['created_at'])) ?>
                </p>
            </section>

            <section class="lolli-card" style="margin-bottom: 28px;">
                <div style="display: flex; gap: 28px; align-items: flex-start; flex-wrap: wrap;">

                    <!-- Аватар -->
                    <div style="flex: 0 0 auto; text-align: center;">
                        <img src="/SweetLolly_new/project/webroot/resources/noavatar.jpg"
                             alt="avatar"
                             style="width: 140px; height: 140px; border-radius: 22px; object-fit: cover; box-shadow: var(--soft-shadow);">
                    </div>

                    <!-- Инфо -->
                    <div style="flex: 1; min-width: 240px;">
                        <h2 style="font-weight: 800; font-size: 24px; margin-bottom: 16px;">Информация</h2>

                        <div style="display: grid; gap: 12px;">
                            <div>
                                <small class="muted">Ник</small><br>
                                <strong><?= htmlspecialchars($user['nickname']) ?></strong>
                            </div>

                            <?php if (!empty($isOwnProfile)): ?>
                                <div>
                                    <small class="muted">Email</small><br>
                                    <strong><?= htmlspecialchars($user['email']) ?></strong>
                                </div>
                            <?php endif; ?>

                            <div>
                                <small class="muted">Дата регистрации</small><br>
                                <strong><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></strong>
                            </div>

                            <div>
                                <small class="muted">Последнее обновление</small><br>
                                <strong><?= date('d.m.Y H:i', strtotime($user['updated_at'])) ?></strong>
                            </div>
                        </div>

                        <?php if (!empty($isOwnProfile)): ?>
                            <div style="margin-top: 24px; display: flex; gap: 10px; flex-wrap: wrap;">
                                <a href="/SweetLolly_new/logout/" class="beauty-btn secondary">Выйти из аккаунта</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Можно позже добавить статистику, донаты, сообщения и т.д. -->
            <section class="lolli-card">
                <h2 style="font-weight: 800; font-size: 22px; margin-bottom: 8px;">Статистика</h2>
                <p class="muted">Пока нет данных. Здесь появятся достижения, время игры, донаты и т.д.</p>
            </section>

        <?php endif; ?>

    </div>
</main>