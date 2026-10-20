<?php
namespace Project\Controllers;

use Core\Controller;
use Project\Services\BridgeApi;
use Project\Models\Notification;

class FriendsController extends Controller
{
    private function requireLogin(): string
    {
        if (empty($_SESSION['logged_in']) || empty($_SESSION['nickname'])) {
            header('Location: /SweetLolly_new/login/');
            exit;
        }
        return $_SESSION['nickname'];
    }

    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token mismatch');
        }
    }

    /** Страница: друзья + входящие заявки + уведомления */
    public function index()
    {
        $me = $this->requireLogin();
        $this->title = 'Друзья | SweetLolly';

        $bridge = new BridgeApi();
        session_release();
        $friends = $bridge->getFriends($me);
        $requests = [];
        if ($friends === null && $bridge->getLastError() === BridgeApi::ERR_NETWORK) {
            session_resume();
            $_SESSION['flash'] = $_SESSION['flash']
                ?? 'Игровой API недоступен. Запусти BedwarsAPI на порту 3000.';
            $friends = [];
        } else {
            $friends = $friends ?? [];
            $requests = $bridge->getFriendRequests($me) ?? [];
            session_resume();
        }
        $notifications = (new Notification())->unreadFor($me);

        return $this->render('friends/index', [
            'friends' => $friends,
            'requests' => $requests,
            'notifications' => $notifications,
            'me' => $me,
        ]);
    }

    /** POST: отправить заявку */
    public function request()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();

        $to = trim($_POST['to'] ?? '');
        if ($to === '') {
            header('Location: /SweetLolly_new/friends/');
            exit;
        }

        $bridge = new BridgeApi();
        session_release();
        $ok = $bridge->sendFriendRequest($me, $to);
        $err = $bridge->getLastError();
        session_resume();

        $_SESSION['flash'] = $ok
            ? 'Заявка отправлена'
            : $this->friendErrorMessage($err);

        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    /** POST: принять */
    public function accept()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();

        $from = trim($_POST['from'] ?? '');
        if ($from === '') {
            header('Location: /SweetLolly_new/friends/');
            exit;
        }

        $bridge = new BridgeApi();
        session_release();
        $ok = $bridge->acceptFriend($from, $me);
        session_resume();

        if ($ok) {
            (new Notification())->markFriendRequestRead($me, $from);
        }

        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    /** POST: отклонить */
    public function reject()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();

        $from = trim($_POST['from'] ?? '');
        $bridge = new BridgeApi();
        session_release();
        $ok = $bridge->rejectFriend($from, $me);
        session_resume();
        if ($ok) {
            (new Notification())->markFriendRequestRead($me, $from);
        }

        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    /** POST: удалить из друзей */
    public function remove()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();

        $to = trim($_POST['to'] ?? '');
        if ($to === '') {
            header('Location: /SweetLolly_new/friends/');
            exit;
        }

        $bridge = new BridgeApi();
        session_release();
        $ok = $bridge->removeFriend($me, $to);
        $err = $bridge->getLastError();
        session_resume();

        $_SESSION['flash'] = $ok
            ? 'Игрок удалён из друзей'
            : $this->friendErrorMessage($err);

        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    /** POST: прочитать уведомление */
    public function readNotification()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            (new Notification())->markRead($id, $me);
        }
        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    private function friendErrorMessage(?string $err): string
    {
        if ($err === BridgeApi::ERR_NETWORK) {
            return 'Игровой API недоступен. Запусти BedwarsAPI на порту 3000.';
        }
        if ($err === BridgeApi::ERR_ENDPOINT) {
            return 'Плагин не знает этот метод друзей. Обнови BedwarsAPI.';
        }
        if ($err === BridgeApi::ERR_SERVER) {
            return 'Плагин вернул ошибку сервера.';
        }
        return 'Ошибка: ' . ($err ?? 'неизвестно');
    }
}