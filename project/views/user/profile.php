<?php
/**
 * Конфигурация отображения статистики мини-игр
 */
$minigames_config = [
    'BedWars' => [
        'fields' => [
            'wins'           => 'Побед',
            'losses'         => 'Поражений',
            'kills'          => 'Убийств',
            'deaths'         => 'Смертей',
            'beds_destroyed' => 'Кроватей',
            'rating'         => 'Рейтинг',
        ],
        'show_kd'      => true,
        'show_winrate' => true,
    ],
    'Classic Duel' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'draws'  => 'Ничьих',
            'hits'   => 'Хитов',
        ],
        'show_kd'      => false,
        'show_winrate' => true,
    ],
    'Bow Duel' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'draws'  => 'Ничьих',
            'hits'   => 'Хитов',
        ],
        'show_kd'      => false,
        'show_winrate' => true,
    ],
    'Boxing Duel' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'draws'  => 'Ничьих',
            'hits'   => 'Хитов',
        ],
        'show_kd'      => false,
        'show_winrate' => true,
    ],
    'UHC Duel' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'draws'  => 'Ничьих',
            'kills'  => 'Убийств',
            'deaths' => 'Смертей',
        ],
        'show_kd'      => true,
        'show_winrate' => true,
    ],
    'Sumo' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'draws'  => 'Ничьих',
        ],
        'show_kd'      => false,
        'show_winrate' => true,
    ],
    'Bridge Solo' => [
        'fields' => [
            'wins'   => 'Побед',
            'losses' => 'Поражений',
            'goals'  => 'Голов',
        ],
        'show_kd'      => false,
        'show_winrate' => true,
    ],
];
?>

