<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/services/services_user.php');
require('queries/queries_pronostique.php');
require('queries/queries_user.php');
require('queries/queries_mise.php');
require('queries/queries_message.php');
require('services/services_pronostiques.php');
require('services/services_user.php');
require('queries/queries_match.php');
require('includes/function.php');
require('includes/constant.php');

$user_id = $_SESSION["user_id"];
$id = isset($_SESSION["id"]) ? $_SESSION["id"] : $_SESSION["user_id"];
//echo $id;
//echo $_SESSION['pseudo'];

$users = find_user_by_id($id);
if($users == null) {
	header('location: index.php');
  exit();
} else {
  $pronostiques = find_pronostiques_by_user($id);
}
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
    header('location: ../profile.php');
  }
$matchs = find_matchs_futurs_sans_pronostiques(get_session('user_id'));
  //$matchs = find_matchs_futurs_sans_pronostiques(time(), get_session('user_id'));
?>
<?php  $title = $users['pseudo'];?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<title><?= isset($title) ? $title . ' - '.WEBSITE_NAME : WEBSITE_NAME.'- Meilleurs site de pronos ';?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_profile.css">
  <link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_match.css">
  <link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_matchs_termines.css">
  <link rel="stylesheet" type="text/css" href="asset/css/css_user_style/css_matchs_suivis.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/js/jquery_site.js"></script>
