<?php
session_start();
require ('../includes/function.php');
require ('../includes/constant.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_equipe.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_championnat.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_match.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_pronostique.php');
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
	<title>Gestion des equipes</title>
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
      					<a href="gestion_sports.php" class="lien">Gestion sports</a><a href="gestion_championnats.php" class="lien">Gestion championnats</a><a href="gestion_matchs.php" class="lien">Gestion matchs</a>
      					</div>
						<form method="post">
  							<input type="submit" name="ajouter_equipe" value="Ajouter">
  						</form>
						<?php
						if (isset($_POST['valider_ajouter_equipe'])) {
							$id = find_equipe_by_name($_POST['name_equipe']);
      						if ($id == null) {
	  							create_equipe($_POST['name_equipe'], $_POST['championnat']);
	  						}else {
  								echo "Erreur : une équipe avec ce nom existe déjà !";
  							}
  						}
	      				?>
							<table class="table-responsive">
								<caption>Equipes</caption>
								<thead>
									<tr>
										<th>Equipe</th>
										<th></th>
										<th>Championnat</th>
										<th>Sport</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										if (isset($_POST['valider_editer_equipe'])) {
											$id = find_equipe_by_name($_POST['equipe_name']);
      										if ($id == null) {
												$ok = true;
											} else {
												if ($_POST['equipe_id'] == $id) {
													$ok = true;
												} else {
													$ok = false;
												}
											}
											if ($ok) { 
												update_equipe($_POST['equipe_name'], $_POST['championnat'], $_POST['equipe_id']);
											} else {
				  								echo "Erreur : une équipe avec ce nom existe déjà !";
				  							}
										}

										if (isset($_POST['delete_equipe'])) {
											$delete_ok = true;
											$matchs = find_matchs_by_equipe_id($_POST['equipe_id']);
											$pronostiques = find_pronostiques_by_equipe_gagnante_id($_POST['equipe_id']);
											if ($matchs != null) {
												$delete_ok = false;
												echo "Erreur : cette équipe ne peut pas être supprimée, elle est référencée par les matchs :";
												echo "<ul>";
												for ($i=0; $i <count($matchs) ; $i++) {
													echo "<li>" . $matchs[$i]['sports_name'] . ", " . $matchs[$i]['championnats_name'] . ", " . $matchs[$i]['equipes_1_nom'] . ", " . $matchs[$i]['equipes_2_nom'] . ", " . $matchs[$i]['date_match'] . "</li>";
												}
												echo "</ul>";
											}

											if ($pronostiques != null) {
												$delete_ok = false;
												echo "Erreur : cette équipe ne peut pas être supprimée, elle est référencée par les pronostiques :";
												echo "<ul>";
												for ($i=0; $i <count($pronostiques) ; $i++) {
													echo "<li>" . $pronostiques[$i]['sport_name'] . ", " . $pronostiques[$i]['championnat_name'] . ", " . $pronostiques[$i]['equipes__nom'] . ", " . $pronostiques[$i]['equipe_2_nom'] . ", " . $pronostiques[$i]['date_match'] . ", " . $pronostiques[$i]['equipe_gagnante_pronostique_nom'] . ", " . $pronostiques[$i]['date_pronostique'] . ", " . $pronostiques[$i]['pseudo'] . "</li>";
												}
												echo "</ul>";
											}

											if ($delete_ok) {
												delete_equipe($_POST['equipe_id']);
											}
										}

										$nombreEquipes = count_equipes();
										$perPage = 14;
										$nbPage = ceil ($nombreEquipes / $perPage);

										if (isset($_GET['p']) && !empty($_GET['p']) && ctype_digit($_GET['p']) == 1) {
											if ($_GET['p'] > $nbPage) {
												$current = $nbPage;
											} else {
												$current = $_GET['p'];
											}
										} else {
											$current = 1;
										}

										$firstOffPage = ($current - 1) * $perPage;

										$equipes = list_equipes_pagination($firstOffPage, $perPage);

										for ($i = 0; $i < count($equipes) ; $i++) {
									?>
									<tr>
										<form method="POST">
											<td>
											<?php 
											if (! isset($_POST['editer_equipe' . $i]) OR isset($_POST['annuler_editer_equipe'])) {
												echo $equipes[$i]['nom'];
											}
											else{
											?>
												<input type="input" name="equipe_name" value="<?php echo $equipes[$i]['nom'] ?>" required="required" minlength="1" maxlength="30" />
											<?php
											}
											?>
											</td>
											<td><input type="hidden" name="equipe_id" value="<?php echo $equipes[$i]['id'] ?>" /></td>
											<td>
											<?php
											if (! isset($_POST['editer_equipe' . $i]) OR isset($_POST['annuler_editer_equipe']))  {
													echo $equipes[$i]['championnat_name'];
											}
											else {
											?>
											<select name="championnat">
											<?php
												$championnats = list_championnats();
												for ($j = 0; $j < count($championnats); $j = $j + 1) {
													if ($equipes[$i]['championnat_id'] == $championnats[$j]['id']) {
											?>
														<option value="<?php echo $championnats[$j]['id'] ?>" selected="selected"><?php echo $championnats[$j]['name'] ?></option>
											<?php
													}
													else {
											?>
														<option value="<?php echo $championnats[$j]['id'] ?>"><?php echo $championnats[$j]['name'] ?></option>
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
													echo $equipes[$i]['sport_name'];
												?>
											</td>
											<td>
												<?php 
												if (! isset($_POST['editer_equipe' . $i])) {
												?>
												<input type="submit" name="editer_equipe<?php echo $i ?>" value="Editer" />
												<?php
												}else{
												?>
												<input type="submit" name="valider_editer_equipe" value="Valider" />
												<input type="button"  value="Annuler" onclick="location.href='gestion_equipes.php'" />
												<?php
												}
												?>
											</td>
											<?php
												if (! isset($_POST['editer_equipe' . $i])) {
											?>
											<td><input type="submit" name="delete_equipe" value="Supprimer" /></td>
											<?php 
											}
											?>
										</form>
										<?php
										}


				      					if (isset($_POST['ajouter_equipe'])) {
				      					?>
				      					<tr>
					      					<form method="post">
					      						<td><input type="text" name="name_equipe" required="required" minlength="2" maxlength="30"></td>
					      						<td></td>
					      						<td>
					      							<select name="championnat">
												<?php
													$championnats = list_championnats();
													for ($i = 0; $i < count($championnats); $i = $i + 1) { 
												?>
													<option value="<?php echo $championnats[$i]['id'] ?>"><?php echo $championnats[$i]['name'] ?></option>
												<?php
												}
												?>
													</select>
												</td>
					      						<td>
						      						<input type="submit" name="valider_ajouter_equipe" value="Valider">
						      						<input type="button"  value="Annuler" onclick="location.href='gestion_equipes.php'" />
					      						</td>
					      					</form>
					      				</tr>
					      				<?php 
				      					}
										?>
									</tr>
								</tbody>
							</table>
						</div>			
					</div>

					<ul class="pagination">
						<?php
							$classPrevious = '';
							if ($current == 1) {
								$classPrevious = "disabled";
							}

							$numeroPagePrecedent;
							if ($current != 1) {
								$numeroPagePrecedent = $current - 1;
							} else {
								$numeroPagePrecedent = $current;
							}
						?>
						<li class="pagination-item">
							<a href="?p=<?php echo $numeroPagePrecedent ?>">&laquo;</a>
						</li>

						<?php
						for ($k = 1; $k <= $nbPage; $k++) {
							if ($k == $current) {
						?>
								<li class="pagination-item"><a href="?p=<?php echo $k ?>"><?php echo $k ?></a></li>
						<?php
							} else {
						?>
								<li class="pagination-item"><a href="?p=<?php echo $k ?>"><?php echo $k ?></a></li>
						<?php 
							}
						}

						$classNext = '';
						if ($current == $nbPage) {
							$classNext = "disabled";
						}

						$numeroPageSuivant;
						if ($current != $nbPage) {
							$numeroPageSuivant = $current + 1;
						} else {
							$numeroPageSuivant = $current;
						}
						?>
						<li class="pagination-item">
							<a href="?p=<?php echo $numeroPageSuivant ?>">&raquo;</a>
						</li>
					</ul>

      			</div>
      			<div class="site-cache" id="site-cache"></div>
      		</div>
		</div>
	</div>
</body>
</html>
<?php
}
?>