<?php
session_start();
require('../queries/queries_match.php');
require('../includes/function.php');
require('../includes/constant.php');
?>
<?php
	$matchs = find_matchs_passes_sans_pronostiques(get_session('user_id'));
?>
<div class="list-match">
	<?php
		for ($i = 0; $i < count($matchs); $i = $i + 1) {
	?>
	<div class="match">
		<div class="prognostic-category" style="box-shadow: 0px 0 7px -5px #333; background: #00cc00; color: white;">
	        <div style="font-size: 13px; display: flex; justify-content: space-between; line-height:2em; font-weight: 700; padding-right: 5px; padding-left: 5px;">
	            <div style="padding-bottom: .25em; padding-top: .25em;"><span><?php echo $matchs[$i]['sport_name']; ?> - <?php echo $matchs[$i]['championnat_name']; ?></span></div>
	            <div style="padding-bottom: .25em; padding-top: .25em;"><span></span></div>
	            <div style="padding-bottom: .25em; padding-top: .25em;"><span></span></div>
	        </div>
        </div>
        <div style="background: white;">
          <div class="header" style="font-size: 12px; overflow: hidden; line-height: 24px; padding: 12px 12px 0; border-top:1px solid #f1f1f1; ">
            <div class="title-versus" style="display: flex; font-size: 13px;">
              <div class="team-1"><?php echo $matchs[$i]['equipe_1_nom']; ?><span style="padding-left: 5px"><?php echo $matchs[$i]['cote_equipe_1']; ?></span></div>
              <div class="versus" style="padding-left: 5px; padding-right: 5px;">Nul <?php echo $matchs[$i]['cote_match_nul']; ?></div>
              <div class="team-2"><?php echo $matchs[$i]['equipe_2_nom']; ?><span style="padding-left: 5px;"><?php echo $matchs[$i]['cote_equipe_2']; ?></span></div>
            </div>
            <div style="text-align: center; font-size: 11px; color: #7a899d; margin-top: 7px;"><?php echo $matchs[$i]['date_match']; ?></div>
          </div>
          <div class="meta" style="line-height: 20px; padding:12px 0px 0px; text-align: center;">
            <div class="result" style="font-size: 13px; color: #43484e; padding: 15px; background: #eff2f9; font-weight: 700; position: relative; z-index: 0;">
              <span>Résultat du Match : </span>
             	<?php
					if ($matchs[$i]['match_nul'] == 1) {
						echo "Match nul";
					} else {
						echo $matchs[$i]['equipe_gagnante_nom'];
					}
				?>
            </div>
          </div>
        </div>
	</div>
	<?php
	}
	?>
</div>

