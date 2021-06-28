<?php
session_start();
require ('../includes/function.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_sport.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_championnat.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_equipe.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_match.php');
require($_SERVER['DOCUMENT_ROOT'] . '/queries/queries_pronostique.php');
require($_SERVER['DOCUMENT_ROOT'] . '/services/services_user.php');
require('../includes/function.php');
require('../includes/constant.php');

if(!is_admin(get_session('user_id'))) {
	header('location: ../profile.php');
	exit();
}
else {
?>
<?php  $title = "Pronostique";?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administration Match</title>
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
                <div class="holder" style="height: 627px;">
      				<div class="responsiveTbl">
      					<div class="lien_gestion">
      						<a href="gestion_sports.php" class="lien">Gestion sports</a><a href="gestion_championnats.php" class="lien">Gestion championnats</a><a href="gestion_equipes.php" class="lien">Gestion équipes</a>
      					</div>
	      				<form method="post">
	      					<input type="submit" name="ajouter_match" value="Ajouter">
						</form>
					<?php
					if (isset($_POST['valider_ajouter_match'])) {
							if($_POST['equipe1'] == $_POST['equipe2'])
							echo "Les équipes doivent être différentes <br />";
							else{
								$id = find_match($_POST['sport'], $_POST['championnat'], $_POST['equipe1'], $_POST['equipe2'], $_POST['date_match']);
      							if ($id == null) {
									create_match($_POST['sport'], $_POST['championnat'], $_POST['equipe1'], $_POST['equipe2'], $_POST['cote_equipe_1'], $_POST['cote_equipe_2'], $_POST['cote_match_nul'], $_POST['date_match']);
								}else {
  									echo "Erreur : un match avec ce sport, ce championnat, ces équipes et cette date existe déjà !";
  								}
							}
						}
      				?>
					<table class="table-responsive">
						<caption>Matchs</caption>
						<thead>
							<tr>
								<th></th>
								<th>Sport</th>
								<th>Championnat</th>
								<th>Equipe 1</th>
								<th>Equipe 2</th>
								<th>Résultat match</th>
								<th>Côte équipe 1</th>
								<th>Côte match nul</th>
								<th>Côte équipe 2</th>
								<th>Date Match</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (isset($_POST['valider_editer_matchs'])) {
								if($_POST['equipe1'] == $_POST['equipe2'])
									echo "<br /> Les équipes doivent être différentes";
								else {
									$id = find_match($_POST['sport'], $_POST['championnat'], $_POST['equipe1'], $_POST['equipe2'], $_POST['date_match']);
       								if ($id == null) {
       									$ok = true;
									} else {
										if ($_POST['match_id'] == $id) {
											$ok = true;
										} else {
											$ok = false;
										}
									}
									if ($ok) {
										update_match($_POST['sport'], $_POST['championnat'], $_POST['equipe1'], $_POST['equipe2'], $_POST['cote_equipe_1'], $_POST['cote_equipe_2'], $_POST['cote_match_nul'], $_POST['date_match'], $_POST['match_id']);
									} else {
  										echo "Erreur : un match avec ce sport, ce championnat, ces équipes et cette date existe déjà !";
  									}
								}
							}

							if (isset($_POST['valider_editer_matchs_equipe_gagnante'])) {
								$equipe_gagnante = $_POST['equipe_gagnante'];
								if ($equipe_gagnante == null) {
									$match_nul = 1;
									$equipe_gagnante_id = null;
								} else {
									$match_nul = 0;
									$equipe_gagnante_id = $equipe_gagnante;
								}
								update_match_equipe_gagnante($match_nul, $equipe_gagnante_id, $_POST['match_id']);

								// validation des pronostiques
								$pronostiques_matchs = find_equipes_gagnantes_match_pronostique($_POST['match_id']);
								for ($i = 0; $i < count($pronostiques_matchs); $i = $i + 1) {
									$match_nul_pronostique = $pronostiques_matchs[$i]['match_nul_pronostique'];
									$match_nul_match = $pronostiques_matchs[$i]['match_nul_match'];
									if($match_nul_pronostique == 1 AND $match_nul_match == 1) {
										$resultat_pronostique = 1;
									}
									else if ($match_nul_pronostique == 0 AND $match_nul_match == 0) {
					                    if($pronostiques_matchs[$i]['pronostique_equipe_gagnante_id'] == $pronostiques_matchs[$i]['match_equipe_gagnante_id'])
					                    {
					                      $resultat_pronostique = 1;
					                    }else{
					                      $resultat_pronostique = 0;
					                    }
				                	} else {
				                		$resultat_pronostique = 0;
				                	}
				                   
				                    update_pronostique($resultat_pronostique, $pronostiques_matchs[$i]['pronostique_id']);
				                  }
							}

							if (isset($_POST['delete_match'])) {
								$delete_ok = true;
								$pronostiques = find_pronostiques_by_match_id($_POST['match_id']);
								if ($pronostiques != null) {
									$delete_ok = false;
									
									echo "<ul class='error'>";
									for ($i=0; $i <count($pronostiques) ; $i++) {
										echo "Erreur : ce match ne peut pas être supprimé, il est référencé par les pronostiques :</<li class='list_error'>" . $pronostiques[$i]['sport_name'] . ", " . $pronostiques[$i]['championnat_name'] . ", " . $pronostiques[$i]['equipe_1_nom'] . ", " . $pronostiques[$i]['equipe_2_nom'] . ", " . $pronostiques[$i]['date_match'] . ", " . ($pronostiques[$i]['match_nul_pronostique'] == 1 ? "match nul" : $pronostiques[$i]['equipe_gagnante_pronostique_nom']) . ", " . $pronostiques[$i]['date_pronostique'] . ", " . $pronostiques[$i]['pseudo'] . "</li><br>";
									}
									echo "</ul>";
								}

								if ($delete_ok) {
									delete_match($_POST['match_id']);
								}
							}

							$nombreMatchs = count_matchs();
							$perPage = 14;
							$nbPage = ceil ($nombreMatchs / $perPage);

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

							$matchs = list_matchs_pagination($firstOffPage, $perPage);
							for ($i=0; $i <count($matchs) ; $i++){
							?>
							<tr>
							<form method="POST">
								<td><input type="hidden" name="match_id" value="<?php echo $matchs[$i]['match_id'] ?>" /></td>
								<td>
								<?php 
								if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs'])) {
									echo $matchs[$i]['sport_name'];
								}
								else{
								?>
									<select name="sport">
									<?php
										$sports = list_sports();
										for ($j = 0; $j < count($sports); $j = $j + 1) {
										if ($matchs[$i]['sports_id'] == $sports[$j]['id']) {
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
									if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs']))  {
											echo $matchs[$i]['championnat_name'];
									}
									else {
									?>
									<select name="championnat">
									<?php
										$championnats = list_championnats();
										for ($j = 0; $j < count($championnats); $j = $j + 1) {
											if ($matchs[$i]['championnats_id'] == $championnats[$j]['id']) {
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
									if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs'])) {
										echo $matchs[$i]['equipe1_nom'];
									}
									else {
									?>
									<select name="equipe1">
									<?php
										$equipes = list_equipes();
										for ($j = 0; $j < count($equipes); $j = $j + 1) {
											if ($matchs[$i]['equipe1_id'] == $equipes[$j]['id']) {
									?>
												<option value="<?php echo $equipes[$j]['id'] ?>" selected="selected"><?php echo $equipes[$j]['nom'] ?></option>
									<?php
											}
											else {
									?>
												<option value="<?php echo $equipes[$j]['id'] ?>"><?php echo $equipes[$j]['nom'] ?></option>
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
									if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs']))  {
										echo $matchs[$i]['equipe2_nom'];
									}
									else {
									?>
									<select name="equipe2">
									<?php
										for ($j = 0; $j < count($equipes); $j = $j + 1) {
											if ($matchs[$i]['equipe2_id'] == $equipes[$j]['id']) {
									?>
												<option value="<?php echo $equipes[$j]['id'] ?>" selected="selected"><?php echo $equipes[$j]['nom'] ?></option>
									<?php
											}
											else {
									?>
												<option value="<?php echo $equipes[$j]['id'] ?>"><?php echo $equipes[$j]['nom'] ?></option>
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
										if (! isset($_POST['editer_matchs_equipe_gagnante' . $i]) OR isset($_POST['annuler_editer_matchs_equipe_gagnante'])){
											$match_nul = $matchs[$i]['match_nul'];
											if ($match_nul != null) {
												if ($match_nul == 1) {
													echo "Match nul";
												} else {
													echo $matchs[$i]['equipe_gagnante_nom'];
												}
											}
										} else {
									?>
										<select name="equipe_gagnante">
											<option value="<?php echo $matchs[$i]['equipe1_id'] ?>"><?php echo $matchs[$i]['equipe1_nom'] ?></option>
											<option value="<?php echo null ?>"><?php echo 'Match nul' ?></option>
											<option value="<?php echo $matchs[$i]['equipe2_id'] ?>"><?php echo $matchs[$i]['equipe2_nom'] ?></option>
										</select>
									<?php
									}
									?>
									</td>
									<td>
									<?php 
										if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs'])){
											echo $matchs[$i]['cote_equipe_1'];
										}else{
									?>
										<input type="number" name="cote_equipe_1" class="input_cote" value="<?php echo $matchs[$i]['cote_equipe_1']; ?>" required="required">
									<?php 
										}
									?>
									</td>
									<td>
									<?php 
										if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs'])){
											echo $matchs[$i]['cote_match_nul'];
										}else{
									?>
										<input type="number" name="cote_match_nul" class="input_cote" value="<?php echo $matchs[$i]['cote_match_nul']; ?>" required="required">
									<?php 
										}
									?>
									</td>
									<td>
									<?php 
										if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs'])){
											echo $matchs[$i]['cote_equipe_2'];
										}else{
									?>
										<input type="number" name="cote_equipe_2" class="input_cote" value="<?php echo $matchs[$i]['cote_equipe_2']; ?>" required="required">
									<?php 
										}
									?>
									</td>
									<td>
									<?php
									if (! isset($_POST['editer_matchs' . $i]) OR isset($_POST['annuler_editer_matchs']))  {
										echo $matchs[$i]['date_match'];
									}
									else {
									?>
										<input type="datetime" name="date_match" value="<?php echo $matchs[$i]['date_match']; ?>" required="required">
										<!--<input type="datetime" name="date_match" value="2019-07-12 22:45" required="required">-->
									<?php
									}
									?>
									</td>
									<td>
									<?php
									if (! isset($_POST['editer_matchs_equipe_gagnante' . $i])) {
										if (! isset($_POST['editer_matchs' . $i])) {
									?>
									<input type="submit" name="editer_matchs<?php echo $i ?>" value="Editer" />
									<?php
									}else{
									?>
									<input type="submit" name="valider_editer_matchs" value="Valider" />
									<input type="button" value="Annuler" onclick="location.href='gestion_matchs.php'" />
									<?php
									}
									}
									?>
									</td>
									<td>
									<?php
									if (! isset($_POST['editer_matchs' . $i])) {
										if (! isset($_POST['editer_matchs_equipe_gagnante' . $i])) {
									?>
									<input type="submit" name="editer_matchs_equipe_gagnante<?php echo $i ?>" value="Editer équipe gagnante" />
									<?php
									}else{
									?>
									<input type="submit" name="valider_editer_matchs_equipe_gagnante" value="Valider" />
									<input type="button" value="Annuler" onclick="location.href='gestion_matchs.php'" />
									<?php
									}
									}
									?>
									</td>
									<?php
										if (! isset($_POST['editer_matchs' . $i]) AND ! isset($_POST['editer_matchs_equipe_gagnante' . $i])) {
									?>
									<td><input type="submit" name="delete_match" value="Supprimer" /></td>
								<?php 
								}
								?>
							</form>
						</tr>
						<?php
						}
						
						if (isset($_POST['ajouter_match'])) {
		  				?>
		  				<tr>
				    		<form method="POST">
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
									<select name="equipe1">
								<?php
									$equipes = list_equipes();
									for ($i = 0; $i < count($equipes); $i = $i + 1) {
								?>
									<option value="<?php echo $equipes[$i]['id'] ?>"><?php echo $equipes[$i]['nom'] ?></option>
								<?php
								}
								?>
									</select>
								</td>

								<td>
									<select name="equipe2">
								<?php
									for ($i = 0; $i < count($equipes); $i = $i + 1) {
								?>
									<option value="<?php echo $equipes[$i]['id'] ?>"><?php echo $equipes[$i]['nom'] ?></option>
								<?php
								}
								?>
									</select>
								</td>
								
								<!--<label>Choisis une formule</label>
								<input type="text" name="formule" required="required" minlength="2" maxlength="40"  placeholder="Choisir une formule">-->
								
								<td></td>
							
								<td><input type="text" name="cote_equipe_1" required="required" size="2" maxlength="5"></td>

								<td><input type="text" name="cote_match_nul" required="required" size="2" maxlength="5"></td>

								<td><input type="text" name="cote_equipe_2"  required="required" size="2" maxlength="5"></td>

								<td><input type="datetime-local" name="date_match" required="required"></td>
							
		  						<td>
		  							<input type="submit" name="valider_ajouter_match" value="Valider">
		  							<input type="button" value="Annuler" onclick="location.href='gestion_matchs.php'" />
		  						</td>
							</form>
						</tr>
						<?php 
		  				}
						?>
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
							<li class="pagination-item active"><a href="?p=<?php echo $k ?>"><?php echo $k ?></a></li>
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
</body>
</html>
<?php
}
?>
