<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php');
  require_once('../templates/profile.php'); 
  require_once('../templates/sidebar.php');

  drawHeader();
?>
<main class="profile-page">
  <aside class="side-nav">
    <?php drawNavbar(); ?>
  </aside>
  <section class="content">
    <?php drawEditProfile(); ?>
  </section>
</main>
<?php
  drawFooter();
?>