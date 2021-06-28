<?php
require($_SERVER['DOCUMENT_ROOT'] . '/services/services_user.php');
?>
<nav class="nav-header" style="display: flex; width: 100%;"> 
  <div class="menu-header"> 
    <ul>
      <li><h1 class="name_site"><?= WEBSITE_NAME ;?></h1></li>
      <li><a href="#" class="menu-item" id="premium">Premium</a></li>
      <li><a href="#" class="menu-item" id="classement">Classement</a></li>
      <li><a href="#" class="menu-item" id="concours">Concours</a></li>
      <li><a href="#" class="menu-item" id="evenement">Evènement</a></li>
      <?php 
        if(!is_pronostiqueur(get_session('user_id'))) {
      ?>
      <li><a href="#" id="search"><i class="fa fa-search"></i></a></li>
      <?php
      }
      ?>
      <li><a href="#"><i class="fa fa-bell"></i></a></li>
    </ul>
    <div class="search-form">
      <input type="text" placeholder="Recherche" required="20">
    </div>
    <a class="close"><i class="fa fa-times"></i></a>
  </div>
<div class="menu">
  <section class="profile_id">
    <nav>
      <?php if(is_logged_in()): ?> 
      <a href="../profile.php">Mon profil</a>
      <?php 
        if(is_pronostiqueur(get_session('user_id'))) {
      ?>
      <a href="../matchs_futurs.php">Matchs à venir</a>
      <?php
      } else {
      ?>
      <a href="#">Mes pronostiqueurs</a>
      <?php
      }
      ?>
      <?php 
        if(is_pronostiqueur(get_session('user_id'))){ 
      ?>
      <a href="../matchs_termines.php">Matchs terminés</a>
      <?php
      } else {
      ?>
      <a href="#">Mes paris</a>
      <?php
      }
      ?>
      <?php 
        if(is_admin(get_session('user_id'))) {
      ?>
      <a href="../admin/index.php">Administration</a>
      <?php
      }
      ?>
      <?php endif;?>
    </nav>
  </section>
  <section class="nav">
    <ul>
      <li><a href="#" class="menu-item">Premium</a></li>
      <li><a href="#" class="menu-item">Classement</a></li>
      <li><a href="#" class="menu-item">Concours</a></li>
      <li><a href="#" class="menu-item">Evènement</a></li>
    </ul>
  </section>
  <section class="logout">
    <a href="../logout.php">Déconnexion</a>
  </section>
</div>
</nav>

