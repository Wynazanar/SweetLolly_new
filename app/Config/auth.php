<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
// ini_set('session.cookie_secure', 1); // когда будет HTTPS

$reqUri = $_SERVER['REQUEST_URI'] ?? '';
$skipSession = strpos($reqUri, '/api/internal/') !== false;

if (!$skipSession) {
    session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function session_release(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
}

function session_resume(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}