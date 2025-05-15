<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php');
  require_once('../templates/profile.php'); 
  require_once('../templates/sidebar.php');

drawHeader();
?>
<body class="profile-page">
    <div class="profile-layout">
        <aside class="side-nav">
            <?php drawNavbar(); ?>
        </aside>

        <main class="profile-content">
            <?php drawEditProfile(); ?>
        </main>
    </div>
</body>
<?php
drawFooter();
?>