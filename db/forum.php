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

    echo '<meta charset="utf-8"><style>body{font-family:Arial;background:#151322;color:#eee;padding:40px}a{color:#ff65ad}</style>';
    echo '<h1>Готово 🍭</h1><p>База <b>lollipopmc</b> и таблицы созданы.</p><p><a href="/lollipopmc/register/">Регистрация сайта</a> · <a href="/lollipopmc/forum/register/">Регистрация форума</a> · <a href="/lollipopmc/forum/">Форум</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<meta charset="utf-8"><pre>Ошибка установки: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre><p>Проверь app/config.php: DB_USER/DB_PASS.</p>';
}
