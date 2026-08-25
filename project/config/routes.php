<?php
	use \Core\Route;
	
	return [
		new Route('/', 'main', 'index'),
		new Route('/games/', 'main', 'games'),
		new Route('/games/:game/', 'main', 'gameInfo'),

		new Route('/rules/', 'main', 'rules'),
		new Route('/candies/','main', 'donats'),

		new Route('/profile/:player/', 'main', 'player')
	];
	
