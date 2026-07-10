<?php
require_once __DIR__ . '/../project/config/connection.php';

if (!function_exists('db')) {
    require_once __DIR__ . '/app/db.php';
}
try {
    $server = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    require_once __DIR__ . '/app/db.php';
    $sql = [];
    $sql[] = "CREATE TABLE IF NOT EXISTS site_users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(32) UNIQUE NOT NULL, email VARCHAR(120) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL, candies INT DEFAULT 0, iris INT DEFAULT 0, privilege VARCHAR(60) DEFAULT 'Нет привилегии', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $sql[] = "CREATE TABLE IF NOT EXISTS forum_users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(32) UNIQUE NOT NULL, email VARCHAR(120) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL, role ENUM('user','youtube','gmoder','curator','developer','team') DEFAULT 'user', avatar VARCHAR(255) DEFAULT NULL, minecraft_nick VARCHAR(32) DEFAULT NULL, status VARCHAR(120) DEFAULT NULL, about TEXT NULL, signature TEXT NULL, last_seen TIMESTAMP NULL DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $sql[] = "CREATE TABLE IF NOT EXISTS forum_categories (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(120) NOT NULL, description TEXT, sort_order INT DEFAULT 0) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $sql[] = "CREATE TABLE IF NOT EXISTS forum_topics (id INT AUTO_INCREMENT PRIMARY KEY, category_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(180) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(category_id) REFERENCES forum_categories(id) ON DELETE CASCADE, FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $sql[] = "CREATE TABLE IF NOT EXISTS forum_posts (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            topic_id INT NOT NULL,
                                            user_id INT NOT NULL,
                                            body TEXT NOT NULL,
                                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                            FOREIGN KEY(topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
                                            FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS minigames_categories(
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(150) NOT NULL
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS minigames (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(150) NOT NULL,
                                            seo_text VARCHAR(200) NOT NULL,
                                            card_text VARCHAR(200) NOT NULL,
                                            description TEXT NOT NULL,
                                            icon VARCHAR(100) DEFAULT NULL,
                                            category_id INT NOT NULL,
                                            tags JSON NOT NULL,
                                            peculiarities JSON NOT NULL,
                                            advice JSON NOT NULL,
                                            similar JSON NOT NULL,
                                            path_image VARCHAR(500) DEFAULT NULL,
                                            gallery_path JSON DEFAULT NULL,
                                            INDEX idx_category (category_id),
                                            FOREIGN KEY (category_id) REFERENCES minigames_categories(id) ON DELETE CASCADE
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS rules_categories (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(250) NOT NULL
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS rules (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            title VARCHAR(350) NOT NULL,
                                            rule TEXT NOT NULL,
                                            category_id INT NOT NULL,
                                            punishments JSON DEFAULT NULL,
                                            INDEX idx_category (category_id),
                                            FOREIGN KEY (category_id) REFERENCES rules_categories(id) ON DELETE CASCADE
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";




    foreach ($sql as $q)
        db()->exec($q);
    // Миграция профилей для уже установленной базы.
    $profileCols = [
        'avatar' => "ALTER TABLE forum_users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL",
        'minecraft_nick' => "ALTER TABLE forum_users ADD COLUMN minecraft_nick VARCHAR(32) DEFAULT NULL",
        'status' => "ALTER TABLE forum_users ADD COLUMN status VARCHAR(120) DEFAULT NULL",
        'about' => "ALTER TABLE forum_users ADD COLUMN about TEXT NULL",
        'signature' => "ALTER TABLE forum_users ADD COLUMN signature TEXT NULL",
        'last_seen' => "ALTER TABLE forum_users ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL",
    ];
    foreach ($profileCols as $query) {
        try {
            db()->exec($query);
        } catch (Throwable $e) {
        }
    }
    db()->exec("CREATE TABLE IF NOT EXISTS forum_reactions (id INT AUTO_INCREMENT PRIMARY KEY, post_id INT NOT NULL, user_id INT NOT NULL, reaction VARCHAR(24) NOT NULL DEFAULT 'like', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uniq_reaction(post_id,user_id,reaction), FOREIGN KEY(post_id) REFERENCES forum_posts(id) ON DELETE CASCADE, FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS forum_bookmarks (id INT AUTO_INCREMENT PRIMARY KEY, topic_id INT NOT NULL, user_id INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uniq_bookmark(topic_id,user_id), FOREIGN KEY(topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE, FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    try {
        db()->exec("ALTER TABLE forum_topics ADD COLUMN views INT NOT NULL DEFAULT 0");
    } catch (Throwable $e) {
    }
    try {
        db()->exec("ALTER TABLE forum_topics ADD COLUMN is_pinned TINYINT(1) NOT NULL DEFAULT 0");
    } catch (Throwable $e) {
    }
    try {
        db()->exec("ALTER TABLE forum_topics ADD COLUMN is_locked TINYINT(1) NOT NULL DEFAULT 0");
    } catch (Throwable $e) {
    }
    try {
        db()->exec("ALTER TABLE forum_topics ADD COLUMN tags VARCHAR(255) DEFAULT NULL");
    } catch (Throwable $e) {
    }
    try {
        db()->exec("ALTER TABLE forum_posts ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL");
    } catch (Throwable $e) {
    }
    db()->exec("CREATE TABLE IF NOT EXISTS forum_topic_reads (id INT AUTO_INCREMENT PRIMARY KEY, topic_id INT NOT NULL, user_id INT NOT NULL, read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uniq_read(topic_id,user_id), FOREIGN KEY(topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE, FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    db()->exec("CREATE TABLE IF NOT EXISTS forum_friends (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, friend_id INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uniq_pair(user_id, friend_id), FOREIGN KEY(user_id) REFERENCES forum_users(id) ON DELETE CASCADE, FOREIGN KEY(friend_id) REFERENCES forum_users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $seedUsers = [
        ['Birsenberg', 'birsenberg@lollipopmc.local', 'team', 'forum/assets/237727.jpg', 'Birsenberg', 'Создатель проекта', 'Главный проект. Обновления, режимы, настройки и важные решения по развитию SweetLolly.', 'TEAM'],
        ['Buterbrod2019', 'buterbrod2019@lollipopmc.local', 'user', 'forum/assets/members_buterbrod2019_files/185085.jpg', 'Buterbrod2019', 'Старый игрок форума', 'Профиль восстановлен из старого форума SweetLolly. Здесь отображаются темы, сообщения, активность и данные аккаунта.', 'Игрок'],
        ['Milolika', 'milolika@lollipopmc.local', 'user', 'forum/assets/members_milolika_files/125631.jpg', 'Milolika', 'Участник форума', 'Старый профиль из архива SweetLolly форума.', 'Игрок'],
    ];
    $seedPass = password_hash('lollipop123', PASSWORD_DEFAULT);
    foreach ($seedUsers as $su) {
        $st = db()->prepare('SELECT id FROM forum_users WHERE username=? LIMIT 1');
        $st->execute([$su[0]]);
        $existing = $st->fetch();
        if ($existing) {
            $up = db()->prepare('UPDATE forum_users SET role=?, avatar=?, minecraft_nick=?, status=?, about=?, signature=? WHERE id=?');
            $up->execute([$su[2], $su[3], $su[4], $su[5], $su[6], $su[7], $existing['id']]);
        } else {
            $ins = db()->prepare('INSERT INTO forum_users(username,email,password_hash,role,avatar,minecraft_nick,status,about,signature) VALUES(?,?,?,?,?,?,?,?,?)');
            $ins->execute([$su[0], $su[1], $seedPass, $su[2], $su[3], $su[4], $su[5], $su[6], $su[7]]);
        }
    }
    $count = db()->query('SELECT COUNT(*) c FROM forum_categories')->fetch()['c'];
    if (!$count) {
        $cats = [
            ['Новости и анонсы', 'Тут будут все новости нашего сервера 👍', 10],
            ['Сообщения о багах', 'Нашли баг? Быстрее расскажите нам об этом 🛠', 20],
            ['Идеи и отзывы', 'Есть безумно интересная идея? Опишите её, а мы добавим 🙂', 30],
            ['Конкурсы и турниры', 'Считаете себя самым крутым? А сможете в конкурсе победить? 😎', 40],
            ['Помощь с чем-либо', 'Есть какие-то вопросы по игре? Спрашивайте 💬', 50],
        ];
        $st = db()->prepare('INSERT INTO forum_categories(title,description,sort_order) VALUES(?,?,?)');
        foreach ($cats as $c)
            $st->execute($c);
    }

    // Новость о косметике на форуме
    try {
        $catId = db()->query("SELECT id FROM forum_categories WHERE title='Новости и анонсы' LIMIT 1")->fetch()['id'] ?? null;
        $teamId = db()->query("SELECT id FROM forum_users WHERE username='Birsenberg' LIMIT 1")->fetch()['id'] ?? null;
        if ($catId && $teamId) {
            $exists = db()->prepare('SELECT id FROM forum_topics WHERE title=? LIMIT 1');
            $exists->execute(['😱 Косметика на SweetLolly']);
            if (!$exists->fetch()) {
                $insT = db()->prepare('INSERT INTO forum_topics(category_id,user_id,title) VALUES(?,?,?)');
                $insT->execute([$catId, $teamId, '😱 Косметика на SweetLolly']);
                $tid = db()->lastInsertId();
                $body = "Всем привееет! 😊\n\nМы, наконец-то, вводим долгожданную косметику 👑 на наш сервер! Эффекты, частицы, музыка, превращения, питомцы и кейсы теперь делают игру красивее.\n\nОткрыть меню косметики можно книгой в инвентаре или командой /ef.\n\nКейсы бывают обычные, редкие и легендарные. В редком есть шанс получить DiamondCandy, а в легендарном — EmeraldCandy 🍭.\n\nПолная новость: /lollipopmc/news/cosmetics/";
                $insP = db()->prepare('INSERT INTO forum_posts(topic_id,user_id,body) VALUES(?,?,?)');
                $insP->execute([$tid, $teamId, $body]);
            }
        }
    } catch (Throwable $e) {
    }

    try {
        $catCount = db()->query('SELECT COUNT(*) as c FROM minigames_categories')->fetch()['c'] ?? 0;

        if ($catCount == 0) {
            $categories = [
                ['name' => 'PvP'],
                ['name' => 'Выживание'],
                ['name' => 'Аркады'],
                ['name' => 'Экономика'],
                ['name' => 'Креатив'],
            ];

            $stmt = db()->prepare('INSERT INTO minigames_categories (name) VALUES (?)');
            foreach ($categories as $cat) {
                $stmt->execute([$cat['name']]);
            }

            echo "<p>✅ Добавлено " . count($categories) . " категорий мини-игр.</p>";

            $gameCount = db()->query('SELECT COUNT(*) as c FROM minigames')->fetch()['c'] ?? 0;

            if ($gameCount == 0) {
                $catMap = db()->query('SELECT name, id FROM minigames_categories')->fetchAll(PDO::FETCH_KEY_PAIR);

                $minigamesData = [
                    [
                        'name' => 'Prison',
                        'seo_text' => 'Копай шахты, продавай ресурсы, улучшай ранг и прокачивайся до топа.',
                        'card_text' => 'Космическая шахта, ранги и экономика ресурсов.',
                        'description' => 'Копай шахты, продавай ресурсы, улучшай ранг и прокачивайся до топа. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '⛏️ Prison',
                        'category_id' => $catMap['Экономика'] ?? 1,
                        'tags' => json_encode(['Шахты', 'Экономика', 'Прокачка']),
                        'peculiarities' => json_encode(['Шахты с разными ресурсами и прогрессией.', 'Экономика, продажа ресурсов и развитие баланса.', 'Ранги, цели и понятный путь развития.', 'Подходит для спокойной игры и долгих сессий.']),
                        'advice' => json_encode(['Начинай с базовых шахт и не пропускай улучшения.', 'Следи за ценой ресурсов и не носи всё ценное с собой.', 'Сохраняй прогресс и задавай вопросы на форуме.']),
                        'similar' => json_encode(['SkyBlock', 'OneBlock']),
                        'path_image' => 'prison.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'SkyBlock',
                        'seo_text' => 'Остров, фермы, развитие базы и уютная выживалка в небе.',
                        'card_text' => 'Острова, фермы и спокойное развитие.',
                        'description' => 'Остров, фермы, развитие базы и уютная выживалка в небе. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '☁️ SkyBlock',
                        'category_id' => $catMap['Выживание'] ?? 1,
                        'tags' => json_encode(['Остров', 'Фермы', 'Выживание']),
                        'peculiarities' => json_encode(['Собственный остров и постепенное развитие.', 'Фермы, ресурсы, торговля и задания.', 'Можно играть одному или с друзьями.', 'Хороший режим для креативного строительства.']),
                        'advice' => json_encode(['Сначала сделай безопасную платформу и генератор.', 'Развивай фермы постепенно, чтобы не создавать лаги.', 'Украшай остров — красивый остров приятно развивать.']),
                        'similar' => json_encode(['Prison', 'OneBlock']),
                        'path_image' => 'skyblock.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'OneBlock',
                        'seo_text' => 'Один блок, много этапов и постоянное развитие из минимального старта.',
                        'card_text' => 'Один блок, этапы и развитие с нуля.',
                        'description' => 'Один блок, много этапов и постоянное развитие из минимального старта. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🧱 OneBlock',
                        'category_id' => $catMap['Выживание'] ?? 1,
                        'tags' => json_encode(['Этапы', 'Челлендж', 'Выживание']),
                        'peculiarities' => json_encode(['Старт с одного блока и постепенное расширение.', 'Разные фазы ресурсов и мобов.', 'Челлендж для аккуратной и внимательной игры.', ' Подходит для игроков, любящих прогресс.']),
                        'advice' => json_encode(['Не стой вплотную к краю острова.', 'Сразу делай место для сундуков и мобов.', 'Расширяй платформу до сложных фаз.']),
                        'similar' => json_encode(['Prison', 'SkyBlock']),
                        'path_image' => 'oneblock.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'BedWars',
                        'seo_text' => 'Командный PvP: защищай кровать, собирай ресурсы и уничтожай базы соперников.',
                        'card_text' => 'Защищай кровать и ломай базы соперников.',
                        'description' => 'Командный PvP: защищай кровать, собирай ресурсы и уничтожай базы соперников. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🛏️ BedWars',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['PvP', 'Команды', 'Быстрые матчи']),
                        'peculiarities' => json_encode(['Командные матчи и понятная цель.', 'Улучшения, ресурсы и оборона базы.', 'Динамичные атаки и защита кровати.', 'Подходит для игры с друзьями.']),
                        'advice' => json_encode(['Не оставляй кровать без защиты.', 'Покупай улучшения команды, а не только личные вещи.', 'Следи за мостами и быстрыми атаками.']),
                        'similar' => json_encode(['SkyWars']),
                        'path_image' => 'bedwars.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'SkyWars',
                        'seo_text' => 'Быстрые сражения на островах: лутай сундуки, стройся к центру и побеждай.',
                        'card_text' => 'Лутай острова и побеждай в быстрых матчах.',
                        'description' => 'Быстрые сражения на островах: лутай сундуки, стройся к центру и побеждай. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🏝️ SkyWars',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['PvP', 'Острова', 'Сундуки']),
                        'peculiarities' => json_encode(['Короткие и напряжённые матчи.', 'Лут, острова и центральная зона.', 'Много тактики: раш, защита или контроль центра.', 'Отлично тренирует PvP и реакцию.']),
                        'advice' => json_encode(['Быстро забирай стартовый лут.', 'Центр обычно даёт преимущество.', 'Следи за игроками, которые строятся к тебе.']),
                        'similar' => json_encode(['BedWars']),
                        'path_image' => 'skywars.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'Murder Mystery',
                        'seo_text' => 'Детективная мини-игра: найди убийцу, выживи или сыграй свою роль идеально.',
                        'card_text' => 'Роли, детектив, убийца и выживание.',
                        'description' => 'Детективная мини-игра: найди убийцу, выживи или сыграй свою роль идеально. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🔎 Murder Mystery',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Детектив', 'Роли', 'Аркады']),
                        'peculiarities' => json_encode(['Роли с разными задачами.', 'Атмосферные карты и быстрые раунды.', 'Нужно наблюдать, думать и не палиться.', 'Весело играть компанией.']),
                        'advice' => json_encode(['Запоминай подозрительное поведение.', 'Не бегай по одиночке без причины.', 'Если ты убийца — не действуй слишком очевидно.']),
                        'similar' => json_encode(['TNT Run', 'BuildBattle', 'BlockParty', 'Arcades']),
                        'path_image' => 'murdermystery.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'TNT Run',
                        'seo_text' => 'Беги по исчезающим блокам, не падай и останься последним на арене.',
                        'card_text' => 'Беги по исчезающим блокам до победы.',
                        'description' => 'Беги по исчезающим блокам, не падай и останься последним на арене. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '💣 TNT Run',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Паркур', 'Аркады', 'Реакция']),
                        'peculiarities' => json_encode(['Простые правила и быстрый старт.', 'Арены с исчезающими блоками.', 'Проверка реакции и маршрута.', 'Идеально для коротких сессий.']),
                        'advice' => json_encode(['Не прыгай без необходимости.', 'Старайся резать карту экономно.', 'Держи дистанцию от толпы.']),
                        'similar' => json_encode(['Murder Mystery', 'BuildBattle', 'BlockParty', 'Arcades']),
                        'path_image' => 'tntrun.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'BuildBattle',
                        'seo_text' => 'Строй по теме, голосуй честно и показывай креатив.',
                        'card_text' => 'Строй по теме и показывай креатив.',
                        'description' => 'Строй по теме, голосуй честно и показывай креатив. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🎨 BuildBattle',
                        'category_id' => $catMap['Креатив'] ?? 1,
                        'tags' => json_encode(['Стройка', 'Креатив', 'Конкурс']),
                        'peculiarities' => json_encode(['Темы для быстрого строительства.', 'Голосование игроков.', 'Развитие креатива и скорости.', 'Подходит для спокойной игры.']),
                        'advice' => json_encode(['Сначала делай форму, потом детали.', 'Используй палитру из 3–5 цветов.', 'Голосуй честно — так режим интереснее.']),
                        'similar' => json_encode(['TNT Run', 'Murder Mystery', 'BlockParty', 'Arcades']),
                        'path_image' => 'buildbattle.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'BlockParty',
                        'seo_text' => 'Музыкальная аркада: найди нужный цвет и успей встать на него.',
                        'card_text' => 'Музыка, цвета и реакция.',
                        'description' => 'Музыкальная аркада: найди нужный цвет и успей встать на него. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🟩 BlockParty',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Музыка', 'Цвета', 'Реакция']),
                        'peculiarities' => json_encode(['Быстрые раунды и простые правила.', 'Цветовые задания и темп.', 'Весёлый режим для компании.', 'Отлично подходит для отдыха.']),
                        'advice' => json_encode(['Держи камеру так, чтобы видеть больше пола.', 'Учись быстро отличать похожие цвета.', 'Не стой у края площадки.']),
                        'similar' => json_encode(['TNT Run', 'Murder Mystery', 'BuildBattle', 'Arcades']),
                        'path_image' => 'blockparty.jpg',
                        'gallery_path' => json_encode([])
                    ],
                    [
                        'name' => 'Arcades',
                        'seo_text' => 'Сборник лёгких мини-игр для быстрых матчей и отдыха между режимами.',
                        'card_text' => 'Набор быстрых мини-игр для отдыха.',
                        'description' => 'Сборник лёгких мини-игр для быстрых матчей и отдыха между режимами. Этот раздел обновлён: теперь описание читается нормально, картинки не ломают вёрстку, а блоки одинаково хорошо выглядят на ПК и телефоне.',
                        'icon' => '🎮 Arcades',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Мини-игры', 'Фан', 'Быстро']),
                        'peculiarities' => json_encode(['Разные короткие активности.', 'Можно играть без долгой подготовки.', 'Подходит для новичков и компании.', 'Хороший выбор, когда хочется разнообразия.']),
                        'advice' => json_encode(['Пробуй разные аркады, чтобы найти любимую.', 'Не бойся проигрывать — режим для фана.', 'Зови друзей, так веселее.']),
                        'similar' => json_encode(['TNT Run', 'Murder Mystery', 'BuildBattle', 'BlockParty']),
                        'path_image' => 'arcades.jpg',
                        'gallery_path' => json_encode([])
                    ],

                ];

                $stmt = db()->prepare("INSERT INTO minigames 
                    (name, seo_text, card_text, description, icon, category_id, tags, peculiarities, advice, similar, path_image, gallery_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                foreach ($minigamesData as $game) {
                    $stmt->execute([
                        $game['name'],
                        $game['seo_text'],
                        $game['card_text'],
                        $game['description'],
                        $game['icon'],
                        $game['category_id'],
                        $game['tags'],
                        $game['peculiarities'],
                        $game['advice'],
                        $game['similar'],
                        $game['path_image'],
                        $game['gallery_path']
                    ]);
                }
                echo "<p>✅ Добавлено " . count($minigamesData) . " мини-игр.</p>";
            }
        }
    } catch (Throwable $e) {
        echo "<p style='color:orange'>⚠️ Ошибка сидирования мини-игр: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    //RULES MIGRATION
    try {

        $ruleCategoriesCount = db()->query('SELECT COUNT(*) as c FROM rules_categories')->fetch()['c'] ?? 0;

        if ($ruleCategoriesCount == 0) {
            $ruleCategories = [
                ['name' => 'Общие правила'],
                ['name' => 'Аккаунт и безопасность'],
                ['name' => 'Игровой процесс'],
                ['name' => 'Экономика и обмены'],
                ['name' => 'Постройки и территория'],
                ['name' => 'Мини-игры и режимы'],
                ['name' => 'Жалобы и обжалования'],
            ];

            $stmt = db()->prepare('INSERT INTO rules_categories (name) VALUES (?)');
            foreach ($ruleCategories as $cat) {
                $stmt->execute([$cat['name']]);
            }

            echo "<p>✅ Добавлено " . count($ruleCategories) . " категорий правил.</p>";

            $ruleCount = db()->query('SELECT COUNT(*) as c FROM rules')->fetch()['c'] ?? 0;

            if ($ruleCount == 0) {
                $rulMap = db()->query('SELECT name, id FROM rules_categories')->fetchAll(PDO::FETCH_KEY_PAIR);

                $rulesData = [
                    [
                        'title' => 'Уважайте игроков и администрацию',
                        'rule' => 'Запрещены оскорбления, травля, провокации, угрозы, дискриминация и токсичное поведение в любом виде.',
                        'category_id' => $rulMap['Общие правила'] ?? 1,
                        'punishments' => json_encode(['Мут', 'Бан'])
                    ],
                    [
                        'title' => 'Запрещён спам и флуд',
                        'rule' => 'Не повторяйте одинаковые сообщения, не злоупотребляйте капсом, символами, рекламой и бессмысленными сообщениями.',
                        'category_id' => $rulMap['Общие правила'] ?? 1,
                        'punishments' => json_encode(['Предупреждение', 'Мут'])
                    ],
                    ['title' => 'Никакой рекламы', 'rule' => 'Запрещена реклама сторонних серверов, сайтов, Discord/VK-групп и любых проектов без разрешения администрации.', 'category_id' => $rulMap['Общие правила'] ?? 1, 'punishments' => json_encode(['Мут', 'Бан'])],
                    ['title' => 'Не выдавайте себя за администрацию', 'rule' => 'Запрещены ники, скины, префиксы и сообщения, которые вводят игроков в заблуждение.', 'category_id' => $rulMap['Общие правила'] ?? 1, 'punishments' => json_encode(['Бан', 'Смена никнейма'])],
                    ['title' => 'Администрация решает спорные ситуации', 'rule' => 'Если правило не описывает конкретный случай, администрация может принять решение по смыслу правил и интересам проекта.', 'category_id' => $rulMap['Общие правила'] ?? 1, 'punishments' => json_encode(['Индивидуально'])],
                    ['title' => 'Отвечаете за свой аккаунт', 'rule' => 'Все действия с вашего аккаунта считаются вашими. Не передавайте пароль другим людям.', 'category_id' => $rulMap['Аккаунт и безопасность'] ?? 1, 'punishments' => json_encode(['Наказание аккаунтов'])],
                    ['title' => 'Мультиаккаунты ограничены', 'rule' => 'Запрещено использовать дополнительные аккаунты для обхода наказаний, фарма, голосований, конкурсов или преимуществ.', 'category_id' => $rulMap['Аккаунт и безопасность'] ?? 1, 'punishments' => json_encode(['Бан твинка', 'Бан основны'])],
                    ['title' => 'Запрещена продажа аккаунтов', 'rule' => 'Нельзя продавать, обменивать, передавать аккаунты, привилегии и внутриигровые ценности за реальные деньги вне правил проекта.', 'category_id' => $rulMap['Аккаунт и безопасность'] ?? 1, 'punishments' => json_encode(['Бан'])],
                    ['title' => 'Запрещены читы и модификации', 'rule' => 'Нельзя использовать читы, autoclicker, x-ray, fly, reach, kill aura, macro и любые модификации, дающие преимущество.', 'category_id' => $rulMap['Игровой процесс'] ?? 1, 'punishments' => json_encode(['Бан'])],
                    ['title' => 'Не используйте баги', 'rule' => 'Если нашли баг — сообщите на форум или администрации. Использование бага ради выгоды запрещено.', 'category_id' => $rulMap['Игровой процесс'] ?? 1, 'punishments' => json_encode(['Откат', 'Бан'])],
                    ['title' => 'Запрещено мешать игровому процессу', 'rule' => 'Нельзя намеренно ломать игру другим: блокировать проходы, мешать ивентам, сливать союзников, портить командную игру.', 'category_id' => $rulMap['Игровой процесс'] ?? 1, 'punishments' => json_encode(['Кик', 'Бан'])],
                    ['title' => 'Запрещены лаг-механизмы', 'rule' => 'Не создавайте механизмы, фермы, постройки и действия, вызывающие лаги сервера или клиента.', 'category_id' => $rulMap['Игровой процесс'] ?? 1, 'punishments' => json_encode(['Удаление', 'Бан'])],
                    ['title' => 'Обмены — на ваш риск', 'rule' => 'Администрация помогает только если есть доказательства: скриншоты, видео, ник, дата и описание ситуации.', 'category_id' => $rulMap['Экономика и обмены'] ?? 1, 'punishments' => json_encode(['По доказательствам'])],
                    ['title' => 'Запрещён обман игроков', 'rule' => 'Скам, фейковые розыгрыши, подмена условий сделки и намеренный обман запрещены.', 'category_id' => $rulMap['Экономика и обмены'] ?? 1, 'punishments' => json_encode(['Возврат', 'Бан'])],
                    ['title' => 'Запрещена продажа за реальные деньги', 'rule' => 'Внутриигровые предметы, валюту и услуги нельзя продавать за реальные деньги вне официальных способов проекта.', 'category_id' => $rulMap['Экономика и обмены'] ?? 1, 'punishments' => json_encode(['Бан'])],
                    ['title' => 'Не ломайте чужое', 'rule' => 'Гриферство, кражи, порча построек и обход приватных зон запрещены, если режим не предполагает обратное.', 'category_id' => $rulMap['Постройки и территория'] ?? 1, 'punishments' => json_encode(['Откат', 'Бан'])],
                    ['title' => 'Запрещены неприемлемые постройки', 'rule' => 'Нельзя строить оскорбительные, NSFW, политические, экстремистские и провокационные объекты.', 'category_id' => $rulMap['Постройки и территория'] ?? 1, 'punishments' => json_encode(['Удаление', 'Бан'])],
                    ['title' => 'Следите за нагрузкой', 'rule' => 'Большие фермы и механизмы должны быть оптимизированы. Администрация может ограничить или удалить лаг-механизм.', 'category_id' => $rulMap['Постройки и территория'] ?? 1, 'punishments' => json_encode(['Предупреждение', 'Удаление'])],
                    ['title' => 'Играйте честно', 'rule' => 'Тиминг в solo-режимах, слив каток, намеренные поражения и договорные матчи запрещены.', 'category_id' => $rulMap['Мини-игры и режимы'] ?? 1, 'punishments' => json_encode(['Бан по режиму'])],
                    ['title' => 'Не портите командную игру', 'rule' => 'Запрещено мешать своей команде, раскрывать позиции, ломать защиту союзников или намеренно проигрывать.', 'category_id' => $rulMap['Мини-игры и режимы'] ?? 1, 'punishments' => json_encode(['Кик', 'Бан'])],
                    ['title' => 'Уважайте особенности режима', 'rule' => 'На каждом режиме могут быть дополнительные правила. Если страница режима указывает ограничения — они считаются частью правил.', 'category_id' => $rulMap['Мини-игры и режимы'] ?? 1, 'punishments' => json_encode(['Индивидуально'])],
                    ['title' => 'Пишите жалобы с доказательствами', 'rule' => 'Укажите ник, дату, режим, описание и приложите скриншоты или видео. Без доказательств жалоба может быть закрыта.', 'category_id' => $rulMap['Жалобы и обжалования'] ?? 1, 'punishments' => json_encode(['Рассмотрение по фактам'])],
                    ['title' => 'Не создавайте дубликаты', 'rule' => 'Одна ситуация — одна тема. Повторные одинаковые обращения замедляют рассмотрение.', 'category_id' => $rulMap['Жалобы и обжалования'] ?? 1, 'punishments' => json_encode(['Закрытие темы'])],
                    ['title' => 'Обжалование должно быть спокойным', 'rule' => 'Оскорбления администрации и давление не помогают. Пишите по фактам и уважительно.', 'category_id' => $rulMap['Жалобы и обжалования'] ?? 1, 'punishments' => json_encode(['Отказ', 'Мут'])]
                ];

                $stmt = db()->prepare("INSERT INTO rules 
                    (title, rule, category_id, punishments) 
                    VALUES (?, ?, ?, ?)");

                foreach ($rulesData as $rule) {
                    $stmt->execute([
                        $rule['title'],
                        $rule['rule'],
                        $rule['category_id'],
                        $rule['punishments'],
                    ]);
                }
                echo "<p>✅ Добавлено " . count($rulesData) . " правил.</p>";
            }
        }
    } catch (Throwable $e) {
        echo "<p style='color: orange'>⚠️ Ошибка синдирования правил: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    echo '<meta charset="utf-8"><style>body{font-family:Arial;background:#151322;color:#eee;padding:40px}a{color:#ff65ad}</style>';
    echo '<h1>Готово 🍭</h1><p>База <b>lollipopmc</b> и таблицы созданы.</p><p><a href="/lollipopmc/register/">Регистрация сайта</a> · <a href="/lollipopmc/forum/register/">Регистрация форума</a> · <a href="/lollipopmc/forum/">Форум</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<meta charset="utf-8"><pre>Ошибка установки: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre><p>Проверь app/config.php: DB_USER/DB_PASS.</p>';
}
