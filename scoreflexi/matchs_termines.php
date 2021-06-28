<?php
session_start();
require('queries/queries_match.php');
require('includes/function.php');
require('includes/constant.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Matchs</title>
	<meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="asset/css/css_header/header-site.css">
	<link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_matchs_termines.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/jquery_site.js"></script>
</head>
<body>

       			<div class="container-table">
  					<div class="responsiveTbl">
      					<?php
							$matchs = find_matchs_passes_sans_pronostiques(get_session('user_id'));
						?>
						<table class="table-responsive">
					        <thead>
					            <tr>
					              	<th>Sport</th>
									<th>Championnat</th>
									<th>Equipe 1</th>
									<th>Equipe 2</th>
									<th>Côte équipe 1</th>
									<th>Côte équipe 2</th>
									<th>Côte match nul</th>
									<th>Equipe gagnante</th>
									<th>Date</th>
					            </tr>
					        </thead>
					        <tbody>
						        <?php
		           					for ($i = 0; $i < count($matchs); $i = $i + 1) {
		       					?>
				       			<tr>
									<td><?php echo $matchs[$i]['sports_name']; ?></td>
									<td><?php echo $matchs[$i]['championnat_name']; ?></td>
									<td><?php echo $matchs[$i]['equipe_1_nom']; ?></td>
									<td><?php echo $matchs[$i]['equipe_2_nom']; ?></td>
									<td><?php echo $matchs[$i]['cote_equipe_1']; ?></td>
									<td><?php echo $matchs[$i]['cote_equipe_2']; ?></td>
									<td><?php echo $matchs[$i]['cote_match_nul']; ?></td>
									<td>
										<?php
											if ($matchs[$i]['match_nul'] == 1) {
												echo "Match nul";
											} else {
												echo $matchs[$i]['equipe_gagnante_nom'];
											}
										?>
									</td>
									<td><?php echo $matchs[$i]['date_match']; ?></td>
								</tr>
								<?php
								}
								?>
							</tbody>
		     			</table>
					</div>
      			</div>	

</body>
</html>