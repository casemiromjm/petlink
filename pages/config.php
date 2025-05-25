<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../templates/layout.php');
  require_once(__DIR__ . '/../templates/sidebar.php');
  require_once(__DIR__ . '/../templates/config.php');
  require_once(__DIR__ . '/../security.php');
  require_once(__DIR__ . '/../init.php');

  $csrf_token = generate_csrf_token();

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
      <?php drawConfig($csrf_token); ?>
    </section>
  </div>

<?php
  drawFooter();
?>
