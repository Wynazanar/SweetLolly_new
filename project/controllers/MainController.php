<?php
	namespace Project\Controllers;
	use \Core\Controller;
	use \Project\Models\Page;
	
	class MainController extends Controller
	{
		public function index() {
			$this->title = 'Главная | SweetLolly';
			
			return $this->render('main/index');
		}

		public function games() {
			$this->title = 'Режимы | SweetLolly';

			$page = new Page();
			$games = $page->getAllGames();
			
			return $this->render('games/index', [
				"games" => $games
			]);
		}
		
		public function gameInfo($gameName) {
			$page = new Page();
			$gameSlug = urldecode($gameName['game'] ?? '');
			$gameInfo = $page->getGameByName($gameSlug);
			
			$this->title = $gameInfo ? $gameInfo['name'] . ' | SweetLolly' : $gameSlug . ' | SweetLolly';
			
			return $this->render('games/gameInfo', [
				"game" => $gameInfo
			]);
		}
	}
