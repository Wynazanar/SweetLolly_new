<?php
function get_category_icon($category) {
  $icons = [
    'PvP' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.60492 8.81L6.14992 10.355C6.24492 10.45 6.36992 10.5 6.50492 10.5H8.00492V9.5H6.70992L2.49992 5.295V4H1.49992V5.5C1.49992 5.635 1.55492 5.76 1.64492 5.855L3.18992 7.4L0.794922 9.795L2.20992 11.21L4.60492 8.815V8.81Z" fill="#421DC9"/>
                      <path d="M7.99992 1C7.84992 1 7.70992 1.065 7.61492 1.185L3.78492 5.87L6.13492 8.22L10.8199 4.39C10.9349 4.295 11.0049 4.155 11.0049 4.005V1.5C11.0049 1.225 10.7799 1 10.5049 1H7.99992Z" fill="#421DC9"/>
                    </svg>',
    'Экономика' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M1 6.972V7.242C1 8.34 3.05625 9.564 6 9.564C8.94375 9.564 11 8.34 11 7.242V6.972C9.86875 7.818 8.06875 8.364 6 8.364C3.93125 8.364 2.13125 7.824 1 6.972Z" fill="#421DC9"/>
                      <path d="M1 4.53V4.836C1 5.934 3.05625 7.158 6 7.158C8.94375 7.158 11 5.934 11 4.836V4.53C9.86875 5.376 8.06875 5.922 6 5.922C3.93125 5.922 2.13125 5.382 1 4.53Z" fill="#421DC9"/>
                      <path d="M6 0C3.5125 0 1 0.822 1 2.4C1 3.498 3.05625 4.722 6 4.722C8.94375 4.722 11 3.498 11 2.4C11 0.822 8.4875 0 6 0ZM1 9.372V9.6C1 11.178 3.5125 12 6 12C8.4875 12 11 11.178 11 9.6V9.372C9.86875 10.218 8.06875 10.764 6 10.764C3.93125 10.764 2.13125 10.224 1 9.372Z" fill="#421DC9"/>
                    </svg>',
    'Креатив' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.3839 11.0341C6.10402 10.3141 6.46408 9.17418 6.29605 8.13024C6.15203 7.2303 5.62994 6.51034 4.83181 6.11437C4.06968 5.73039 3.03751 5.79039 2.27538 6.26436C1.57327 6.69633 1.1892 7.39829 1.1892 8.23824C1.1892 8.41822 1.2012 8.59221 1.21321 8.7542C1.26121 9.37217 1.27922 9.60615 0.33106 10.0801C0.139028 10.1761 0.0130074 10.3621 0.00100537 10.5721C-0.0109966 10.7821 0.0850194 10.9861 0.259048 11.1061C0.859148 11.52 1.91532 12 3.03151 12C3.84164 12 4.67578 11.748 5.3839 11.0401V11.0341Z" fill="#421DC9"/>
                    <path d="M8.80447 0.546718L4.07568 5.27442C4.44174 5.31642 4.7898 5.41241 5.10185 5.5684C6.008 6.02437 6.6141 6.81633 6.83614 7.80626L11.4509 3.19255C12.183 2.4606 12.183 1.27267 11.4509 0.546718C10.7188 -0.179236 9.53059 -0.185236 8.80447 0.546718Z" fill="#421DC9"/>
                  </svg>',
    'Аркады' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10.9091 2H1.09091C0.490909 2 0 2.51429 0 3.14286V7.71429C0 8.97714 0.976364 10 2.18182 10H9.81818C11.0236 10 12 8.97714 12 7.71429V3.14286C12 2.51429 11.5091 2 10.9091 2ZM2.18182 6.57143H1.09091V5.42857H2.18182V6.57143ZM1.63636 4.85714C1.33636 4.85714 1.09091 4.6 1.09091 4.28571C1.09091 3.97143 1.33636 3.71429 1.63636 3.71429C1.93636 3.71429 2.18182 3.97143 2.18182 4.28571C2.18182 4.6 1.93636 4.85714 1.63636 4.85714ZM8.72727 8.85714H3.27273V3.14286H8.72727V8.85714ZM10.9091 6.57143H9.81818V5.42857H10.9091V6.57143ZM10.3636 4.85714C10.0636 4.85714 9.81818 4.6 9.81818 4.28571C9.81818 3.97143 10.0636 3.71429 10.3636 3.71429C10.6636 3.71429 10.9091 3.97143 10.9091 4.28571C10.9091 4.6 10.6636 4.85714 10.3636 4.85714Z" fill="#421DC9"/>
                </svg>',
    'Выживание' => '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11.6196 2.32519L6.66742 0.00623376V12C12.5874 9.24468 11.9867 2.88623 11.98 2.81766C11.9694 2.71301 11.93 2.6127 11.8657 2.52643C11.8014 2.44016 11.7143 2.37085 11.613 2.32519H11.6196ZM0.0199603 2.81766C0.0132862 2.88 -0.587388 9.24468 5.33259 12V0L0.380365 2.31896C0.173466 2.41247 0.0399828 2.59948 0.0132862 2.81143L0.0199603 2.81766Z" fill="#421DC9"/>
                    </svg>',
  ];

  foreach ($icons as $key => $icon) {
    if (stripos($category, $key) !== false) {
      return $icon;
    }
  }

  return '';
}
?>

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

              <div class="game-good-media">
                <img src="/SweetLolly_new/project/webroot/images/minigames/<?= htmlspecialchars($game['path_image'] ?? '') ?>"
                  alt="<?= htmlspecialchars($game['name'] ?? '') ?>">
              </div>

              <div class="game-good-body">
                <div class="body-pill">
                  <div class="pill-icon">
                    <?= get_category_icon($game['category_name']) ?>
                  </div>
                  <p><?= htmlspecialchars($game['category_name'] ?? '') ?></p>
                </div>
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
                    <p><?= htmlspecialchars($tag) ?></p>
                  <?php endforeach; ?>
                </div>
                <div class="game-good-footer">
                  <div class="good-footer-msg">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M6.5 1.08337C5.78171 1.08337 5.09283 1.36872 4.58492 1.87663C4.07701 2.38454 3.79167 3.07341 3.79167 3.79171C3.79167 4.51 4.07701 5.19888 4.58492 5.70679C5.09283 6.2147 5.78171 6.50004 6.5 6.50004C7.2183 6.50004 7.90717 6.2147 8.41508 5.70679C8.92299 5.19888 9.20833 4.51 9.20833 3.79171C9.20833 3.07341 8.92299 2.38454 8.41508 1.87663C7.90717 1.36872 7.2183 1.08337 6.5 1.08337ZM2.16667 11.9167H10.8333C11.1313 11.9167 11.375 11.673 11.375 11.375V10.8334C11.375 8.74254 9.67417 7.04171 7.58333 7.04171H5.41667C3.32583 7.04171 1.625 8.74254 1.625 10.8334V11.375C1.625 11.673 1.86875 11.9167 2.16667 11.9167Z" fill="#6D5876"/>
                    </svg>
                    <p>Какое-то сообщение!</p>
                  </div>
                  <b>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8.23715 2.46155L13.5385 8.00001L8.23715 13.5385L6.91648 12.1611L9.98565 8.96997H2.46153V7.03005H9.98565L6.91648 3.83889L8.23715 2.46155Z" fill="white"/>
                    </svg>
                  </b>
                </div>
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