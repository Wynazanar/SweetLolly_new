<?php

/**
 * Миграция / сидер БД.
 * Запуск: php database/migration.php
 * или через браузер: /database/migration.php
 */

$basePath = dirname(__DIR__);

require $basePath . '/vendor/autoload.php';

// .env
$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->safeLoad();

// Конфиг БД → константы (как в public/index.php)
$db = require $basePath . '/app/Config/database.php';
if (!defined('DB_HOST')) define('DB_HOST', $db['host']);
if (!defined('DB_USER')) define('DB_USER', $db['username']);
if (!defined('DB_PASS')) define('DB_PASS', $db['password']);
if (!defined('DB_NAME')) define('DB_NAME', $db['database']);

if (!function_exists('db')) {
    require_once __DIR__ . '/app/db.php';
}

try {
    // Без dbname — чтобы создать базу, если её ещё нет
    $server = new PDO(
        'mysql:host=' . DB_HOST . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

    require_once __DIR__ . '/app/db.php';
    $sql = [];

    $sql[] = "CREATE TABLE IF NOT EXISTS minigames_categories(
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(150) NOT NULL
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS minigames (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(150) NOT NULL,
                                            seo_text VARCHAR(250) NOT NULL,
                                            card_text VARCHAR(250) NOT NULL,
                                            description TEXT NOT NULL,
                                            category_id INT NOT NULL,
                                            tags JSON NOT NULL,
                                            peculiarities JSON NOT NULL,
                                            advice JSON NOT NULL,
                                            similar JSON NOT NULL,
                                            submodes JSON DEFAULT NULL,
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

    $sql[] = "CREATE TABLE IF NOT EXISTS subs(
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            name VARCHAR(350) NOT NULL,
                                            priceMonth DECIMAL(7,2) NOT NULL,
                                            priceAlways DECIMAL(7,2) NOT NULL,
                                            discount INT DEFAULT 0,
                                            path_image VARCHAR(500) NOT NULL
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql[] = "CREATE TABLE IF NOT EXISTS site_notifications (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            player_name VARCHAR(32) NOT NULL,
                                            event_type VARCHAR(64) NOT NULL,
                                            payload JSON NULL,
                                            is_read TINYINT(1) NOT NULL DEFAULT 0,
                                            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                            INDEX idx_player_read (player_name, is_read),
                                            INDEX idx_player_id (player_name, id)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";



    foreach ($sql as $q)
        db()->exec($q);
    

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
                        'name' => 'BedWars',
                        'seo_text' => 'Командный PvP: защищай кровать, собирай ресурсы и уничтожай базы соперников.',
                        'card_text' => 'Защищай кровать и ломай базы соперников.',
                        'description' => 'BedWars — командный режим, в котором ваша кровать — это жизнь. Собирайте ресурсы на островах, улучшайте защиту, стройте мосты к врагам и уничтожайте их базы. Пока кровать цела, вы можете возрождаться. Как только её сломают — у вас остаётся только одна жизнь. Идеально для игры с друзьями и быстрых напряжённых матчей.',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['PvP', 'Команды', 'Быстрые матчи']),
                        'peculiarities' => json_encode([
                            'Собирай ресурсы на своём острове и в центре карты.',
                            'Защищай кровать блоками и улучшениями.',
                            'Строй мосты и атакуй базы соперников.',
                            'После уничтожения кровати — только одна жизнь.'
                        ]),
                        'advice' => json_encode([
                            'В начале раунда сразу поставь защиту на кровать.',
                            'Не трать всё железо только на себя — улучшай команду.',
                            'Следи за мостами: по ним часто приходят раши.',
                            'После первого раунда запомни расположение генераторов.',
                            'Играй ближе к команде, соло-раш часто заканчивается плохо.'
                        ]),
                        'similar' => json_encode(['SkyWars', 'Duels', 'The Bridge']),
                        'submodes' => json_encode(['Solo', 'Doubles', '3v3v3v3', '4v4v4v4', '4v4']),
                        'path_image' => 'bedwars.png',
                        'gallery_path' => json_encode([
                            'bedwars-1.png', 'bedwars-2.png', 'bedwars-3.png', 'bedwars-4.png',
                            'bedwars-5.png', 'bedwars-6.png', 'bedwars-7.png', 'bedwars-8.png',
                            'bedwars-9.png', 'bedwars-10.png'
                            ])
                    ],
                    [
                        'name' => 'SkyWars',
                        'seo_text' => 'Быстрые сражения на островах: лутай сундуки, стройся к центру и побеждай.',
                        'card_text' => 'Выживи и стань последним на своей арене.',
                        'description' => 'SkyWars — динамичный PvP-режим на парящих островах. В начале матча вы появляетесь на своём острове со стартовым лутом. Собирайте ресурсы из сундуков, стройтесь к центру или к другим игрокам и сражайтесь до последнего. Короткий матч, высокая ставка и много тактики: раш, контроль середины или осторожная оборона.',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['PvP', 'Острова', 'Сундуки']),
                        'peculiarities' => json_encode([
                            'Стартуешь на своём острове с базовым лутом.',
                            'Лутай сундуки и стройся к центру или к врагам.',
                            'Центральная зона часто решает исход матча.',
                            'Последний выживший побеждает.'
                        ]),
                        'advice' => json_encode([
                            'Сразу забирай лут с острова — не стой на месте.',
                            'Центр обычно даёт лучшее снаряжение.',
                            'Слушай звуки блоков и шагов — так легче находить игроков.',
                            'Не прыгай в бой без щита или зелий, если есть выбор.',
                            'На первых матчах играй аккуратнее и учись строить.'
                        ]),
                        'similar' => json_encode(['BedWars', 'Duels']),
                        'submodes' => json_encode(['Solo', 'Teams']),
                        'path_image' => 'skywars.png',
                        'gallery_path' => json_encode(['skywars-1.png', 'skywars-2.png', 'skywars-3.png'])
                    ],
                    [
                        'name' => 'TNT Run',
                        'seo_text' => 'Беги по исчезающим блокам, не падай и останься последним на арене.',
                        'card_text' => 'Беги, прыгай, выживай и не взорвись первым.',
                        'description' => 'TNT Run — аркадный режим на скорость и реакцию. Под ногами постоянно исчезают блоки: нужно бежать, прыгать и выбирать безопасный маршрут. Чем дольше ты на арене — тем выше шанс победы. Простые правила, короткий матч и отличный способ размяться между серьёзными режимами.',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Паркур', 'Аркады', 'Реакция']),
                        'peculiarities' => json_encode([
                            'Блоки под ногами исчезают через секунду.',
                            'Нужно постоянно двигаться и выбирать путь.',
                            'Побеждает тот, кто останется последним.',
                            'Идеально для коротких сессий.'
                        ]),
                        'advice' => json_encode([
                            'Не прыгай без необходимости — экономия движений важна.',
                            'Держись подальше от толпы в начале.',
                            'Старайся резать карту по краям, когда центр забит.',
                            'Смотри вперёд на 2–3 блока, а не только под ноги.',
                            'Если застрял — лучше упасть раньше, чем толкаться в куче.'
                        ]),
                        'similar' => json_encode(['BlockParty', 'Murder Mystery', 'Arcades']),
                        'submodes' => json_encode([]),
                        'path_image' => 'tntrun.png',
                        'gallery_path' => json_encode(['tntrun-1.png', 'tntrun-2.png'])
                    ],
                    [
                        'name' => 'Arcades',
                        'seo_text' => 'Сборник лёгких мини-игр для быстрых матчей и отдыха между режимами.',
                        'card_text' => 'Набор быстрых мини-игр в одном режиме.',
                        'description' => 'Arcades — хаб с несколькими короткими и весёлыми мини-играми. Здесь можно быстро переключаться между разными активностями без долгой подготовки. Подходит и новичкам, и компании друзей, когда хочется просто пофаниться между серьёзными режимами.',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Мини-игры', 'Фан', 'Быстро']),
                        'peculiarities' => json_encode([
                            'Несколько разных коротких активностей.',
                            'Можно играть без долгой подготовки.',
                            'Подходит для новичков и компании.',
                            'Хороший выбор, когда хочется разнообразия.'
                        ]),
                        'advice' => json_encode([
                            'Пробуй разные аркады, чтобы найти любимую.',
                            'Не бойся проигрывать — режим для фана.',
                            'Зови друзей, так веселее.',
                            'Между матчами можно быстро переключиться на другой режим.'
                        ]),
                        'similar' => json_encode(['TNT Run', 'Murder Mystery', 'BuildBattle', 'BlockParty']),
                        'submodes' => json_encode(['Разные мини-игры внутри хаба']),
                        'path_image' => 'arcades.png',
                        'gallery_path' => json_encode(['arcades-1.png', 'arcades-2.png'])
                    ],
                    [
                        'name' => 'BuildBattle',
                        'seo_text' => 'Строй по теме, голосуй честно и показывай креатив.',
                        'card_text' => 'Строй по теме и показывай своё воображение.',
                        'description' => 'BuildBattle — креативный конкурс на скорость и фантазию. Вам дают тему, ограниченное время и полный набор блоков. Стройте то, что приходит в голову, потом голосуйте за чужие работы. Отличный способ потренировать вкус, скорость и чувство композиции.',
                        'category_id' => $catMap['Креатив'] ?? 1,
                        'tags' => json_encode(['Стройка', 'Креатив', 'Конкурс']),
                        'peculiarities' => json_encode([
                            'Тема и таймер на строительство.',
                            'Голосование игроков за лучшие постройки.',
                            'Развивает креатив и скорость работы.',
                            'Подходит для спокойной игры.'
                        ]),
                        'advice' => json_encode([
                            'Сначала сделай общую форму, потом детали.',
                            'Используй палитру из 3–5 цветов — так аккуратнее.',
                            'Не забывай про фон и землю под постройкой.',
                            'Голосуй честно — так режим интереснее всем.',
                            'Если тема сложная — упрости идею, но сделай чисто.'
                        ]),
                        'similar' => json_encode(['BlockParty', 'Arcades', 'Murder Mystery']),
                        'submodes' => json_encode(['Solo', 'Teams']),
                        'path_image' => 'buildbattle.png',
                        'gallery_path' => json_encode(['buildbattle-1.png', 'buildbattle-2.png'])
                    ],
                    [
                        'name' => 'BlockParty',
                        'seo_text' => 'Музыкальная аркада: найди нужный цвет и успей встать на него.',
                        'card_text' => 'Танцуй на блоках под музыку и останься в игре.',
                        'description' => 'BlockParty — весёлая музыкальная аркада. На полу появляются цвета, а на экране — нужный оттенок. Нужно успеть встать на правильный блок, пока остальные не исчезли. Темп растёт, цвета путаются, и в конце остаются самые быстрые и внимательные.',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Музыка', 'Цвета', 'Реакция']),
                        'peculiarities' => json_encode([
                            'Быстрые раунды и простые правила.',
                            'Цветовые задания под музыку.',
                            'Весёлый режим для компании.',
                            'Отлично подходит для отдыха.'
                        ]),
                        'advice' => json_encode([
                            'Держи камеру так, чтобы видеть больше пола.',
                            'Учись быстро отличать похожие цвета.',
                            'Не стой у края площадки.',
                            'В конце раундов двигайся заранее, а не в последний момент.',
                            'Если запутался — лучше выбрать ближайший безопасный цвет.'
                        ]),
                        'similar' => json_encode(['TNT Run', 'Murder Mystery', 'BuildBattle', 'Arcades']),
                        'submodes' => json_encode([]),
                        'path_image' => 'blockparty.png',
                        'gallery_path' => json_encode(['blockparty-1.png', 'blockparty-2.png'])
                    ],
                    [
                        'name' => 'Murder Mystery',
                        'seo_text' => 'Детективная мини-игра: найди убийцу, выживи или сыграй свою роль идеально.',
                        'card_text' => 'Роли, детектив, убийца и выживание.',
                        'description' => 'Murder Mystery — режим с ролями. Кто-то становится убийцей, кто-то детективом, остальные — мирными жителями. Убийца должен тихо устранять игроков, детектив — найти его по уликам, а остальные — просто выжить. Атмосфера, наблюдение и блеф делают каждый раунд уникальным.',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Детектив', 'Роли', 'Аркады']),
                        'peculiarities' => json_encode([
                            'Роли с разными задачами.',
                            'Атмосферные карты и быстрые раунды.',
                            'Нужно наблюдать, думать и не палиться.',
                            'Весело играть компанией.'
                        ]),
                        'advice' => json_encode([
                            'Запоминай подозрительное поведение и маршруты.',
                            'Не бегай по одиночке без причины.',
                            'Если ты убийца — не действуй слишком очевидно.',
                            'Детективу важно не раскрываться раньше времени.',
                            'Слушай шаги и смотри, кто появляется рядом с телами.'
                        ]),
                        'similar' => json_encode(['TNT Run', 'BuildBattle', 'BlockParty', 'Arcades']),
                        'submodes' => json_encode(['Classic', 'Infection']),
                        'path_image' => 'murdermystery.png',
                        'gallery_path' => json_encode(['murdermystery-1.png', 'murdermystery-2.png'])
                    ],
                    [
                        'name' => 'Prison',
                        'seo_text' => 'Копай шахты, продавай ресурсы, улучшай ранг и прокачивайся до топа.',
                        'card_text' => 'Шахты, ранги и экономика ресурсов.',
                        'description' => 'Prison — экономический режим с прогрессией. Копайте в шахтах, продавайте ресурсы, повышайте ранг и открывайте новые уровни. Здесь важны планирование, апгрейды и понимание экономики. Подходит для долгих спокойных сессий и тех, кто любит постепенный рост.',
                        'category_id' => $catMap['Экономика'] ?? 1,
                        'tags' => json_encode(['Шахты', 'Экономика', 'Прокачка']),
                        'peculiarities' => json_encode([
                            'Шахты с разными ресурсами и прогрессией.',
                            'Продажа ресурсов и развитие баланса.',
                            'Ранги, цели и понятный путь развития.',
                            'Подходит для спокойной игры и долгих сессий.'
                        ]),
                        'advice' => json_encode([
                            'Начинай с базовых шахт и не пропускай улучшения.',
                            'Следи за ценой ресурсов и не носи всё ценное с собой.',
                            'Вкладывайся в кирку и рюкзак в первую очередь.',
                            'Задавай вопросы на форуме, если застрял на ранге.',
                            'Не трать все деньги сразу — оставляй запас на апгрейды.'
                        ]),
                        'similar' => json_encode(['SkyBlock', 'OneBlock']),
                        'submodes' => json_encode([]),
                        'path_image' => 'prison.png',
                        'gallery_path' => json_encode(['prison-1.png', 'prison-2.png'])
                    ],
                    [
                        'name' => 'SkyBlock',
                        'seo_text' => 'Остров, фермы, развитие базы и уютная выживалка в небе.',
                        'card_text' => 'Острова, фермы, спокойное развитие и собственные правила.',
                        'description' => 'SkyBlock — классическая выживалка на собственном острове в небе. Начинаете с малого, строите фермы, развиваете базу, торгуете и выполняете задания. Можно играть одному или с друзьями. Режим даёт свободу и спокойный темп без постоянного PvP.',
                        'category_id' => $catMap['Выживание'] ?? 1,
                        'tags' => json_encode(['Остров', 'Фермы', 'Выживание']),
                        'peculiarities' => json_encode([
                            'Собственный остров и постепенное развитие.',
                            'Фермы, ресурсы, торговля и задания.',
                            'Можно играть одному или с друзьями.',
                            'Хороший режим для креативного строительства.'
                        ]),
                        'advice' => json_encode([
                            'Сначала сделай безопасную платформу и генератор.',
                            'Развивай фермы постепенно, чтобы не создавать лаги.',
                            'Украшай остров — красивый остров приятно развивать.',
                            'Не храни все ресурсы в одном сундуке.',
                            'Смотри гайды по первым часам, если запутался.'
                        ]),
                        'similar' => json_encode(['Prison', 'OneBlock']),
                        'submodes' => json_encode([]),
                        'path_image' => 'skyblock.png',
                        'gallery_path' => json_encode(['skyblock-1.png', 'skyblock-2.png', 'skyblock-3.png'])
                    ],
                    [
                        'name' => 'OneBlock',
                        'seo_text' => 'Один блок, много этапов и постоянное развитие из минимального старта.',
                        'card_text' => 'Один начальный блок, этапы и развитие с нуля.',
                        'description' => 'OneBlock — челлендж-режим, где всё начинается с одного блока. Вы ломаете его снова и снова, получаете новые ресурсы, проходите фазы и постепенно расширяете свой островок. Нужны аккуратность, терпение и понимание прогрессии. Отличный выбор для тех, кто любит начинать с нуля.',
                        'category_id' => $catMap['Выживание'] ?? 1,
                        'tags' => json_encode(['Этапы', 'Испытание', 'Остров']),
                        'peculiarities' => json_encode([
                            'Старт с одного блока и постепенное расширение.',
                            'Разные фазы ресурсов и мобов.',
                            'Челлендж для аккуратной и внимательной игры.',
                            'Подходит для игроков, любящих прогресс.'
                        ]),
                        'advice' => json_encode([
                            'Не стой вплотную к краю острова.',
                            'Сразу делай место для сундуков и мобов.',
                            'Расширяй платформу до сложных фаз.',
                            'Сохраняй редкие блоки и семена.',
                            'На первых этапах играй максимально осторожно.'
                        ]),
                        'similar' => json_encode(['Prison', 'SkyBlock']),
                        'submodes' => json_encode([]),
                        'path_image' => 'oneblock.png',
                        'gallery_path' => json_encode(['oneblock-1.png', 'oneblock-2.png'])
                    ],
                    [
                        'name' => 'Duels',
                        'seo_text' => 'Дуэли на любой вкус: Classic, UHC, Bow, Sumo, Boxing, The Bridge и другие.',
                        'card_text' => '1v1 и командные дуэли в разных форматах.',
                        'description' => 'Duels — режим чистого навыка. Выбирайте формат и сражайтесь один на один или небольшими командами. Classic, UHC, Bow, Boxing, Sumo, The Bridge — у каждого свои правила и темп. Идеально для тренировки PvP, разминки и выяснения, кто сильнее в конкретном стиле.',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['Дуэли', 'Тренировка', 'PvP']),
                        'peculiarities' => json_encode([
                            'Много разных форматов дуэлей.',
                            'Быстрые матчи без долгой подготовки.',
                            'Отлично прокачивает механику и реакцию.',
                            'Можно играть ranked и обычные матчи.'
                        ]),
                        'advice' => json_encode([
                            'Начни с Classic, чтобы понять базовую механику.',
                            'В Bow следи за позицией и не стой на месте.',
                            'В Sumo важны толчки и контроль края.',
                            'The Bridge — это скорость строительства и точность ударов.',
                            'Смотри реплеи сильных игроков своего любимого режима.'
                        ]),
                        'similar' => json_encode(['BedWars', 'SkyWars']),
                        'submodes' => json_encode(['Classic', 'UHC', 'Bow', 'Boxing', 'Sumo', 'The Bridge']),
                        'path_image' => 'duels.png',
                        'gallery_path' => json_encode(['duels-1.png', 'duels-2.png', 'duels-3.png'])
                    ],
                    [
                        'name' => 'CS:GO',
                        'seo_text' => 'Тактические раунды в стиле Counter-Strike: покупки, позиции и командная игра.',
                        'card_text' => 'Раунды, оружие и тактика в стиле CS.',
                        'description' => 'CS:GO-режим переносит атмосферу классических тактических шутеров в Minecraft. Покупайте оружие, занимайте позиции, работайте в команде и выигрывайте раунды. Здесь важны не только аим, но и понимание экономики раунда, таймингов и коммуникации.',
                        'category_id' => $catMap['PvP'] ?? 1,
                        'tags' => json_encode(['Тактика', 'Команды', 'Раунды']),
                        'peculiarities' => json_encode([
                            'Покупка оружия и снаряжения каждый раунд.',
                            'Атака и оборона точек.',
                            'Экономика раунда влияет на силу команды.',
                            'Нужна координация и роли в команде.'
                        ]),
                        'advice' => json_encode([
                            'Не покупай всё подряд — следи за деньгами команды.',
                            'Учись базовым позициям на каждой карте.',
                            'Слушай шаги и сообщай информацию тиммейтам.',
                            'В пистолетных раундах играй аккуратнее.',
                            'После поражения не форсируй — лучше эко-раунд.'
                        ]),
                        'similar' => json_encode(['Zombies', 'BedWars', 'Duels']),
                        'submodes' => json_encode(['Defuse', 'Deathmatch']),
                        'path_image' => 'csgo.png',
                        'gallery_path' => json_encode(['csgo-1.png', 'csgo-2.png'])
                    ],
                    [
                        'name' => 'Zombies',
                        'seo_text' => 'Выживи против волн зомби: открывай двери, покупай оружие и держись вместе.',
                        'card_text' => 'Волны зомби, оружие и кооперативное выживание.',
                        'description' => 'Zombies — кооперативный режим на выживание. Зомби идут волнами, вы зарабатываете очки, открываете новые зоны карты, покупаете оружие и перки. Чем дальше — тем сложнее. Победа зависит от командной игры, правильного выбора позиций и грамотной траты очков.',
                        'category_id' => $catMap['Аркады'] ?? 1,
                        'tags' => json_encode(['Кооп', 'Волны', 'Выживание']),
                        'peculiarities' => json_encode([
                            'Волны зомби с растущей сложностью.',
                            'Открытие дверей и новых зон карты.',
                            'Покупка оружия и улучшений за очки.',
                            'Командная игра решает больше, чем соло-скилл.'
                        ]),
                        'advice' => json_encode([
                            'В первых волнах копи очки, не трать всё сразу.',
                            'Держитесь вместе — разбегаться опасно.',
                            'Оставляйте пути отхода при открытии новых зон.',
                            'Тренируйте перезарядку и смену оружия заранее.',
                            'На высоких волнах приоритет — выживание, а не киллы.'
                        ]),
                        'similar' => json_encode(['CS:GO', 'Arcades', 'Murder Mystery']),
                        'submodes' => json_encode(['Classic', 'Hardcore']),
                        'path_image' => 'zombies.png',
                        'gallery_path' => json_encode(['zombies-1.png', 'zombies-2.png'])
                    ],
                ];

                $stmt = db()->prepare("INSERT INTO minigames 
                    (name, seo_text, card_text, description, category_id, tags, peculiarities, advice, similar, submodes, path_image, gallery_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                foreach ($minigamesData as $game) {
                    $stmt->execute([
                        $game['name'],
                        $game['seo_text'],
                        $game['card_text'],
                        $game['description'],
                        $game['category_id'],
                        $game['tags'],
                        $game['peculiarities'],
                        $game['advice'],
                        $game['similar'],
                        $game['submodes'],
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

    try {
        $subCount = db()->query('SELECT COUNT(*) as c FROM subs')->fetch()['c'] ?? 0;

        if ($subCount == 0) {
            $subsData = [
                [
                    'name' => 'Lapis Candy',
                    'priceMonth' => 99,
                    'priceAlways' => 299,
                    'discount' => 50,
                    'path_image' => 'bg-lapis.jpg'
                ],
                [
                    'name' => 'Gold Candy',
                    'priceMonth' => 299,
                    'priceAlways' => 899,
                    'discount' => 130,
                    'path_image' => 'bg-gold.jpg'
                ],
                [
                    'name' => 'Diamond Candy',
                    'priceMonth' => 549,
                    'priceAlways' => 1399,
                    'discount' => 200,
                    'path_image' => 'bg-diamond.jpg'
                ],
                [
                    'name' => 'Emerald Candy',
                    'priceMonth' => 999,
                    'priceAlways' => 2249,
                    'discount' => 300,
                    'path_image' => 'bg-emerald.jpg'
                ],
                [
                    'name' => 'God Of Candy',
                    'priceMonth' => 1499,
                    'priceAlways' => 5499,
                    'discount' => 500,
                    'path_image' => 'bg-god.jpg'
                ],
                [
                    'name' => 'Sponsor',
                    'priceMonth' => 2799,
                    'priceAlways' => 22999,
                    'discount' => 800,
                    'path_image' => 'bg-sponsor.jpg'
                ],
            ];

            $stmt = db()->prepare("INSERT INTO subs 
                    (name, priceMonth, priceAlways, discount, path_image) 
                    VALUES (?, ?, ?, ?, ?)");

                foreach ($subsData as $sub) {
                    $stmt->execute([
                        $sub['name'],
                        $sub['priceMonth'],
                        $sub['priceAlways'],
                        $sub['discount'],
                        $sub['path_image']
                    ]);
                }
                echo "<p>✅ Добавлено " . count($subsData) . " подписок.</p>";
        }
    }
    catch (Throwable $e) {
        echo "<p style='color: orange'>⚠️ Ошибка синдирования подписок: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    echo '<meta charset="utf-8"><style>body{font-family:Arial;background:#151322;color:#eee;padding:40px}a{color:#ff65ad}</style>';
    echo '<h1>Готово 🍭</h1><p>База <b>lollipopmc</b> и таблицы созданы.</p><p><a href="/lollipopmc/register/">Регистрация сайта</a> · <a href="/lollipopmc/forum/register/">Регистрация форума</a> · <a href="/lollipopmc/forum/">Форум</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<meta charset="utf-8"><pre>Ошибка установки: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre><p>Проверь .env: DB_HOST / DB_DATABASE / DB_USERNAME / DB_PASSWORD.</p>';
}
