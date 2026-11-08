<?php
use Core\Route;

return [
    new Route('/', 'main', 'index'),

    // Игры
    new Route('/games', 'games', 'index'),
    new Route('/games/:game', 'games', 'show'),

    // Правила
    new Route('/rules', 'rules', 'index'),

    // Донат
    new Route('/candies', 'donats', 'index'),

    // Помощь
    new Route('/help', 'help', 'index'),
    new Route('/team', 'help', 'team'),

    // Профиль
    new Route('/profile/:player', 'profile', 'show'),
    new Route('/settings/', 'profile', 'settings'),

    // Auth
    new Route('/login/', 'auth', 'login'),
    new Route('/register/', 'auth', 'register'),
    new Route('/logout/', 'auth', 'logout'),

    // API
    new Route('/api/top/:endpoint', 'api', 'top'),
    new Route('/api/internal/notify', 'api', 'notify'),
    new Route('/api/notifications', 'api', 'notifications'),
    new Route('/api/notifications/opened', 'api', 'notificationsOpened'),

    // Друзья
    new Route('/friends/', 'friends', 'index'),
    new Route('/friends/request/', 'friends', 'request'),
    new Route('/friends/accept/', 'friends', 'accept'),
    new Route('/friends/reject/', 'friends', 'reject'),
    new Route('/friends/remove/', 'friends', 'remove'),
    new Route('/friends/notification-read/', 'friends', 'readNotification'),
];