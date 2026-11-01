<?php
function currentUser(): ?array
{
    if (empty($_SESSION['logged_in'])) {
        return null;
    }

    return [
        'id'       => $_SESSION['user_id'],
        'nickname' => $_SESSION['nickname'] ?? null,
        'email'    => $_SESSION['email'] ?? null,
    ];
}

function requireAuth(): void
{
    if (!currentUser()) {
        header('Location: /SweetLolly_new/login/');
        exit;
    }
}