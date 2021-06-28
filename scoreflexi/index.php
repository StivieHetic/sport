<?php 
session_start();
$title = "Accueil";
require "queries/queries_user.php";
require ('includes/function.php');
include('includes/constant.php');

	if(isset($_POST['login'])){
		if(['identifiant', 'password']){
			extract($_POST);
			$users = find_users_active($identifiant, sha1($password), 1);
			if($users != null) {
				$_SESSION['user_id'] = $users['id'];
				$_SESSION['pseudo'] = $users['pseudo'];
				header('location: profile.php');
			}else{
				echo "Mot de passe ou bien identifiant incorrect";
			}
		}
	}else{
		
	}
	if (isset($_POST['register'])) {
		if (!empty($_POST['pseudo']) && !empty($_POST['mail']) && !empty($_POST['equipe']) && !empty($_POST['sport']) && !empty($_POST['password'])&& !empty($_POST['password_comfirm'])) {
			$error = false;

			if (! filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
				echo "Adresse Email invalide !";
				$error = true;
			}

			if ($_POST['password'] != $_POST['password_comfirm']) {
				echo "Les deux mots de passe ne correspondent pas !";
				$error = true;
			}

			if (find_user_pseudo($_POST['pseudo']) != null) {
				echo "Pseudo déjà utilisé !";
				$error = true;
			}

			if (find_user_mail($_POST['mail']) != null) {
				echo "Adresse email déjà utilisée !";
				$error = true;
			}

			if (!$error) {
				$to = $_POST['mail'];
				$subject = WEBSITE_NAME. " - Activation de compte";
				$password = sha1($_POST['password']);
				$token = sha1($_POST['pseudo'] . $_POST['mail'] . $password);

				ob_start();

				require('template/email/activation.view.php');
				$content = ob_get_clean();

				$header="MIME-Version: 1.0\r\n";
				$header.='From:"Scoreflexi.com"<support@primfx.com>'."\n";
				$header.='Content-Type:text/html; charset="uft-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				mail($to, $subject, $content, $header);

				echo "Un Email d'activation vous a été envoyé";
				
				$pronostiqueur = $_POST['pronostiqueur'];
				if ($pronostiqueur == 1) {
					$profil_id = 3;	
				} else {
					$profil_id = 1;
				}
				create_user($_POST['pseudo'], $_POST['mail'], $_POST['sport'], $_POST['equipe'], $password, $profil_id);
				header('location: index.php');
			}else{
				
			}
		}else{
			echo "Veuillez remplir tous les champs";
		}
	}else{
		
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=0">
	    <link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_index.css">
	    <title><?= isset($title) ? $title . ' - '.WEBSITE_NAME : WEBSITE_NAME.'- Meilleurs site de pronos ';?></title>
	</head>
	<body>
		<header>
			<nav>
				<h1><a href="#"><?= WEBSITE_NAME ;?></a></h1>
				<ul class="m-right">
					<label for="modal-1" class="btn-register">Inscription</label>
					<label for="modal-2" class="btn-login">Connexion</label>
				</ul>
			</nav>
		</header>
		<main>
			<input type="checkbox" id="modal-1" class="hs" style="margin-top: 110px">
			<div class="modal-wrapper">
				<div class="modalbox">
					<div class="modal-content">
						<div class="modal-header">
							<h2>Inscription</h2>
							<label for="modal-1" class="btn-close">❌</label>
						</div>
						<div class="modal-content-body">
							<form class="formulaire-inscription" data-parsley-validate method="POST">
					          	<div class="row">
						            <label>Pseudo</label>
						           	<input type="text" placeholder="Votre pseudo" name="pseudo" id="pseudo" minlength="4" required='required' />
					          	</div>
					     		<div class="row">
					            	<label>Email</label>
					           		<input type="email" placeholder="Votre Email" name="mail" id="email" required="required" />
					        	</div>
					          	<div class="row">
						            <label>Sport</label>
						            <input type="text" placeholder=" Sport favoris"   name="sport" id="sport"  required="required">
					          	</div>
					          	<div class="row">
						            <label>	Equipe</label>
						            <input type="text" placeholder="Equipe favorite"  name="equipe" id="equipe" required="required">
					         	</div>
					           	<div class="row">
					            	<label>	Mot de Passe</label>
					          		<input type="password" placeholder="Votre mot de passe" name="password" id="password" minlength="6" required="required" />
					      		</div>   
					      		<div class="row">
					            	<label>	Confirmation Mot de passe</label>
					          		<input type="password" placeholder="Confirmation mot de passe" name="password_comfirm" id="password_comfirm" minlength="6" required="required"/>
					      		</div>
						        <div class="full">
						             S'inscrire en tant que pronostiqueur ?
						            <input type="radio" name="pronostiqueur" value="1">
						            <label>Oui</label>
						            <input type="radio" name="pronostiqueur" value="0" checked="checked" >
						        	<label>Non</label>
						        </div>
						        <div class="valide">
						      	 	<input type="submit" value="Inscription" name="register">
						      	</div>
					        </form>
						</div>
					</div>
				</div>
			</div>

			<input type="checkbox" id="modal-2" class="hs">

			<div class="modal-wrapper">
				<div class="modalbox">
					<div class="modal-content">
						<div class="modal-header">
							<h2>Connexion</h2>
							<label for="modal-2" class="btn-close">❌</label>
						</div>
						<div class="modal-content-body">
							<form method="POST">
					            <div class="row">
					              <label>Pseudo ou Email </label>
					              <input type="text" name="identifiant" id="identifiant" placeholder="Email ou Pseudo" required="required">
					            </div>
					            <div class="row">
					              <label>Mot de passe</label>
					              <input type="password" name="password"  placeholder="Mot de passe" required="required">
					            </div>
					           	Mot de passe oublié ?
					            <div class="valide">
					            	<input type="submit" name="login" value="Connexion">
					            </div>
					        </form>
						</div>
					</div>
				</div>
			</div>
		</main>
	</body>
</html>