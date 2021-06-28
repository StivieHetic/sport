<?php
session_start();
require ('../includes/function.php');
require ('../includes/constant.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_sport.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_championnat.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_match.php');
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
	<title>Gestion des sports</title>
	<link rel="stylesheet" type="text/css" href="/asset/css/css_header/header-site.css">
	<link rel="stylesheet" type="text/css" href="../asset/css/css_user_style/table_matchs.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery_site.js"></script>
</head>
<body>
	<div class="site-container">
    	<div class="site-pusher">
			<header class="header-site"><?php require ("../partials/header_site.php") ?></header>
			<div class="container">
                <div class="holder">
      				<div class="responsiveTbl">
      					<div class="lien_gestion">
      					<a href="gestion_championnats.php" class="lien">Gestion championnats</a><a href="gestion_equipes.php" class="lien">Gestion équipes</a><a href="gestion_matchs.php" class="lien"> Gestion matchs</a>
      				</div>
      					<form method="POST">
      						<input type="submit" name="ajouter_sport" value="Ajouter">
      					</form>

      					<?php 
      					if (isset($_POST['valider_ajouter_sport'])) {
      						$id = find_sport_by_name($_POST['name_sport']);
      						if ($id == null) {
  								create_sport($_POST['name_sport']);
  							} else {
  								echo "Erreur : un sport avec ce nom existe déjà !";
  							}
  						}
      					?>
						<table class="table-responsive">
							<caption>Sports</caption>
							<thead>
								<tr>
									<th>Sport</th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<?php
								if (isset($_POST['valider_editer_sport'])) {
									$id = find_sport_by_name($_POST['sport_name']);
      								if ($id == null) {
      									$ok = true;
									} else {
										if ($_POST['sport_id'] == $id) {
											$ok = true;
										} else {
											$ok = false;
										}
		  							}
		  							if ($ok) {
		  								update_sport($_POST['sport_name'], $_POST['sport_id']);
		  							} else {
		  								echo "Erreur : un sport avec ce nom existe déjà !";
		  							}
								}

								if (isset($_POST['delete_sport'])) {
									$delete_ok = true;
									$championnats = find_championnats_by_sport_id($_POST['sport_id']);
									$matchs = find_matchs_by_sport_id($_POST['sport_id']);
									if ($championnats != null) {
										$delete_ok = false;
										echo "Erreur : ce sport ne peut pas être supprimé, il est référencé par les championnats :";
										echo "<ul>";
										for ($i=0; $i <count($championnats) ; $i++) {
											echo "<li>" . $championnats[$i]['name'] . "</li>";
										}
										echo "</ul>";
									}

									if ($matchs != null) {
										$delete_ok = false;
										echo "Erreur : ce sport ne peut pas être supprimé, il est référencé par les matchs :";
										echo "<ul>";
										for ($i=0; $i <count($matchs) ; $i++) {
											echo "<li>" . $matchs[$i]['sports_name'] . ", " . $matchs[$i]['championnats_name'] . ", " . $matchs[$i]['equipes_1_nom'] . ", " . $matchs[$i]['equipes_2_nom'] . ", " . $matchs[$i]['date_match'] . "</li>";
										}
										echo "</ul>";
									}

									if ($delete_ok) {
										delete_sport($_POST['sport_id']);
									}
								}

								$sports = list_sports();
								for ($i=0; $i <count($sports) ; $i++) {
							?>
							<tr>
								<form method="POST">
									<td>
										<?php 
										if (! isset($_POST['editer_sport' . $i]) OR isset($_POST['annuler_editer_sport'])) {
											echo $sports[$i]['name'];
										}
										else {
										?>
											<input type="input" name="sport_name" value="<?php echo $sports[$i]['name'] ?>" required="required" minlength="5" maxlength="50" />
										<?php
										}
										?>
									</td>
									<td><input type="hidden" name="sport_id" value="<?php echo $sports[$i]['id'] ?>" /></td>
									<td>
										<?php
										if (! isset($_POST['editer_sport' . $i])) {
										?>
										<input type="submit" name="editer_sport<?php echo $i ?>" value="Editer" />
										<?php
										}
										else {
										?>
										<input type="submit" name="valider_editer_sport" value="Valider" />
										<input type="button" value="Annuler" onclick="location.href='gestion_sports.php'" />
										<?php
										}
										?>
									</td>
									<?php
									if (! isset($_POST['editer_sport' . $i])) {
									?>
									<td><input type="submit" name="delete_sport" value="Supprimer" /></td>
									<?php
									}
									?>
								</form>
							</tr>
							<?php
							}

							if (isset($_POST['ajouter_sport'])) {
      						?>
      						<tr>
								<form method="POST">
									<td><input type="input" name="name_sport" required="required" minlength="5" maxlength="50" /></td>
									<td>
										<input type="submit" name="valider_ajouter_sport" value="Valider" />
										<input type="button" value="Annuler" onclick="location.href='gestion_sports.php'" />
									</td>
								</form>
							</tr>
      						<?php
      						}
							?>
						</table>
					</div>
				</div>
			</div>
			<div class="site-cache" id="site-cache"></div>
		</div>
	</div>
</body>
</html>
<?php
}
?>
