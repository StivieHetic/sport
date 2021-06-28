<?php
session_start();
require('../queries/queries_match.php');
require('../queries/queries_pronostique.php');
require('../includes/function.php');
require('../includes/constant.php');
?>
<?php
if(isset($_POST['pronostique'])){
	$equipe_gagnante_cote = explode('/', $_POST['equipe_gagnante_cote']);
	$equipe_gagnante = $equipe_gagnante_cote[0];
	if ($equipe_gagnante == null) {
		$match_nul = 1;
		$equipe_gagnante_id = null;
	} else {
		$match_nul = 0;
		$equipe_gagnante_id = $equipe_gagnante;
	}
	$cote = $equipe_gagnante_cote[1];
	create_pronostique($_POST['match_id'], $match_nul, $equipe_gagnante_id, $cote, $_POST['analyse'], $_POST['mise'], time(), isset($_POST['prive']), get_session('user_id'));
}
$matchs = find_matchs_futurs_sans_pronostiques(get_session('user_id'));
	//$matchs = find_matchs_futurs_sans_pronostiques(time(), get_session('user_id'));
?>
<div class="list-match">
	<?php
		for ($i = 0; $i < count($matchs); $i = $i + 1) {
	?>
	<div class="match">
		<form method="POST">
			<div class="header-match btn-booking">
				<span><?php echo $matchs[$i]['sport_name']; ?> - <?php echo $matchs[$i]['championnat_name']; ?></span>	
				<span><?php echo $matchs[$i]['date_match']; ?></span>
				<div style="display: flex; align-items: center;">
					<input type="checkbox" name="prive" id="prive" /><label for="prive">Privé</label>
					<div class="combinaison" style="padding-left: 5px;">15/20</div>
				</div>
			</div>
			<div class="other">
				<div class="two-team">
					<div class="team-1">
						<span><?php echo $matchs[$i]['equipe_1_nom']; ?></span>
					</div>
					<div class="combine">
						<input type="checkbox" style="cursor: pointer;">
					</div>
					<div class="team-2">
						<span><?php echo $matchs[$i]['equipe_2_nom']; ?></span>
					</div>
				</div>
			</div>
			<div class="information-cote">
				<div class="box-cote">
					<select name="equipe_gagnante_cote"  required="required" style="border: 1px solid #d5d5d5; border-top: none;">
						<option></option>
						<option value="<?php echo $matchs[$i]['equipe_1_id'] . "/" . $matchs[$i]['cote_equipe_1'] ?>"><?php echo $matchs[$i]['equipe_1_nom'] . " / " . $matchs[$i]['cote_equipe_1'] ?></option>
						<option value="<?php echo null . "/" .  $matchs[$i]['cote_match_nul'] ?>"><?php echo 'Match nul' . " / " . $matchs[$i]['cote_match_nul'] ?></option>
						<option value="<?php echo $matchs[$i]['equipe_2_id'] . "/" . $matchs[$i]['cote_equipe_2'] ?>"><?php echo $matchs[$i]['equipe_2_nom'] . " / " . $matchs[$i]['cote_equipe_2'] ?></option>
					</select>
					<select style="border: 1px solid #d5d5d5; border-top: none; border-left: none; width: 10%;" placeholder="Confiance" required="required">
						<option></option>
						<option>1/10</option>
						<option>2/10</option>
						<option>3/10</option>
						<option>4/10</option>
						<option>5/10</option>
						<option>6/10</option>
						<option>7/10</option>
						<option>8/10</option>
						<option>9/10</option>
						<option>10/10</option>
					</select>
				</div>
				<div class="information">
					<textarea name="analyse" rows="2" minlength="150" maxlength="400" placeholder="Analyse" required="required" style="border: 1px solid #d5d5d5; border-top: none;"></textarea>
					<input name="mise" type="number" min="1" max="100" class="input-table" placeholder="€" required="required">
				</div>
				<div class="soumettre">
					<input type="hidden" name="match_id" value="<?php echo $matchs[$i]['match_id'] ?>" />
					<button type="submit" name="pronostique" class="btn" value="Valider" style="width: 100%; padding:10px;">Envoyer pronostique</button>
				</div>
			</div>
		</form>
	</div>
	<?php
	}
	?>
</div>


