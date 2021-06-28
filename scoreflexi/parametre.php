<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/services/services_user.php');
require('queries/queries_user.php');
require('includes/function.php');
?>
<div>
  <?php 
  	if (isset($_POST['valide'])) {
  		update_profil_user($_POST['equipe'], $_POST['sport'], $_SESSION["user_id"]);
  	}
    if (isset($_POST['valide_mot_de_passe'])){
       $user = find_user($_SESSION['pseudo']);
       if (sha1($_POST['ancien_mot_de_passe']) != $user['password']) {
          echo "Veuillez entrer le bon ancien mot de passe";
       } else {
          if ($_POST['nouveau_mot_de_passe'] != $_POST['confirme_nouveau_mot_de_passe']) {
            echo "Les deux mots de passe ne correspondent pas !";
          } else { 
            update_password_user(sha1($_POST['nouveau_mot_de_passe']), $_SESSION['user_id']);

            echo "Votre mot de passe a bien été mis à jour";
          }
      }
    }

  	$users = find_user_by_id($_SESSION["user_id"]);
  ?>
  <form method="POST" action="parametre.php">
  	<label>équipe</label>
  	<?php 
      if (!isset($_POST['modifier'])) {
        echo($users['equipe']);
      }
      else {
      ?>
        <input type="input" name="equipe" value="<?php echo($users['equipe']); ?>" required="required" />
      <?php
      }
    ?>
  	<br />
  	<label>sport</label>
  	<?php 
  		if (!isset($_POST['modifier'])) {
  			echo($users['sport']);
  		}
  		else {
  		?>
  			<input type="input" name="sport" value="<?php echo($users['sport']); ?>" required="required" />
  		<?php
  		}
  	?>
  	<br />
    <?php 
      if (isset($_POST['modifier_mot_de_passe'])) {
    ?>
        <label>Ancien mot de passe</label>
        <input type="password" name="ancien_mot_de_passe" required="required" /><br />
        <label>Nouveau mot de passe</label>
        <input type="password" name="nouveau_mot_de_passe" required="required" /><br />
        <label>Confirmer nouveau mot de passe</label>
        <input type="password" name="confirme_nouveau_mot_de_passe" required="required" /><br />
    <?php
      }
    ?>
  	<?php 
      if (!isset($_POST['modifier']) && !isset($_POST['modifier_mot_de_passe'])) { 
    ?>
      <input type="submit" name="modifier" value="modifier" onclick="location.href='parametre.php'" />
  		<input type="submit" name="modifier_mot_de_passe" value="modifier le mot de passe" onclick="location.href='parametre.php'" />
    <?php
  	  } else if (isset($_POST['modifier'])) {
  	?>
  	<input type="submit" name="valide" value="Envoyer" />
    <input type="button" value="Annuler" onclick="location.href='parametre.php'" />
    <?php
      } else if (isset($_POST['modifier_mot_de_passe'])) { 
    ?>
    <input type="submit" name="valide_mot_de_passe" value="Envoyer" />
    <input type="button" value="Annuler" onclick="location.href='parametre.php'" />
    <?php
    }
    ?>
  </form>
</div>

