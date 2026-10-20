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
        $friends = $bridge->getFriends($me) ?? [];
        $requests = $bridge->getFriendRequests($me) ?? [];
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
        $ok = $bridge->sendFriendRequest($me, $to);

        // можно flash-сообщение в сессию
        $_SESSION['flash'] = $ok
            ? 'Заявка отправлена'
            : ('Ошибка: ' . ($bridge->getLastError() ?? 'неизвестно'));

        header('Location: /SweetLolly_new/friends/');
        exit;
    }

    /** POST: принять */
    public function accept()
    {
        $me = $this->requireLogin();
        $this->checkCsrf();

        $from = trim($_POST['from'] ?? ''); // кто кинул заявку
        $bridge = new BridgeApi();
        $bridge->acceptFriend($from, $me);

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
        $bridge->rejectFriend($from, $me);

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
}