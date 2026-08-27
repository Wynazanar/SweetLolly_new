<?php
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $title ?></title>

	<link rel="icon" href="/SweetLolly_new/project/webroot/resources/logo.png" type="image/png">
	<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/reset.css">
	<link rel="stylesheet" href="/SweetLolly_new/project/webroot/styles/global.css">
</head>

<body>
	<header>
		<div class="container">
			<div class="header">
				<a href="/SweetLolly_new/" class="logo">SWEETLOLLY</a>
				<div class="user-theme">
					<div class="theme-toggle" data-theme-toggle>🌙</div>

					<?php if (!empty($_SESSION['logged_in'])): ?>
						<div class="user user-logged">
							<a class="user-logg-a" href="/SweetLolly_new/profile/<?= htmlspecialchars($_SESSION['nickname'] ?? '') ?>/">
								<img src="/SweetLolly_new/project/webroot/resources/noavatar.jpg" alt="avatar">
								<p><?= htmlspecialchars($_SESSION['nickname'] ?? 'Игрок') ?></p>
							</a>
							<a href="/SweetLolly_new/logout/" class="logout">
								<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"  
									transform="scale(-1,1) ">
									<path d="M15 11H8v2h7v4l6-5-6-5z"/><path d="M5 21h7v-2H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2"/>
								</svg>
							</a>
						</div>
					<?php else: ?>
						<a class="user" href="/SweetLolly_new/login/">
							<!-- <img src="/SweetLolly_new/project/webroot/resources/noavatar.jpg" alt="guest"> -->
							<p>Войти</p>
						</a>
					<?php endif; ?>

					<script src="/SweetLolly_new/project/webroot/scripts/theme.js"></script>
				</div>
			</div>
		</div>
		</div>
	</header>
	<section class="hero-nav">
		<div class="container">
			<div class="hero-row">
				<a href="SweetLolly_new/" class="site-logo" aria-label="SweetLolly">
					<img src="/SweetLolly_new/project/webroot/images/logo_s.jpeg">
				</a>
				<div class="promo">
					<div>Присоединись к игрокам<br>уже сейчас!</div>
					<a href="./howto/" class="btn btn-lollypop">Начать играть :)</a>
					<br>
					<div class="clipboard">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
							viewBox="0 0 24 24">
							<rect width="14" height="14" x="8" y="2" rx="2" ry="2"></rect>
							<path
								d="M8.5 18A2.5 2.5 0 0 1 6 15.5V8H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-2z">
							</path>
						</svg>
						<p>IP:</p>
						<span class="copy_id">play.swetlolly.net</span>
					</div>
				</div>
			</div>
			<nav class="main-menu">
				<a href="/SweetLolly_new/forum/">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
						viewBox="0 0 24 24">
						<path
							d="M12 2C6.49 2 2 6.49 2 12c0 2.12.68 4.19 1.93 5.9l-1.75 2.53c-.21.31-.24.7-.06 1.03.17.33.51.54.89.54h9c5.51 0 10-4.49 10-10S17.51 2 12 2M6 9h3v2H6zm7 6H6v-2h7zm5 0h-3v-2h3zm0-4h-7V9h7z">
						</path>
					</svg>
					Форум
				</a>
				<a href="/SweetLolly_new/rules/">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
						viewBox="0 0 24 24" transform="scale(-1,1) ">
						<path
							d="M2 20h13v2H2zM18.71 8.71a.996.996 0 0 0 0-1.41l-5-5a.996.996 0 0 0-1.41 0l-1.29 1.29L17.42 10zm-10.42 9c.2.2.45.29.71.29s.51-.1.71-.29L11 16.42l-6.41-6.41L3.3 11.3a.996.996 0 0 0 0 1.41l5 5Zm5.21-3.8 6.79 6.8 1.42-1.42-6.8-6.79L16 11.41 9.59 5 6 8.59 12.41 15z">
						</path>
					</svg>
					Правила
				</a>
				<a href="/SweetLolly_new/games/">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
						viewBox="0 0 24 24">
						<path
							d="M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h11c2.76 0 5-2.24 5-5V4c0-1.1-.9-2-2-2m-7 16h-2v2H8v-2H6v-2h2v-2h2v2h2zm3 1c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m2-2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1m1-5H6V5h12z">
						</path>
					</svg>
					Режимы
				</a>
				<a href="/SweetLolly_new/candies/">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
						viewBox="0 0 24 24">
						<path
							d="M21 8H7c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h14c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1m-1 8c-1.1 0-2 .9-2 2h-8c0-1.1-.9-2-2-2v-4c1.1 0 2-.9 2-2h8c0 1.1.9 2 2 2z">
						</path>
						<path d="M18 4H3c-.55 0-1 .45-1 1v11h2V6h14zm-4 8a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
					</svg>
					Леденцы
				</a>
				<a href="/SweetLolly_new/help/">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
						viewBox="0 0 24 24">
						<path
							d="M12 22c5.51 0 10-4.49 10-10S17.51 2 12 2 2 6.49 2 12s4.49 10 10 10M11 7h2v2h-2zm0 4h2v6h-2z">
						</path>
					</svg>
					Помощь
				</a>
			</nav>
		</div>
	</section>

	<?= $content ?>

	<footer class="lolli-footer">
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-6 footer_about">
						<div class="footer-logo"></div>
						<div class="footer-text">Добро пожаловать на сайт сервера SweetLolly :)<br>Новости, форум,
							режимы, помощь и
							личный кабинет — всё в одном месте.<br><br>Email для связи: sweetauth.project@gmail.com
						</div>
					</div>
					<div class="col-12 col-md-3 footer_navigation">
						<h3>Навигация</h3>
						<ul>
							<li><a href="/SweetLolly_new/rules/">Правила</a></li>
							<li><a href="/SweetLolly_new/games/">Режимы</a></li>
							<li><a href="/SweetLolly_new/candies/">Леденцы</a></li>
							<li><a href="/SweetLolly_new/help/">Помощь</a></li>
						</ul>
					</div>
					<div class="col-12 col-md-3 footer_community">
						<h3>Сообщество</h3>
						<ul>
							<li><a href="./forum/">Форум</a></li>
							<li><a href="https://www.instagram.com/sweetlolly.project/">Instagram</a></li>
							<li><a href="https://discord.com/invite/bqkBcVxPen">Discord</a></li>
							<li><a href="https://www.youtube.com/@SweetLollyYT">YouTube</a></li>
							<li><a href="https://t.me/+-N_b9e6CGt45MGZk">Telegram</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<p>SweetLolly.net © 2022-2026</p>
		</div>
	</footer>
</body>

</html>