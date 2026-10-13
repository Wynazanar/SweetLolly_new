<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/gameInfo.css">
<main>
  <div class="container">
    <?php
require_once $projectRoot . '/project/components/Table.php';

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

echo Table::make(
    columns: ['nickname' => 'Игрок', 'wins' => 'Победы', 'kills' => 'Убийства', 'deaths' => 'Смерти', 'points' => 'Очки'],
    rows: $bw_top,
    id: 'players-table',
    searchable: false,
    sortable: false,
);
?>
  </div>
</main>