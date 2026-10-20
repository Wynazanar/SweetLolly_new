<?php
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once $projectRoot . '/project/components/header/Header.php';
require_once $projectRoot . '/project/components/footer/Footer.php';
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
	<?php
    	echo Header::make();
	?>

	<?= $content ?>

	<?php
		echo Footer::make();
	?>
</body>
</html>