<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php');
  require_once('../templates/profile.php'); 
  require_once('../templates/sidebar.php');

  drawHeader();
?>
<body>
    <div class="profile-container"> 
        <aside class="side-nav">
            <?php drawNavbar(); ?>
        </aside>

        <section class="profile-section"> 
            <?php drawEditProfile(); ?>
        </section>
    </div>
</body>
<?php
drawFooter();
?>