</head>
<body style="margin: 0px; padding:0px; height: 100vh">
  <div class="top" style= "position: fixed; height: 3em;  background-color: #00cc00; width: 100%; z-index: 100;">
    <div class="nav-bars" style="display: flex; align-items: center; height: 48px; padding-right: 5px; padding-left: 5px;">
    <div style="display: flex; justify-content: space-between; width: 100%;  align-items: center;">
      <div id="lien">
        <ul id="tabs-1" class="navmenu-1" style="display: flex; list-style: none; padding: 0px; justify-content: space-between; width: 500px;">
          <li><a href="mode/premium.php">Premium</a></li>
          <li><a href="#">Concours</a></li>
          <li><a href="#">Evènement</a></li>
        </ul>
      </div>
      <?php 
      if(!is_pronostiqueur($id)) {
      ?>
      <div style="width: 420px; margin-right: 150px;">
        <input type="text" id="search-box" placeholder="Recherche Tispter..." style="width: 100%; padding: 6px; font-size: 12px; border-width: 1px; border-color: #CCCCCC; background-color: #FFFFFF; color: #000000; border: none; outline: none; /*box-shadow: 0px 0px 5px rgba(66,66,66,.75);*/">
        <div id="display-result" style="display: none; position: fixed; width: 420px; background: white; box-shadow: 0 6px 16px rgba(0,0,0,.2),0 0 4px rgba(0,0,0,.05); margin-top: 12px;">
          <div class="display-box-user">
            <a href="http://google.fr">
              <img src="image/61235_34.png" width="20" height="20">&nbsp;Squady
            </a>
          </div>
          <div class="display-box-user">
            <a>
              <img src="image/61235_34.png" width="20" height="20">&nbsp;Ikizz
            </a>
          </div>
          <div class="display-box-user">
            <img src="image/61235_34.png" width="20" height="20">&nbsp;Pronos_foot
          </div>
          <div class="display-box-user">
            <img src="image/61235_34.png" width="20" height="20">&nbsp;Mathos250
          </div>
          <div class="display-box-user">
            <img src="image/61235_34.png" width="20" height="20">&nbsp;nlpronostic95
          </div>
        </div>
      </div>
      <?php
      }
      ?>
      <div style="display: flex; justify-content: space-around;">
        <div id="noti_Counter" style="display: none;">7</div>
        <div id="noti_Button"><i class="fa fa-user" aria-hidden="true"></i></div> 
        <div id="notifications">
          <h3 class="pseudo"><?php echo $_SESSION['pseudo']; ?></h3>
          <div class="menu-user">
            <ul id="tabs" class="navmenu-3">
              <?php if(is_logged_in()): ?> 
              <?php 
                if(!is_pronostiqueur($user_id)) {
              ?>
              <li><a href="#">Mon profil</a></li>
              <?php
              }
              ?>
              <?php 
                if(is_pronostiqueur($user_id)) {
              ?>
              <li><a href="#contenu/matchs_a_venir">Match à venir</a></li>
              <?php
              }  
              ?>
              <?php 
                if(is_pronostiqueur($user_id)) {
              ?>
              <li><a href="#contenu/matchs_termines">Match Terminés</a></li>
              <?php
              }
              ?>
              <?php 
                if(is_pronostiqueur($user_id)) {
              ?>
              <li><a href="#contenu/abonnes">Abonnés</a></li>
              <?php
              } else {
              ?>
              <li><a href="#">Abonnement</a></li>
              <?php
              }
              ?>
              <?php 
                if(is_admin($user_id)) {
              ?>
              <li><a href="#">Administration</a></li>
              <?php
              }
              ?>
              <?php endif;?>
              <li><a href="#contenu/parametre">Paramètre</a></li>
              <li><a href="logout.php">Deconnexion</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="left" style="position: fixed; background: white; top:3em; width: 245px; height: 100%; padding-bottom: 122px; z-index: 999;">
  <?php 
  if(is_pronostiqueur($id)) {
  ?>
  <?php 
    if (isset($_POST['envoyer'])){
        create_message($_POST['message_chat'], $id);
      }
    ?>
    <div class="messages">
      <?php
        $messages = find_messages($id);
        for ($i=0; $i < count($messages) ; $i++) { 
      ?>
      <div class="message-chat">
        <span class="author"><?php echo $messages[$i]['pseudo']; ?></span> :
        <span class="content-text"><?php echo $messages[$i]['content']; ?></span>
        <br/>
      </div>
      <?php
      }
      ?>
    </div>
    <div>
      <form method="POST">
        <textarea class="texte" name="message_chat" placeholder="Envoyer un message" rows="1" required="required"></textarea>
        <div class="validee" style="text-align: left;">
          <button type="submit" name="envoyer" class="btn">Envoyer</button>
        </div>
      </form>
    </div>
  <?php
  } else {
  ?>
  <div class="block classement">
  <?php
    $users_avec_pronostiques_valides = find_users_avec_pronostiques_valides();
    for ($i=0; $i < count($users_avec_pronostiques_valides) ; $i++) {
        $reussite = calcul_reussite_by_user($users_avec_pronostiques_valides[$i]['id']);
        $users_avec_pronostiques_valides[$i]['reussite'] = $reussite;
        $points = calcul_points_by_user($users_avec_pronostiques_valides[$i]['id']);
        $users_avec_pronostiques_valides[$i]['points'] = $points;
    }
    //$users_avec_pronostiques_valides = sort_users_by_reussite_descending($users_avec_pronostiques_valides);
    $users_avec_pronostiques_valides = sort_users_by_points_descending($users_avec_pronostiques_valides);
    //$users_avec_pronostiques_valides = calcul_classement_by_user($users_avec_pronostiques_valides);
    $users_avec_pronostiques_valides = calcul_classement_points_by_user($users_avec_pronostiques_valides);
  ?>
  <table class="table">
    <tr>
      <th>#</th>
      <th>Pseudo</th>
      <th>Pts</th>
      <th>Réussite</th>
    </tr>
    <?php 
      for ($i=0; $i < count($users_avec_pronostiques_valides) ; $i++) {
    ?>
    <tr>
      <td><?php echo $users_avec_pronostiques_valides[$i]['classement']; ?></td>
      <td><?php echo $users_avec_pronostiques_valides[$i]['pseudo']; ?></td>
      <!-- <td><?php echo count_pronostiques_by_user($users_avec_pronostiques_valides[$i]['id']); ?></td> -->
      <td><?php echo $users_avec_pronostiques_valides[$i]['points']; ?></td>
      <td><?php echo $users_avec_pronostiques_valides[$i]['reussite'] . "%";?></td>
    </tr>
    <?php
    }
    ?>
  </table>
  </div>
  <?php
  }
  ?>
  </div>
  <div class="content-user" style="position: fixed; width: 100%; top: 3em; height: 4em; background: white;box-shadow: 0px 0 7px -5px #333; display: flex; align-items: center; justify-content: space-between; padding-right: 5px; padding-left: 5px; z-index: 50;">
    <div style="display: flex; justify-content: space-between; width: 60%; font-size: 14px; margin-left: 245px;"> 
      <ul id="tabs" class="navmenu-2" style="display: flex; justify-content: space-between; width: 100%;">
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
      <li class="active"><a href="#pronostique/publics">Pronostiques Publics</a></li>
      <?php
      } else {
      ?>
      <li class="active"><a href="#">Pronostiques Suivis</a></li>
      <?php
      }
      ?>
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
      <li><a href="#pronostique/privatises">Pronostiques Privatisés</a></li>
      <?php
      } 
      ?>
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
      <li><a href="#pronostique/termines">Pronostiques Terminés</a></li>
      <?php
      } else {
      ?>
      <li><a href="#">Mes Pronostiqueurs</a></li>
      <?php
      }
      ?>
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
      <li><a href="#pronostique/suivis">Pronostiques Suivis</a></li>
      <?php
      } else {
      ?>
      <li><a href="#">Pronostiqueurs Favoris</a></li>
      <?php
      }
      ?>
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
      <li><a href="#pronostique/statistique">Pronostique Statistiques</a></li>
      <?php
      } else {
      ?>
      <li><a href="#">Recommendation</a></li>
      <?php
      }
      ?>
      <?php 
        if(!is_pronostiqueur($id)) {
      ?>
      <li><a href="#">Information / Statistiques</a></li>
      <?php
      }
      ?>
      </ul>
    </div>
      <?php 
        if(!is_pronostiqueur($user_id)){ 
      ?>
      <div>
        <button class="button" style="width: 100px;">Suivre</button>
      </div>
      <?php
      } else  {
      ?>
      
      <?php
      }
      ?>
  </div>
  <div class="content_wrapper" style="position: relative; margin-left: 245px; top:7em;">
    <div id="content">
      <div class="list-pronostique" style="display: flex; flex-wrap: wrap; line-height: 1.5em;">
        <?php
          if (isset($_POST['valide_mise'])) {
              create_mise($_POST['mise'], $_POST['pronostique_id'], $user_id);
          }

          if(count($pronostiques) != 0): ?>
          <?php 
            for ($i = 0; $i < count($pronostiques); $i = $i + 1) {
               if($pronostiques[$i]['resultat'] == null && $pronostiques[$i]['prive'] == 0) {
        ?>
        <div class="pronostique">
        <?php
          if($pronostiques[$i]['resultat'] == null) {
            $etat_pronostique = "En cours";
            $css_pronostique = "inprogress";
          }
          else if($pronostiques[$i]['resultat'] == 1) {
            $etat_pronostique = "Victoire";
            $css_pronostique = "win";
          }
          else {
            $etat_pronostique = "Défaite";
            $css_pronostique = "losing";
          }
        ?>
        <div class="prognostic-category" style="box-shadow: 0px 0 7px -5px #333;">
          <div style="font-size: 13px; display: flex; justify-content: space-between; line-height:2em; font-weight: 700; padding-right: 5px; padding-left: 5px;" class="<?php echo $css_pronostique; ?>">
            <div style="padding-bottom: .25em; padding-top: .25em;"><span><?php echo $pronostiques[$i]['sport_name'] ?> - <?php echo $pronostiques[$i]['championnat_name'] ?></span></div>
            <div style="padding-bottom: .25em; padding-top: .25em;"><span>Pronostique N°<?php echo $pronostiques[$i]['id']; ?></span></div>
            <div style="padding-bottom: .25em; padding-top: .25em; display: flex;">
            <div style="display:flex; align-items: center; font-weight: 700; font-size: 13px;">
              <?php 
                if (!is_pronostiqueur($user_id)) {
                  $mise = find_mise($pronostiques[$i]['id']);
                  if ($mise == null) {
                    if(time() < $pronostiques[$i]['date_match']) {
              ?>
              <form method="POST">
                <input type="number" required="required" min="1" class="mise" name="mise" style="width: 40px;"><strong class="euro">€</strong>
                <input type="hidden" name="pronostique_id" value="<?php echo $pronostiques[$i]['id']; ?>" />
                <input type="submit" name="valide_mise" value="valide">
              </form>
              <?php
              } else {
                    echo "pas de mise";
                  }
                }
                else {
                    echo $mise . "€";
                  }
                }
              ?>
              </div>
              <span style="padding-left: 10px;"><?php echo $etat_pronostique; ?></span>
            </div>
          </div>
        </div>
        <div style="background: white;">
          <div class="header" style="font-size: 13px; overflow: hidden; line-height: 24px; padding: 12px 12px 0; border-top:1px solid #f1f1f1; ">
            <div class="title-versus" style="display: flex;">
              <div class="team-1"><?php echo $pronostiques[$i]['equipe_1_nom'] ?></div>
              <div class="versus" style="padding-left: 5px; padding-right: 5px;">-</div>
              <div class="team-2"><?php echo $pronostiques[$i]['equipe_2_nom'] ?></div>
            </div>
            <div style="text-align: center; font-size: 11px; color: #7a899d; line-height: 1; margin-top: 7px;"><?php echo date("l d F Y H:i", $pronostiques[$i]['date_match']) ?></div>
          </div>
          <div class="meta" style="line-height: 20px; padding: 12px; text-align: center;">
            <div class="result" style="font-size: 14px; color: #43484e; padding: 15px; background: #eff2f9; font-weight: 700; position: relative; z-index: 0; border-radius: 4px;">
              <span>Vainqueur : </span>
              <?php
                $match_nul = $pronostiques[$i]['match_nul_pronostique'];
                if ($match_nul == 1) {
                echo "Match nul";
              } else {
                echo $pronostiques[$i]['equipe_gagnante_nom'];
              }
              ?>
              <span class="cote"><?php echo $pronostiques[$i]['cote'] ?>€</span>
            </div>
          </div>
          <div class="metas-inner" style="margin-top: 11px; text-align: center;">
            <div class="list" style="font-size: 13px; font-weight: bold;">
              <div class="metas" style="background: #00cc00;"><?php echo $pronostiques[$i]['mise'] . "€"; ?></div>
              <div class="metas" style="background: grey"><?php echo $pronostiques[$i]['cote'] . "€"; ?></div>
              <?php
                if($pronostiques[$i]['resultat'] == 1){
              ?>
              <div class="metas" style="background: black"><?php echo $pronostiques[$i]['mise'] * $pronostiques[$i]['cote'] - $pronostiques[$i]['mise'] . "€"; ?></div>
              <div class="metas" style="background: blue"><?php echo $pronostiques[$i]['mise'] * $pronostiques[$i]['cote'] . "€"; ?></div>
              <?php
              } else if($pronostiques[$i]['resultat'] != null && $pronostiques[$i]['resultat'] == 0) {
              ?>
              <div class="metas" style="background: red;"><?php echo $pronostiques[$i]['mise'] . "€"; ?></div>
              <?php
              } 
              ?>
            </div>
          </div>
          <div class="content" style="padding: 15px 20px; line-height: 29px;"> 
            <div class="prognostic-analysis" style="font-size: 13px; height:77px;"><?php echo $pronostiques[$i]['analyse'] ?></div>
          </div>
        </div>
      </div>
      <?php
        }
      }
      ?>
      <?php else: ?>
      <div class="block message_" style="width: 100%;">
      <?php 
        if(is_pronostiqueur($id)) {
      ?>
        <p align="center">Cet utilisateur n'a posté aucun pronostique pour le moment. Veuillez repasser plus tard !</p>
      <?php
      } else {
      ?>
      <table>
      <?php
       $pronostiqueurs = find_pronostiqueurs();
       for ($i=0; $i < count($pronostiqueurs) ; $i++) {
      ?>
      <tr>
        <td><?php echo $pronostiqueurs[$i]['pseudo']; ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $pronostiqueurs[$i]['id']; ?>">
            <input type="submit" name="envoi" value="Voir détails" />
          </form>
        </td>
      </tr>
      <?php
      }
      ?>
      </table>
      <?php 
      if (isset($_POST["envoi"])) {
          echo $_POST["id"];
          $_SESSION['id'] = $_POST["id"];
      ?>
        <meta http-equiv=Refresh content="5; url=profile.php" />
      <?php    
      }
      ?>
        <div align="center">Vous ne suivez aucun pronostiqueur pour le moment !</div>
      <?php
      }
      ?>
      </div>
      <?php endif; ?>
      </div>
  </div>
</div>
</body>
</html>
