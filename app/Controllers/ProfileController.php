<?php
namespace App\Controllers;

use Core\Controller;
use App\Services\BridgeApi;

class ProfileController extends Controller
{
    public function show(array $params)
    {
        $nickname = trim(urldecode($params['player'] ?? ''));

        if ($nickname === '') {
            $this->title = 'Игрок не найден | SweetLolly';
            return $this->render('user/profile', [
                'user'           => null,
                'nickname'       => '',
                'minigame_stats' => [],
            ]);
        }

        $sessionNick  = $_SESSION['nickname'] ?? '';
        $isOwnProfile = !empty($_SESSION['logged_in'])
            && $sessionNick !== ''
            && strcasecmp($sessionNick, $nickname) === 0;

        $bridge = new BridgeApi();
        session_release();

        $user = $bridge->getProfile($nickname);

        if ($user === null && $isOwnProfile) {
            session_resume();
            $user = $this->profileFromSession($nickname);
            session_release();
        }

        if ($user === null) {
            $bridgeError = $bridge->getLastError() !== null;
            session_resume();
            $this->title = 'Игрок не найден | SweetLolly';
            return $this->render('user/profile', [
                'user'           => null,
                'nickname'       => $nickname,
                'minigame_stats' => [],
                'bridgeError'    => $bridgeError,
            ]);
        }

        $minigameStats = $bridge->getMinigameStats($nickname);
        $user['mojang_uuid'] = $this->getMojangUuid($user['nickname'] ?? $nickname);

        session_resume();

        $this->title = ($user['nickname'] ?? $nickname) . ' | Профиль | SweetLolly';

        return $this->render('user/profile', [
            'user'           => $user,
            'minigame_stats' => $minigameStats,
            'isOwnProfile'   => $isOwnProfile,
        ]);
    }

    public function settings()
    {
        $this->title = 'Настройки аккаунта | SweetLolly';
        // пока пусто, как было
        return $this->render('user/settings');
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