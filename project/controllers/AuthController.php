<?php
namespace Project\Controllers;
use Core\Controller;
use Project\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $this->title = 'Вход | SweetLolly';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();

            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($this->isRateLimited($login)) {
                return $this->render('auth/login', [
                    'error' => 'Слишком много попыток. Попробуйте через 15 минут.'
                ]);
            }

            $user = (new User)->findByLogin($login);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nickname'] = $user['nickname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                $this->clearRateLimit($login);
                header('Location: /SweetLolly_new/');
                exit;
            }

            $this->incrementRateLimit($login);
            return $this->render('auth/login', [
                'error' => 'Неверный логин или пароль'
            ]);
        }

        return $this->render('auth/login');
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