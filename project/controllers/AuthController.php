<?php
namespace Project\Controllers;
use Core\Controller;
use Project\Models\User;

class AuthController extends Controller
{
    private const API_URL = 'http://10.196.46.253:4567/v1/auth/login';
    private const API_KEY = 'mykey123';

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

            if ($this->isRateLimited($login)) {
                return $this->render('auth/login', [
                    'error' => 'Слишком много попыток. Попробуйте через 15 минут.'
                ]);
            }

            // === Вызов API вместо локальной проверки ===
            $apiResult = $this->authViaApi($login, $password);

            if ($apiResult === null) {
                $this->incrementRateLimit($login);
                return $this->render('auth/login', [
                    'error' => 'Ошибка соединения с сервером авторизации'
                ]);
            }

            if (empty($apiResult['success'])) {
                $this->incrementRateLimit($login);
                return $this->render('auth/login', [
                    'error' => $apiResult['message'] ?? 'Неверный логин или пароль'
                ]);
            }

            // Успешный вход через API
            session_regenerate_id(true);

            // Данные, которые вернул AuthBridge
            $username  = $apiResult['username'] ?? $login;
            $_SESSION['logged_in']      = true;
            $_SESSION['nickname']       = $username;
            $_SESSION['email']          = $apiResult['email'] ?? null;
            $_SESSION['unique_id']      = $apiResult['unique_id'] ?? null;
            $_SESSION['creation_date']  = $apiResult['creation_date'] ?? null;
            $_SESSION['last_login']     = $apiResult['last_login'] ?? null;
            $_SESSION['user_id']        = $apiResult['unique_id'] ?? $username;

            $this->clearRateLimit($login);

            $nickForHTTP = rawurlencode($username);
            header("Location: /SweetLolly_new/profile/$nickForHTTP/");
            exit;
        }

        return $this->render('auth/login');
    }

    /**
     * Отправляет логин + пароль (в открытом виде) на API AuthBridge
     * @return array|null  — ответ API или null при ошибке сети
     */
    private function authViaApi(string $login, string $password): ?array
    {
        $payload = json_encode([
            'login'    => $login,
            'password' => $password
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init(self::API_URL);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'key: ' . self::API_KEY,
                'Content-Length: ' . strlen($payload)
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode >= 500) {
            error_log("Auth API error: $error (HTTP $httpCode)");
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            error_log("Auth API invalid JSON: $response");
            return null;
        }

        return $data;
    }

    public function register()
    {
        $this->title = 'Регистрация | SweetLolly';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';

            $errors = [];
            if (strlen($username) < 3 || strlen($username) > 32)
                $errors[] = 'Ник 3–32 символа';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[] = 'Некорректный email';
            if (strlen($password) < 8)
                $errors[] = 'Пароль минимум 8 символов';
            if ($password !== $confirm)
                $errors[] = 'Пароли не совпадают';

            if ($errors) {
                return $this->render('auth/register', ['errors' => $errors]);
            }

            try {
                if ((new User)->exists($username, $email)) {
                    return $this->render('auth/register', [
                        'errors' => ['Такой ник или email уже занят']
                    ]);
                }

                (new User)->create($username, $email, $password);
                header('Location: /SweetLolly_new/login/');
                exit;
            } catch (\Throwable $e) {
                return $this->render('auth/register', [
                    'errors' => ['Ошибка при регистрации. Попробуйте позже.']
                ]);
            }
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