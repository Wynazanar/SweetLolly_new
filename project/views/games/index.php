<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Режимы | SweetLolly</title>
  
  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/games.css?v=<?= time() ?>">
</head>

<body class="">
  <main style="Margin-top: 50px;">
    <div class="container">
      <div class="games-good-grid">
        <?php foreach ($games as $game): ?>
          <a class="game-good-card" data-mode-card="" data-cats="survival,economy"
            href="./<?= htmlspecialchars($game['name'] ?? $game->name) ?>/">

            <div class="game-good-media is-icon">
              <img src="/SweetLolly_new/project/webroot/images/<?= htmlspecialchars($game['path_image'] ?? '') ?>"
                alt="<?= htmlspecialchars($game['name'] ?? '') ?>">
            </div>

            <div class="game-good-body">
              <p style="font-size: 12px;"><?= htmlspecialchars($game['category_name'] ?? '') ?></p>
              <h3><?= htmlspecialchars($game['name'] ?? '') ?></h3>
              <p class="body-text"><?= htmlspecialchars($game['card_text'] ?? '') ?></p>

              <div class="game-good-tags">
                <?php
                $tags = $game->tags ?? $game['tags'] ?? [];
                if (is_string($tags)) {
                  $tags = json_decode($tags, true) ?? [];
                }
                foreach ($tags as $tag):
                  ?>
                  <span><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
              </div>

              <b>Открыть →</b>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

</body>
</html>