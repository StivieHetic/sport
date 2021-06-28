<?php
session_start();
require ('../includes/function.php');
require ('../includes/constant.php');
require($_SERVER['DOCUMENT_ROOT'] . '/services/services_user.php');

if(!is_admin(get_session('user_id'))) {
	header('location: ../profile.php');
	exit();
}
else {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Interface d'administration</title>
	<link rel="stylesheet" type="text/css" href="/asset/css/css_header/header-site.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery_site.js"></script>

</head>
<body style="font-family:'open Sans', sans-serif;">
	<div class="site-container">
    	<div class="site-pusher">
			<header class="header-site"><?php require ("../partials/header_site.php") ?></header>
			<div style="margin: auto; width: 1900px ">
			<a href="admin/gestion_sports.php">Gestion sports</a> - <a href="admin/gestion_championnats.php">Gestion championnats</a> - <a href="admin/gestion_equipes.php">Gestion équipes</a> - <a href="admin/gestion_matchs.php">Gestion matchs</a>
		</div>
			<div class="site-cache" id="site-cache"></div>
		</div>
	</div>
</body>
</html>
<?php
}
?>
