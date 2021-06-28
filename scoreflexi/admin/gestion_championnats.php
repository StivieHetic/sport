<?php
session_start();
require ('../includes/function.php');
require ('../includes/constant.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_championnat.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_sport.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_equipe.php');
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
	<title>Gestion des championnats</title>
	<link rel="stylesheet" type="text/css" href="../asset/css/css_user_style/table_matchs.css">
	<link rel="stylesheet" type="text/css" href="/asset/css/css_header/header-site.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
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
						<a href="gestion_sports.php" class="lien">Gestion sports</a><a href="gestion_equipes.php" class="lien">Gestion équipes</a><a href="gestion_matchs.php" class="lien">Gestion matchs</a>
						</div>
						<form method="post">
      						<input type="submit" name="ajouter_championnat" value="Ajouter">
      					</form>

      					<?php
      					if (isset($_POST['valider_ajouter_championnat'])) {
      						$id = find_championnat_by_name($_POST['name_championnat']);
      						if ($id == null) {
  								create_championnat($_POST['name_championnat'], $_POST['sport']);
  							} else {
  								echo "Erreur : un championnat avec ce nom existe déjà !";
  							}
  						}
      					?>
						<table class="table-responsive">
							<caption>Championnats</caption>
							<thead>
								<tr>
									<th>Championnat</th>
									<th></th>
									<th>Sport</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<?php 
								if (isset($_POST['valider_editer_championnat'])) {
									$id = find_championnat_by_name($_POST['championnat_name']);
      								if ($id == null) {
										$ok = true;
									} else {
										if ($_POST['championnat_id'] == $id) {
											$ok = true;
										} else {
											$ok = false;
										}
									}
									if ($ok) {
										update_championnat($_POST['championnat_name'], $_POST['sport'], $_POST['championnat_id']);
									} else {
		  								echo "Erreur : un championnat avec ce nom existe déjà !";
									}
								}

								if (isset($_POST['delete_championnat'])) {
									$delete_ok = true;
									$equipes = find_equipes_by_championnat_id($_POST['championnat_id']);
									$matchs = find_matchs_by_championnat_id($_POST['championnat_id']);
									if ($equipes != null) {
										$delete_ok = false;
										echo "Erreur : ce championnat ne peut pas être supprimé, il est référencé par les équipes :";
										echo "<ul>";
										for ($i=0; $i <count($equipes) ; $i++) {
											echo "<li>" . $equipes[$i]['nom'] . "</li>";
										}
										echo "</ul>";
									}

									if ($matchs != null) {
										$delete_ok = false;
										echo "Erreur : ce championnat ne peut pas être supprimé, il est référencé par les matchs :";
										echo "<ul>";
										for ($i=0; $i <count($matchs) ; $i++) {
											echo "<li>" . $matchs[$i]['sports_name'] . ", " . $matchs[$i]['championnats_name'] . ", " . $matchs[$i]['equipes_1_nom'] . ", " . $matchs[$i]['equipes_2_nom'] . ", " . $matchs[$i]['date_match'] . "</li>";
										}
										echo "</ul>";
									}

									if ($delete_ok) {
										delete_championnat($_POST['championnat_id']);
									}

								}


								$championnats = list_championnats();
								for ($i=0; $i <count($championnats) ; $i++) {
							?>	
							<tr>
								<form method="POST">
									<td>
										<?php 
										if (! isset($_POST['editer_championnat' . $i]) OR isset($_POST['annuler_editer_championnat'])) {
											echo $championnats[$i]['name'];
										}
										else {
										?>
											<input type="input" name="championnat_name" value="<?php echo $championnats[$i]['name'] ?>" required="required" minlength="5" maxlength="50" />
										<?php
										}
										?>
									</td>
									<td><input type="hidden" name="championnat_id" value="<?php echo $championnats[$i]['id'] ?>" /></td>
									<td>
										<?php
											if (! isset($_POST['editer_championnat' . $i]) OR isset($_POST['annuler_editer_championnat'])) {
												echo $championnats[$i]['sport_name'];
											}
											else {
										?>
										<select name="sport">
											<?php
												$sports = list_sports();
												for ($j = 0; $j < count($sports); $j = $j + 1) {
													if ($championnats[$i]['sport_id'] == $sports[$j]['id']) {
											?>
														<option value="<?php echo $sports[$j]['id'] ?>" selected="selected"><?php echo $sports[$j]['name'] ?></option>
											<?php
													}
													else {
											?>
														<option value="<?php echo $sports[$j]['id'] ?>"><?php echo $sports[$j]['name'] ?></option>
											<?php
													}
												}
											?>
										</select>
										<?php
											}
										?>
									</td>
									<td>
										<?php
										if (! isset($_POST['editer_championnat' . $i])) {
										?>
										<input type="submit" name="editer_championnat<?php echo $i ?>" value="Editer" />
										<?php
										}
										else {
										?>
										<input type="submit" name="valider_editer_championnat" value="Valider" />
										<input type="button" value="Annuler" onclick="location.href='gestion_championnats.php'" />
										<?php
										}
										?>
									</td>
									<?php
									if (! isset($_POST['editer_championnat' . $i])) {
									?>
									<td><input type="submit" name="delete_championnat" value="Supprimer" /></td>
									<?php
									}
									?>
								</form>
							</tr>
							<?php
							}

	      					if (isset($_POST['ajouter_championnat'])) {
	      					?>
	      					<tr>
		      					<form method="post">
		      						<td><input type="text" name="name_championnat" required="required" minlength="1" maxlength="50"></td>
		      						<td></td>
		      						<td>
		      							<select name="sport">
									<?php
										$sports = list_sports();
										for ($i = 0; $i < count($sports); $i = $i + 1) { 
									?>
										<option value="<?php echo $sports[$i]['id'] ?>"><?php echo $sports[$i]['name'] ?></option>
									<?php
									}
									?>
										</select>
									</td>
			      					<td>
			      						<input type="submit" name="valider_ajouter_championnat" value="Valider">
			      						<input type="button" value="Annuler" onclick="location.href='gestion_championnats.php'" />
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