<?php
// Вызывать один раз в index.php до любого вывода
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
// если сайт на HTTPS:
// ini_set('session.cookie_secure', 1);

session_start();