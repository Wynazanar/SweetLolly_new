<?php
namespace Project\Controllers;
use Core\Controller;
use Project\Models\User;
use Project\Services\BridgeApi;

class AuthController extends Controller
{
    /** Временно отключено — включить перед продом */
    private const RATE_LIMIT_ENABLED = false;

    public function login()
{
    $this->title = 'Вход | SweetLolly';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->checkCsrf();

        $login    = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($login === '' || $password === '') {
            return $this->render('auth/login', [
                'error' => 'Введите логин и пароль'
            ]);
        }

        if (self::RATE_LIMIT_ENABLED && $this->isRateLimited($login)) {
            return $this->render('auth/login', [
                'error' => 'Слишком много попыток. Попробуйте через 15 минут.'
            ]);
        }

        $bridge = new BridgeApi();
        $apiResult = $bridge->login($login, $password);

        if ($apiResult === null || empty($apiResult['success'])) {
            if (self::RATE_LIMIT_ENABLED) {
                $this->incrementRateLimit($login);
            }
            $msg = $bridge->getLastError() ?: 'Неверный логин или пароль';
            return $this->render('auth/login', [
                'error' => $msg
            ]);
        }

        // Успешный вход
        session_regenerate_id(true);

        $username = $apiResult['username'] ?? $login;
        $_SESSION['logged_in']     = true;
        $_SESSION['nickname']      = $username;
        $_SESSION['email']         = $apiResult['email'] ?? null;
        $_SESSION['unique_id']     = $apiResult['unique_id'] ?? null;
        $_SESSION['creation_date'] = $apiResult['creation_date'] ?? null;
        $_SESSION['last_login']    = $apiResult['last_login'] ?? null;
        $_SESSION['user_id']       = $apiResult['unique_id'] ?? $username;

        if (self::RATE_LIMIT_ENABLED) {
            $this->clearRateLimit($login);
        }

        $nickForHTTP = rawurlencode($username);
        header("Location: /SweetLolly_new/profile/$nickForHTTP/");
        exit;
    }

    return $this->render('auth/login');
}

public function register()
{
    $this->title = 'Регистрация | SweetLolly';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->checkCsrf();

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');   // пока не сохраняем в nlogin, можно оставить для будущего
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $errors = [];
        if (strlen($username) < 3 || strlen($username) > 16) {
            $errors[] = 'Ник 3–16 символов';
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Ник может содержать только латиницу, цифры и _';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Пароль минимум 6 символов';
        }
        if ($password !== $confirm) {
            $errors[] = 'Пароли не совпадают';
        }

        if ($errors) {
            return $this->render('auth/register', ['errors' => $errors]);
        }

        $bridge = new BridgeApi();
        $result = $bridge->register($username, $password);

        if ($result === null || empty($result['success'])) {
            $msg = $bridge->getLastError() ?: 'Ошибка при регистрации';
            return $this->render('auth/register', [
                'errors' => [$msg]
            ]);
        }

        // Успех — сразу логиним или кидаем на страницу входа
        // Вариант 1: сразу войти
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['nickname']  = $result['username'] ?? $username;
        $_SESSION['unique_id'] = $result['unique_id'] ?? null;
        $_SESSION['user_id']   = $result['unique_id'] ?? $username;

        header('Location: /SweetLolly_new/profile/' . rawurlencode($_SESSION['nickname']) . '/');
        exit;

        // Вариант 2 (если хочешь только редирект на логин):
        // header('Location: /SweetLolly_new/login/');
        // exit;
    }

    return $this->render('auth/register');
}

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $p['path'],
                $p['domain'],
                $p['secure'],
                $p['httponly']
            );
        }
        session_destroy();
        header('Location: /SweetLolly_new/');
        exit;
    }

    private function bridgeLoginErrorMessage(?string $code): string
    {
        return match ($code) {
            BridgeApi::ERR_ENDPOINT => 'На сервере не активен плагин AuthBridge (API /v1/auth/login не найден). '
                . 'Установите jar в plugins/, проверьте config.yml и логи при старте.',
            BridgeApi::ERR_SERVER => 'Сервер авторизации вернул ошибку. Проверьте консоль Minecraft и AuthBridge.',
            default => 'Не удалось связаться с сервером авторизации. '
                . 'Проверьте, что ServerTap доступен по адресу из project/config/bridge.php.',
        };
    }

    // ——— CSRF ———
    private function checkCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token mismatch');
        }
    }

    // ——— Простой rate-limit (можно в Redis/файл/таблицу) ———
    private function isRateLimited(string $login): bool
    {
        $key = 'login_attempts_' . md5($login . $_SERVER['REMOTE_ADDR']);
        $data = $_SESSION[$key] ?? ['count' => 0, 'time' => 0];
        if ($data['count'] >= 5 && time() - $data['time'] < 900) {
            return true;
        }
        return false;
    }

    private function incrementRateLimit(string $login): void
    {
        $key = 'login_attempts_' . md5($login . $_SERVER['REMOTE_ADDR']);
        $data = $_SESSION[$key] ?? ['count' => 0, 'time' => time()];
        $data['count']++;
        $data['time'] = time();
        $_SESSION[$key] = $data;
    }

    private function clearRateLimit(string $login): void
    {
        $key = 'login_attempts_' . md5($login . $_SERVER['REMOTE_ADDR']);
        unset($_SESSION[$key]);
    }
}