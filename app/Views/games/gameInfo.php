<?php

namespace App\Components;

// var_dump($game);
// var_dump($topConfig);
// var_dump($topModes);
// var_dump($activeMode);
// var_dump($topRows);
// var_dump($topMeta);

echo SubHeader::make([
    ['title' => 'Как начать играть', 'href' => url('/start')],
    ['title' => 'Правила',           'href' => url('/rules')],
    ['title' => 'Леденцы',           'href' => url('/candies')],
    ['title' => 'Помощь',            'href' => url('/help')],
]);

?>

<link rel="stylesheet" href="<?= asset('styles/gameInfo.css') ?>">
<main>
  <?php if (!empty($game)): ?>
  <div class="container" style="margin-top: 56px;">
      <?php
        echo Breadcrumbs::make([
          ['title' => 'Главная', 'href' => url('/')],
          ['title' => 'Режимы',  'href' => url('/games')],
          ['title' => htmlspecialchars($game['name'] ?? '')]
        ]);
      ?>

      <div class="game-header-card">
        <div class="game-header-info">
          <span>ПОПУЛЯРНЫЙ РЕЖИМ</span>
          <h1><?= htmlspecialchars($game['name'] ?? '') ?></h1>
          <p>Защищай свою кровать, уничтожай кровати врагов и стань последним выжившим на острове!</p>
          <a href="#" class="beauty-btn secondary">Играть →</a>
          <span>Командная игра · стратегия · развитие базы</span>
        </div>
        <img src="<?= asset('images/minigames/bedwars-1.png'); ?>">
      </div>
  </div>

  <section style="background: var(--pill-bg-strong); padding-block: 56px;">
    <div class="container">
      <h2>Галерея</h2>
      <p></p>

      <?php
        $full_images = [];

        foreach(json_decode($game['gallery_path']) as $path):
          array_push($full_images, asset('images/minigames/'.$path));
        endforeach;

        echo Slider::make($full_images);
      ?>
    </div>
  </section>
  
  <section style="margin-bottom: 30px; margin-top: 56px;">
    <div class="container">
      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px;">
        <div style="padding: 28px; background: var(--surface); border-radius: 24px; display: flex; flex-direction: column; justify-content: space-between;">
          <?php
            foreach(json_decode($game['peculiarities']) as $pec_key=>$pec):
              echo "<p>".($pec_key + 1)." ".$pec."</p>";
            endforeach;
          ?>
        </div>

        <div style="padding: 28px; background: var(--pill-bg-strong); border-radius: 24px;">
          <?php
            foreach(json_decode($game['advice']) as $adv):
              echo "<p style='font-size: 14px;'>".$adv."</p>";
            endforeach;
          ?>
        </div>
      </div>



    </div>
  </section>
    
  <div class="container">
    <?php if ($topConfig !== null): ?>
      <section class="top-section mode-detail-card">
        <div class="top-section__head">
          <h2 class="section-title">Топ игроков</h2>

          <?php if (count($topModes) > 1): ?>
            <div class="top-mode-tabs" role="tablist" aria-label="Подрежимы топа">
              <?php foreach ($topModes as $modeKey => $modeCfg): ?>
                <?php
                  $isActive = ($activeMode === $modeKey);
                  $qs = $_GET;
                  $qs['mode'] = $modeKey;
                  $href = '?' . http_build_query($qs);
                ?>
                <a
                  href="<?= htmlspecialchars($href) ?>"
                  class="top-mode-tab<?= $isActive ? ' is-active' : '' ?>"
                  role="tab"
                  aria-selected="<?= $isActive ? 'true' : 'false' ?>"
                  data-mode="<?= htmlspecialchars($modeKey) ?>"
                ><?= htmlspecialchars($modeCfg['label']) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <?php
          $endpoint = $topConfig['endpoint'];
          $basePath = rtrim(config('app.base_path', ''), '/');
          $apiUrl   = ($basePath !== '' ? $basePath : '') . '/api/top/' . $endpoint;

          echo Table::make(
              columns:    $topConfig['columns'],
              rows:       $topRows,
              id:         $endpoint,
              searchable: true,
              sortable:   true,
              apiUrl:     $apiUrl,
              dataKey:    $endpoint,
              page:       (int)($topMeta['page'] ?? 1),
              pageSize:   (int)($topMeta['limit'] ?? 20),
              total:      (int)($topMeta['total'] ?? 0),
              pages:      (int)($topMeta['pages'] ?? 1),
              orderBy:    $topConfig['orderBy'],
              order:      'desc'
          );
        ?>
      </section>
    <?php endif; ?>
  </div>

  <section>
    <div class="container">
      <h2>Ещё больше приключений</h2>
      <div>
        <?php foreach(json_decode($game['similar']) as $similar): ?>
          <a href="<?= url('games/'.$similar.'/') ?>"><?= $similar ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if(empty($game)): ?>
    <h3>Игра не найдена!</h3>
  <?php endif; ?>
</main>