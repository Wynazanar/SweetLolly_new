<?php
require_once $projectRoot . '/project/components/Table.php';

$rows = [
  ['nickname' => 'Wynazanar', 'role' => 'Developer', 'points' => 1500, 'status' => 'Онлайн'],
  ['nickname' => 'Steve',     'role' => 'Игрок',     'points' => 320,  'status' => 'Оффлайн'],
  ['nickname' => 'Alex',      'role' => 'Модератор', 'points' => 890,  'status' => 'Онлайн'],
];

$filters = [
  'role' => [
  'label'   => 'Роль',
  'options' => ['Developer', 'Модератор', 'Игрок'],
  ],
  'status' => [
  'label'   => 'Статус',
  'options' => ['Онлайн', 'Оффлайн'],
  ],
];

?>

<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/gameInfo.css">
<main>
  <div class="container">
    <?php
      echo Table::make(
          columns: ['nickname' => 'Игрок', 'wins' => 'Победы', 'kills' => 'Убийства', 'deaths' => 'Смерти', 'points' => 'Очки'],
          rows: $rows,
          id: 'players-table',
          searchable: false,
          sortable: false,
      );
    ?>
  </div>
</main>