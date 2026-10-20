<?php
	use \Core\Route;
	
	return [
		new Route('/', 'main', 'index'),
		new Route('/games/', 'main', 'games'),
		new Route('/games/:game/', 'main', 'gameInfo'),

		new Route('/rules/', 'main', 'rules'),
		new Route('/candies/','main', 'donats'),

		new Route('/help/','main', 'help'),
		new Route('/team/','main', 'team'),

		new Route('/profile/:player', 'main', 'profile'),
		new Route('/settings/', 'main', 'settings'),

		new Route('/login/',  'auth', 'login'),
		new Route('/register/','auth', 'register'),
		new Route('/logout/', 'auth', 'logout'),

		new Route('/api/top/:endpoint', 'api', 'top'),

		// уведомления от плагина
		new Route('/api/internal/notify', 'api', 'notify'),

		// друзья
		new Route('/friends/', 'friends', 'index'),
		new Route('/friends/request/', 'friends', 'request'),
		new Route('/friends/accept/', 'friends', 'accept'),
		new Route('/friends/reject/', 'friends', 'reject'),
		new Route('/friends/notification-read/', 'friends', 'readNotification'),
		new Route('/api/notifications', 'api', 'notifications'),
		new Route('/api/notifications/opened', 'api', 'notificationsOpened'),
		
	];