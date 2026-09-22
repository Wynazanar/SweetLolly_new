<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/games.css">
<main style="Margin-top: 50px;">
    <div class="container games-good">
      
      <h2 class="section-title">Игровые режимы</h2>
      <h2 class="section-subtitle">Уникальные режимы, мини-игры и многое другое. Найди то, что по душе,</br> и погружайся в мир приключений!</h2>

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
  
<script>
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
  
</script>