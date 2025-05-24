<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php'); 
  require_once('../templates/sidebar.php'); 
  require_once('../templates/config.php');

  session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
  }

  drawHeader();
?>
  <div class="profile-container"> 
    <aside class="side-nav">
      <?php drawNavbar(); ?>
    </aside>
    <section class="profile-section"> 
      <?php drawConfig(); ?>
    </section>
  </div>
  
<?php
  drawFooter();
?>