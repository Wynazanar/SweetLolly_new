<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Game;
use App\Services\TopService;

class GamesController extends Controller
{
    public function index()
    {
        $this->title = 'Режимы | SweetLolly';

        $gameModel = new Game();

        return $this->render('games/index', [
            'games'          => $gameModel->allWithCategories(),
            'gameCategories' => $gameModel->allCategories(),
        ]);
    }

    public function show(array $params)
    {
        $slug = urldecode($params['game'] ?? '');
        $gameModel = new Game();
        $game = $gameModel->findByName($slug);

        $this->title = $game
            ? $game['name'] . ' | SweetLolly'
            : $slug . ' | SweetLolly';

        $topService = new TopService();
        $topConfig  = $topService->resolveConfig($game, $slug);

        $topRows = [];
        $topMeta = ['page' => 1, 'pages' => 1, 'total' => 0, 'limit' => 20];

        if ($topConfig !== null) {
            session_release();
            $result = $topService->fetch($topConfig['endpoint'], [
                'page'    => 1,
                'limit'   => 20,
                'orderBy' => $topConfig['orderBy'],
                'order'   => 'desc',
            ]);
            session_resume();

            $topRows = $result['rows'];
            $topMeta = $result['meta'];
        }

        return $this->render('games/gameInfo', [
            'game'      => $game,
            'topConfig' => $topConfig,
            'topRows'   => $topRows,
            'topMeta'   => $topMeta,
        ]);
    }
}