<?php
namespace Project\Controllers;
use \Core\Controller;
use \Project\Models\Page;

class MainController extends Controller
{
	public function index()
	{
		$this->title = 'Главная | SweetLolly';

		return $this->render('main/index');
	}

	public function games()
	{
		$this->title = 'Режимы | SweetLolly';

		$page = new Page();
		$games = $page->getAllGames();
		$gameCategories = $page->getAllGameCategories();

		return $this->render('games/index', [
			"games" => $games,
			"gameCategories" => $gameCategories
		]);
	}

	public function gameInfo($gameName)
	{
		$page = new Page();
		$gameSlug = urldecode($gameName['game'] ?? '');
		$gameInfo = $page->getGameByName($gameSlug);

		$this->title = $gameInfo ? $gameInfo['name'] . ' | SweetLolly' : $gameSlug . ' | SweetLolly';

		return $this->render('games/gameInfo', [
			"game" => $gameInfo
		]);
	}

	public function rules()
	{
		$this->title = 'Правила | SweetLolly';

		$page = new Page();
		$rules = $page->getRules();
		$ruleCategories = $page->getRuleCategories();

		return $this->render('rules/index', [
			"rules" => $rules,
			"ruleCategories" => $ruleCategories,
		]);
	}

	public function donats()
	{
		$this->title = "Донат | SweetLolly";

		$page = new Page();
		$subs = $page->getAllSubs();

		return $this->render('donats/index', [
			"subs" => $subs,
		]);
	}

	public function help()
	{
		$this->title = "Помощь | SweetLolly";

		return $this->render('help/index');
	}

	public function team()
	{
		$this->title = "Команда проекта | SweetLolly";

		return $this->render('help/index');
	}

	public function profile($params)
	{
		$nickname = urldecode($params['player'] ?? '');
		// $user = (new \Project\Models\User)->findByNickname($nickname);

		$user = [
			'nickname' => $nickname,                    // или из API
			'unique_id' => $_SESSION['unique_id'] ?? null,
			'creation_date' => $_SESSION['creation_date'] ?? null,
			'last_login' => $_SESSION['last_login'] ?? null,
		];

		if (!$user) {
			$this->title = "Игрок не найден | SweetLolly";
			return $this->render('user/profile', [
				'user' => null,
				'nickname' => $nickname,
			]);
		}

		$this->title = $user['nickname'] . ' | Профиль | SweetLolly';

		$isOwnProfile = !empty($_SESSION['logged_in'])
			&& ($_SESSION['nickname'] ?? '') === $user['nickname'];

		return $this->render('user/profile', [
			'user' => $user,
			'isOwnProfile' => $isOwnProfile,
		]);
	}

	function getMojangUuid(string $nickname): ?string
	{
		$url = 'https://api.mojang.com/users/profiles/minecraft/' . rawurlencode($nickname);
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_HTTPHEADER => ['Accept: application/json'],
		]);
		$body = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($code !== 200 || !$body) {
			return null; // ник не премиум или ошибка
		}

		$data = json_decode($body, true);
		if (empty($data['id'])) {
			return null;
		}

		// id без дефисов → с дефисами
		// $id = $data['id'];
		// return sprintf(
		// 	'%s-%s-%s-%s-%s',
		// 	substr($id, 0, 8),
		// 	substr($id, 8, 4),
		// 	substr($id, 12, 4),
		// 	substr($id, 16, 4),
		// 	substr($id, 20, 12)
		// );
	}
}
