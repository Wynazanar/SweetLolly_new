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
    <div class="container games-good">
      <section class="beauty-hero">
        <span class="beauty-eyebrow">
          <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z"></path>
          </svg>
          Режимы SweetLolly
        </span>
        <h1>Игровые режимы</h1>
        <p>Нормальная чистая страница: без лишней каши, с понятными карточками и рабочей галереей скриншотов.</p>
        <div class="games-good-ip">
          <span>IP</span><code>play.swetlolly.net</code>
          <button data-copy="play.swetlolly.net">Скопировать</button>
        </div>
        <div class="beauty-actions">
          <a class="beauty-btn" href="/lollipopmc/howto/">
            Как начать
          </a>
          <a class="beauty-btn secondary" href="/lollipopmc/forum/">
            Форум
          </a>
        </div>
      </section>
      <section class="games-good-filter">
        <button class="active" data-mode-filter="all">Все</button>
        <?php foreach ($gameCategories as $category): ?>
          <button data-mode-filter="<?= $category['name'] ?>"><?= $category['name'] ?></button>
        <?php endforeach; ?>
      </section>
      <div>
        <div class="games-good-grid">
          <?php foreach ($games as $game): ?>
            <a class="game-good-card" data-mode-card="" data-cats="<?= $game['category_name']?>"
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
  <div class="toast-copy">Скопировано: play.swetlolly.net</div>
</body>
<script>

  (function () {
    document.addEventListener('click', function (e) {
      var b = e.target.closest('[data-mode-filter]'); if (!b) return;
      e.preventDefault(); var f = b.getAttribute('data-mode-filter');
      document.querySelectorAll('[data-mode-filter]').forEach(function (x) { x.classList.toggle('active', x === b) });
      document.querySelectorAll('[data-mode-card]').forEach(function (card) {
        var cats = (card.getAttribute('data-cats') || '').split(',');
        card.style.display = (f === 'all' || cats.indexOf(f) !== -1) ? '' : 'none';
      });
    });
  })();

  (function () { 
    var btns = document.querySelectorAll('[data-mode-filter]');
    var cards = document.querySelectorAll('[data-mode-card]');
    if (!btns.length) 
      return; 
    
    btns.forEach(function (b) { 
      b.addEventListener('click', function () { 
        btns.forEach(function (x) { 
          x.classList.remove('active')
        });

        b.classList.add('active');
        var f = b.getAttribute('data-mode-filter');
        cards.forEach(function (c) {
          var cats = (c.getAttribute('data-cats') || '');
          c.style.display = (f === 'all' || cats.indexOf(f) > -1) ? '' : 'none';
        }); 
      });
    });
  })();
  (function () { document.querySelectorAll('[data-copy]').forEach(function (b) { b.addEventListener('click', function () { var v = b.getAttribute('data-copy'); navigator.clipboard && navigator.clipboard.writeText(v); var t = b.textContent; b.textContent = 'Скопировано'; setTimeout(function () { b.textContent = t }, 1200); }); }); })();
</script>

</html>