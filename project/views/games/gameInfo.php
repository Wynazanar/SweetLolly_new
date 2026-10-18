<?php
require_once $projectRoot . '/project/components/Table.php';
?>
<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/gameInfo.css">
<main>
  <div class="container">
    <?php if (!empty($game)): ?>
      <h1><?= htmlspecialchars($game['name'] ?? '') ?></h1>
      <!-- остальная вёрстка режима -->
    <?php endif; ?>

    <?php if ($topConfig !== null): ?>
      <h2 class="section-title">Топ игроков</h2>
      <?php
        $endpoint = $topConfig['endpoint'];
        echo Table::make(
            columns:    $topConfig['columns'],
            rows:       $topRows,
            id:         $endpoint,
            searchable: true,
            sortable:   true,
            // только путь сайта — IP плагина здесь нет
            apiUrl:     '/SweetLolly_new/api/top/' . $endpoint,
            dataKey:    $endpoint,
            page:       (int)($topMeta['page'] ?? 1),
            pageSize:   (int)($topMeta['limit'] ?? 20),
            total:      (int)($topMeta['total'] ?? 0),
            pages:      (int)($topMeta['pages'] ?? 1),
            orderBy:    $topConfig['orderBy'],
            order:      'desc'
        );
      ?>
    <?php endif; ?>
  </div>
</main>