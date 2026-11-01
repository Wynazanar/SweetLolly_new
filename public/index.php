<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);

require $basePath . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->safeLoad();

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Сессия + CSRF + session_release/resume
require $basePath . '/app/Config/auth.php';

// Временные константы для старого Model
$db = require $basePath . '/app/Config/database.php';
define('DB_HOST', $db['host']);
define('DB_USER', $db['username']);
define('DB_PASS', $db['password']);
define('DB_NAME', $db['database']);

$appBasePath = config('app.base_path');

$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (($qPos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $qPos);
}

if ($appBasePath !== '' && str_starts_with($uri, $appBasePath)) {
    $uri = substr($uri, strlen($appBasePath));
}

if ($uri === '' || $uri === false) {
    $uri = '/';
}

$routes = require $basePath . '/app/Config/routes.php';

$track = (new Core\Router())->getTrack($routes, $uri);
$page  = (new Core\Dispatcher())->getPage($track);

echo (new Core\View())->render($page);