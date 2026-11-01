<?php
namespace Project\Services;

class TopService
{
    private BridgeApi $bridge;

    public function __construct(?BridgeApi $bridge = null)
    {
        $this->bridge = $bridge ?? new BridgeApi();
    }

    public function resolveConfig(?array $gameInfo, string $slug): ?array
    {
        $map = [
            'bedwars' => [
                'endpoint' => 'bw-top',
                'orderBy'  => 'rating',
                'columns'  => [
                    'player_name'    => 'Игрок',
                    'kills'          => 'Убийства',
                    'deaths'         => 'Смерти',
                    'wins'           => 'Победы',
                    'losses'         => 'Поражения',
                    'beds_destroyed' => 'Кровати',
                    'rating'         => 'Рейтинг',
                ],
            ],
            'bow duel' => [
                'endpoint' => 'bow-top',
                'orderBy'  => 'wins',
                'columns'  => [
                    'player_name' => 'Игрок',
                    'wins'        => 'Победы',
                    'losses'      => 'Поражения',
                    'kills'       => 'Убийства',
                    'deaths'      => 'Смерти',
                ],
            ],
            'classic duel' => [
                'endpoint' => 'classic-top',
                'orderBy'  => 'wins',
                'columns'  => [
                    'player_name' => 'Игрок',
                    'wins'        => 'Победы',
                    'losses'      => 'Поражения',
                ],
            ],
            // остальные режимы добавляй сюда
        ];

        $key = mb_strtolower($gameInfo['name'] ?? $slug);
        return $map[$key] ?? null;
    }

    public function fetch(string $endpoint, array $params = []): array
    {
        $defaults = [
            'page'    => 1,
            'limit'   => 20,
            'orderBy' => null,
            'order'   => 'desc',
        ];

        $result = $this->bridge->getTop($endpoint, array_merge($defaults, $params));

        if ($result === null) {
            return [
                'rows' => [],
                'meta' => ['page' => 1, 'pages' => 1, 'total' => 0, 'limit' => 20],
            ];
        }

        return $result;
    }
}