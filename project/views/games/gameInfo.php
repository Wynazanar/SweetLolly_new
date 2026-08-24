<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Режимы | SweetLolly</title>

  <link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/gameInfo.css?v=<?= time() ?>">
</head>

<main style="margin-top: 50px;">
  <div class="container modes-wrap">

    <!-- <div class="modes-back">
      <a class="beauty-pill" href="../">← Все режимы</a>
    </div> -->

    <section class="mode-detail-hero"
      style="--cover:url('/SweetLolly_new/project/webroot/images/<?= $game['path_image'] ?>')">
      <div class="mode-detail-content">
        <span class="modes-kicker">
          <?= htmlspecialchars($game['icon']) ?>
        </span>
        <h1> <?= $game['name'] ?> </h1>
        <p> <?= $game['seo_text'] ?> </p>
        <div class="mode-tags-v7">
          <?php
          $tags = $game->tags ?? $game['tags'] ?? [];
          if (is_string($tags)) {
            $tags = json_decode($tags, true) ?? [];
          }
          foreach ($tags as $tag): ?>
            <span><?= htmlspecialchars($tag) ?></span>
          <?php endforeach ?>
        </div>
      </div>
    </section>

    <div class="mode-detail-layout">
      <main>
        <section class="mode-detail-card">
          <h2>Что это за режим?</h2>
          <p class="muted"> <?= $game['description'] ?> </p>
        </section>
        <section class="mode-detail-card">
          <h2>Особенности</h2>
          <ul class="mode-list">
            <?php
            $peculiarities = $game->peculiarities ?? $game['peculiarities'] ?? [];
            if (is_string($peculiarities)) {
              $peculiarities = json_decode($peculiarities, true) ?? [];
            }
            foreach ($peculiarities as $key => $peculiarity): ?>
              <li><b> <?= $key + 1 ?>. </b> <?= htmlspecialchars($peculiarity) ?> </li>
            <?php endforeach ?>
          </ul>
        </section>
        <section class="mode-detail-card">
          <h2>Советы для старта</h2>
          <ul class="mode-list">
            <?php
            $advices = $game->advice ?? $game['advice'] ?? [];
            if (is_string($advices)) {
              $advices = json_decode($advices, true) ?? [];
            }
            foreach ($advices as $advice): ?>
              <li> <?= htmlspecialchars($advice) ?> </li>
            <?php endforeach ?>
          </ul>
        </section>
      </main>
      <aside>
        <section class="mode-detail-card">
          <h2>Информация</h2>
          <div class="mode-side-stat">
            <div><b>IP</b><span class="ip-copy">play.swetlolly.net</span></div>
            <div>
              <b>Тип</b>
              <span>
                <?php
                $tags = $game->tags ?? $game['tags'] ?? [];
                if (is_string($tags)) {
                  $tags = json_decode($tags, true) ?? [];
                }
                $tagsString = implode(', ', array_map('htmlspecialchars', $tags));
                echo $tagsString;
                ?>
              </span>
            </div>
            <div><b>Статус</b><span>Доступен на сервере</span></div>
          </div>
          <div class="beauty-actions"><a class="beauty-btn" href="../../../howto/">Как зайти</a><a
              class="beauty-btn secondary" href="../../../forum/">Форум</a></div>
        </section>
        <section class="mode-detail-card">
          <h2>Похожие</h2>
          <?php
          $similars = $game->similar ?? $game['similar'] ?? [];
          if (is_string($similars)) {
            $similars = json_decode($similars, true) ?? [];
          }
          foreach ($similars as $similar): ?>
            <a class="beauty-pill" href="/SweetLolly_new/games/<?= $similar ?>/"> <?= $similar ?> </a>
          <?php endforeach ?>
        </section>
      </aside>
    </div>
    <section class="games-good-gallery" data-v30-gallery>
      <div class="games-good-title">
        <h2>Скриншоты <?= htmlspecialchars($game['name']) ?></h2>
        <p>Нажмите на фото, чтобы открыть в полном размере</p>
      </div>

      <div class="games-good-view">
        <button data-v30-prev class="gg-arr prev" aria-label="Предыдущий">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>

        <button data-v30-next class="gg-arr next" aria-label="Следующий">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 18l6-6-6-6" />
          </svg>
        </button>

        <div class="gg-slides">
          <figure data-v30-slide class="active">
            <img src="/lollipopmc/assets/shot_lobby.jpg" alt="Лобби" loading="lazy">
            <figcaption>Лобби</figcaption>
          </figure>
          <figure data-v30-slide>
            <img src="/lollipopmc/assets/shot_tntrun_arena.jpg" alt="Арена" loading="lazy">
            <figcaption>Арена TNT Run</figcaption>
          </figure>
          <figure data-v30-slide>
            <img src="/lollipopmc/assets/game_orig_tntrun.jpg" alt="Забег" loading="lazy">
            <figcaption>Забег</figcaption>
          </figure>
          <figure data-v30-slide>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="Победа" loading="lazy">
            <figcaption>Победа</figcaption>
          </figure>
        </div>

        <div class="games-good-thumbs">
          <button data-v30-thumb class="active">
            <img src="/lollipopmc/assets/shot_lobby.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_arena.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/game_orig_tntrun.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
          <button data-v30-thumb>
            <img src="/lollipopmc/assets/shot_tntrun_win.jpg" alt="">
          </button>
        </div>
      </div>
    </section>
  </div>
</main>

</body>

</html>