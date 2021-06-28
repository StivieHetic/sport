<?php
session_start();
require "queries/queries_user.php";
require "includes/function.php";

if (!empty($_GET['p']) && find_user_pseudo($_GET['p']) != null && !empty($_GET['token'])) {
 	$pseudo = $_GET['p'];
 	$token = $_GET['token'];

 	$users = find_user($pseudo);

 	$token_verif = sha1($pseudo . $users['mail'] . $users['password']);

 	if ($token == $token_verif) {
 		update_user(1, $pseudo);

		header(' location: index.php');
		exit();
	} else {
 		set_flash('parametres invalides', 'danger');
 		header(' location: index.php');
		exit();
 	}
} else {
 	header(' location: index.php');
	exit();
}
?>
