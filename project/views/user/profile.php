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
                <h1>Игрок не найден</h1>
                <p>Пользователь с ником «<?= htmlspecialchars($nickname ?? '') ?>» не найден.</p>
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
                <h1><?= htmlspecialchars($user['nickname'] ?? '') ?></h1>
            </section>

            <section class="lolli-card" style="margin-bottom: 28px;">
                <div style="display: flex; gap: 28px; align-items: flex-start; flex-wrap: wrap;">

                    <div style="flex: 0 0 auto; text-align: center;">
                        <img src="https://nmsr.nickac.dev/fullbody/<?= htmlspecialchars($user['unique_id'] ?? $user['uuid'] ?? '—') ?>"
                             alt="avatar"
                             style="border-radius: 22px; object-fit: cover; box-shadow: var(--soft-shadow);">
                    </div>

                    <div style="flex: 1; min-width: 240px;">
                        <h2 style="font-weight: 800; font-size: 24px; margin-bottom: 16px;">Информация</h2>

                        <div style="display: grid; gap: 12px;">
                            <div>
                                <small class="muted">Ник</small><br>
                                <strong><?= htmlspecialchars($user['nickname'] ?? '—') ?></strong>
                            </div>

                            <div>
                                <small class="muted">UUID</small><br>
                                <strong><?= htmlspecialchars($user['unique_id'] ?? $user['uuid'] ?? '—') ?></strong>
                            </div>

                            <div>
                                <small class="muted">Дата регистрации</small><br>
                                <strong>
                                    <?php
                                    $created = $user['creation_date'] ?? $user['created_at'] ?? null;
                                    echo $created ? date('d.m.Y H:i', strtotime($created)) : '—';
                                    ?>
                                </strong>
                            </div>

                            <div>
                                <small class="muted">Последний вход</small><br>
                                <strong>
                                    <?php
                                    $last = $user['last_login'] ?? $user['updated_at'] ?? null;
                                    echo $last ? date('d.m.Y H:i', strtotime($last)) : '—';
                                    ?>
                                </strong>
                            </div>
                        </div>

                        <?php if (!empty($isOwnProfile)): ?>
                            <div style="margin-top: 24px;">
                                <a href="/SweetLolly_new/logout/" class="beauty-btn secondary">Выйти из аккаунта</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    </div>
</main>