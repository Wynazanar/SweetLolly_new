<?php
require_once $projectRoot . '/project/components/Table.php';

$apiBase = "http://127.0.0.1:3000/bw-top";
$page    = 1;
$limit   = 20;

$url  = $apiBase . '?page=' . $page . '&limit=' . $limit . '&orderBy=rating&order=desc';
$json = json_decode(@file_get_contents($url), true) ?: [];

$rows = $json['bw-top'] ?? [];
$meta = $json['meta'] ?? ['page' => 1, 'pages' => 1, 'total' => 0];

$columns = [
    'player_name'      => 'Игрок',
    'kills'            => 'Убийства',
    'deaths'           => 'Смерти',
    'wins'             => 'Победы',
    'losses'           => 'Поражения',
    'beds_destroyed'   => 'Кровати',
    'rating'           => 'Рейтинг',
];

?>

<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/gameInfo.css">
<main>
  <div class="container">
    <?php
      echo Table::make(
        columns:   $columns,
        rows:      $rows,
        id:        'bw-top',
        searchable: true,
        sortable:  true,
        apiUrl:    $apiBase,           // ← без этого JS не выведется
        dataKey:   'bw-top',
        page:      (int)($meta['page'] ?? 1),
        pageSize:  $limit,
        total:     (int)($meta['total'] ?? 0),
        pages:     (int)($meta['pages'] ?? 1),
        orderBy:   'rating',
        order:     'desc'
      );
    ?>
  </div>
</main>