<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/profile.css">
<main>
    <div class="container">
        <p class="main-page">SweetLolly / #<?= htmlspecialchars($user['nickname'] ?? 'Игрок') ?></p>
        <h1 class="main-title">Профиль игрока</h1>
        <p class="main-subtitle">Приключения, достижения и друзья — всё, что делает твою игру особенной.</p>

        <div class="profile-layout">
            <!-- Сайдбар -->
            <aside class="profile-sidebar">
                <div class="avatar">
                    <?php $nickname = trim($user['nickname'] ?? 'Steve'); ?>
                    <img src="https://nmsr.nickac.dev/fullbody/<?= htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8') ?>" alt="Скин <?= htmlspecialchars($nickname) ?>">
                </div>

                <h2 class="nickname"><?= htmlspecialchars($user['nickname'] ?? '') ?></h2>

                <div class="role-pills">
                    <span class="player-role-pill">
                        <?= htmlspecialchars($user['prefix'] ?? 'Игрок') ?>
                    </span>
                    <span class="player-role-pill online">Онлайн</span>
                </div>

                <div class="profile-meta">
                    <div class="meta-item">
                        <span class="meta-label">Регистрация</span>
                        <span class="meta-value">
                            <?php
                                $created = $user['creation_date'] ?? $user['created_at'] ?? null;
                                echo $created ? date('d.m.Y', strtotime($created)) : '—';
                            ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Друзья</span>
                        <span class="meta-value">28</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Любимый режим</span>
                        <span class="meta-value">BedWars</span>
                    </div>
                </div>

                <?php if ($isOwnProfile): ?>
                    <a class="profile-btn" href="/SweetLolly_new/logout/">Выйти из аккаунта</a>
                <?php endif; ?>
            </aside>

            <!-- Основной контент -->
            <div class="profile-content">

                <!-- Статистика -->
                <section class="profile-section">
    <div class="section-header">
        <h4>Статистика по мини-играм</h4>
        <span class="section-count"><?= count(array_filter($minigame_stats ?? [])) ?> режимов</span>
    </div>

    <div class="stats-grid">
        <?php foreach ($minigames_config as $gameName => $config): 
            if (empty($minigame_stats[$gameName])) continue;

            $stats = $minigame_stats[$gameName];

            // Считаем винрейт
            $wins   = (int)($stats['wins'] ?? 0);
            $losses = (int)($stats['losses'] ?? 0);
            $draws  = (int)($stats['draws'] ?? 0);
            $total  = $wins + $losses + $draws;
            $winrate = $total > 0 ? round(($wins / $total) * 100) : 0;

            // K/D (если нужно)
            $kd = null;
            if ($config['show_kd']) {
                $kills  = (int)($stats['kills'] ?? 0);
                $deaths = (int)($stats['deaths'] ?? 0);
                $kd = $deaths > 0 ? round($kills / $deaths, 2) : $kills;
            }
        ?>
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3><?= htmlspecialchars($gameName) ?></h3>
                    <?php if (!empty($stats['rating'])): ?>
                        <span class="stat-badge">Рейтинг <?= (int)$stats['rating'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="stat-main">
                    <?php if ($config['show_winrate']): ?>
                        <div class="stat-big">
                            <span class="stat-number"><?= $winrate ?>%</span>
                            <span class="stat-label">Винрейт</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($kd !== null): ?>
                        <div class="stat-big">
                            <span class="stat-number"><?= $kd ?></span>
                            <span class="stat-label">K/D</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="stat-details">
                    <?php foreach ($config['fields'] as $key => $label): 
                        if (!isset($stats[$key])) continue;
                    ?>
                        <div class="stat-row">
                            <span><?= $label ?></span>
                            <strong><?= (int)$stats[$key] ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

                <!-- Достижения -->
                <section class="profile-section">
                    <div class="section-header">
                        <h4>Достижения</h4>
                        <span class="section-count">4 / 12</span>
                    </div>
                    <div class="achievements-grid">
                        <div class="achievement unlocked">
                            <div class="achievement-icon">🛏️</div>
                            <div class="achievement-info">
                                <h5>Разрушитель кроватей</h5>
                                <p>Уничтожь 50 кроватей</p>
                            </div>
                        </div>
                        <div class="achievement unlocked">
                            <div class="achievement-icon">⚔️</div>
                            <div class="achievement-info">
                                <h5>Первая кровь</h5>
                                <p>Сделай 100 убийств</p>
                            </div>
                        </div>
                        <div class="achievement unlocked">
                            <div class="achievement-icon">🏆</div>
                            <div class="achievement-info">
                                <h5>Чемпион</h5>
                                <p>Выиграй 25 игр подряд</p>
                            </div>
                        </div>
                        <div class="achievement locked">
                            <div class="achievement-icon">🔥</div>
                            <div class="achievement-info">
                                <h5>Легенда</h5>
                                <p>Достигни рейтинга 2000</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Друзья -->
                <section class="profile-section">
                    <div class="section-header">
                        <h4>Друзья</h4>
                        <a href="#" class="section-link">Все друзья →</a>
                    </div>
                    <div class="friends-grid">
                        <div class="friend-card">
                            <img src="https://nmsr.nickac.dev/face/Notch" alt="Notch" class="friend-avatar">
                            <div class="friend-info">
                                <span class="friend-name">Notch</span>
                                <span class="friend-status online">Онлайн</span>
                            </div>
                        </div>
                        <div class="friend-card">
                            <img src="https://nmsr.nickac.dev/face/jeb_" alt="jeb_" class="friend-avatar">
                            <div class="friend-info">
                                <span class="friend-name">jeb_</span>
                                <span class="friend-status online">Онлайн</span>
                            </div>
                        </div>
                        <div class="friend-card">
                            <img src="https://nmsr.nickac.dev/face/Dinnerbone" alt="Dinnerbone" class="friend-avatar">
                            <div class="friend-info">
                                <span class="friend-name">Dinnerbone</span>
                                <span class="friend-status offline">Оффлайн</span>
                            </div>
                        </div>
                        <div class="friend-card">
                            <img src="https://nmsr.nickac.dev/face/CaptainSparklez" alt="CaptainSparklez" class="friend-avatar">
                            <div class="friend-info">
                                <span class="friend-name">CaptainSparklez</span>
                                <span class="friend-status offline">Оффлайн</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Последние матчи -->
                <section class="profile-section">
                    <div class="section-header">
                        <h4>Последние матчи</h4>
                    </div>
                    <div class="matches-list">
                        <div class="match-item win">
                            <div class="match-mode">BedWars</div>
                            <div class="match-result">Победа</div>
                            <div class="match-stats">12 убийств • 3 смерти • 2 кровати</div>
                            <div class="match-time">2 часа назад</div>
                        </div>
                        <div class="match-item loss">
                            <div class="match-mode">Classic Duel</div>
                            <div class="match-result">Поражение</div>
                            <div class="match-stats">8 хитов</div>
                            <div class="match-time">5 часов назад</div>
                        </div>
                        <div class="match-item win">
                            <div class="match-mode">BedWars</div>
                            <div class="match-result">Победа</div>
                            <div class="match-stats">9 убийств • 1 смерть • 1 кровать</div>
                            <div class="match-time">Вчера</div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</main>