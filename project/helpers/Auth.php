<?php
function currentUser(): ?array
{
    if (empty($_SESSION['logged_in'])) return null;
    return [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role'     => $_SESSION['role'],
    ];
}

function requireAuth(): void
{
    if (!currentUser()) {
        header('Location: /SweetLolly_new/login/');
        exit;
    }
}

function requireRole(string ...$roles): void
{
    $user = currentUser();
    if (!$user || !in_array($user['role'], $roles, true)) {
        http_response_code(403);
        die('Access denied');
    }
}