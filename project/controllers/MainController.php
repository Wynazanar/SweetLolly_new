<?php
namespace Project\Controllers;
use \Core\Controller;
use \Project\Models\Page;
use \Project\Services\BridgeApi;

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
		$nickname = trim(urldecode($params['player'] ?? ''));
		if ($nickname === '') {
			$this->title = 'Игрок не найден | SweetLolly';
			return $this->render('user/profile', [
				'user' => null,
				'nickname' => '',
			]);
		}

		$sessionNick = $_SESSION['nickname'] ?? '';
		$isOwnProfile = !empty($_SESSION['logged_in'])
			&& $sessionNick !== ''
			&& strcasecmp($sessionNick, $nickname) === 0;

		$fallbackUuid = $isOwnProfile ? ($_SESSION['unique_id'] ?? null) : null;
		$bridge = new BridgeApi();
		$resolved = $bridge->resolveProfile(
			$nickname,
			is_string($fallbackUuid) ? $fallbackUuid : null
		);
		$user = $resolved['user'];

		if ($user === null && $isOwnProfile) {
			$user = $this->profileFromSession($nickname);
		}

		if ($user === null && !$resolved['bridgeAvailable']) {
			$this->title = 'Профиль | SweetLolly';
			return $this->render('user/profile', [
				'user' => null,
				'nickname' => $nickname,
				'bridgeError' => true,
			]);
		}

		if ($user === null) {
			$this->title = 'Игрок не найден | SweetLolly';
			return $this->render('user/profile', [
				'user' => null,
				'nickname' => $nickname,
			]);
		}

		$uuid = $user['unique_id'] ?? null;
		$nick = $user['nickname'] ?? $nickname;
		if (is_string($uuid) && $uuid !== '') {
			$user['points'] = $bridge->playerPoints($uuid, is_string($nick) ? $nick : null);
		} elseif (is_string($nick) && $nick !== '') {
			$user['points'] = $bridge->playerPoints('', $nick);
		}

		$displayNick = $user['nickname'] ?? $nickname;
		$user['mojang_uuid'] = $this->getMojangUuid($displayNick);

		$this->title = $user['nickname'] . ' | Профиль | SweetLolly';

		return $this->render('user/profile', [
			'user' => $user,
			'isOwnProfile' => $isOwnProfile,
		]);
	}

	private function profileFromSession(string $nickname): ?array
	{
		if (empty($_SESSION['logged_in'])) {
			return null;
		}

		$sessionNick = $_SESSION['nickname'] ?? '';
		if ($sessionNick === '' || strcasecmp($sessionNick, $nickname) !== 0) {
			return null;
		}

		return [
			'nickname'      => $sessionNick,
			'unique_id'     => $_SESSION['unique_id'] ?? null,
			'email'         => $_SESSION['email'] ?? null,
			'last_ip'       => null,
			'last_login'    => $_SESSION['last_login'] ?? null,
			'creation_date' => $_SESSION['creation_date'] ?? null,
		];
	}

	private function getMojangUuid(string $nickname): ?string
	{
		$nickname = trim($nickname);
		if ($nickname === '') {
			return null;
		}

		$url = 'https://api.mojang.com/users/profiles/minecraft/' . rawurlencode($nickname);
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 5,
			CURLOPT_HTTPHEADER     => ['Accept: application/json'],
		]);
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($code !== 200 || !is_string($body) || $body === '') {
			return null;
		}

		$data = json_decode($body, true);
		if (!is_array($data) || empty($data['id']) || !is_string($data['id'])) {
			return null;
		}

		return $this->formatMojangUuid($data['id']);
	}

	private function formatMojangUuid(string $id): ?string
	{
		$compact = strtolower(str_replace('-', '', $id));
		if (strlen($compact) !== 32 || !ctype_xdigit($compact)) {
			return null;
		}

		return sprintf(
			'%s-%s-%s-%s-%s',
			substr($compact, 0, 8),
			substr($compact, 8, 4),
			substr($compact, 12, 4),
			substr($compact, 16, 4),
			substr($compact, 20, 12)
		);
	}
}
