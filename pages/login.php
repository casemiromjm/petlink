<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../templates/layout.php');
  require_once(__DIR__ . '/../templates/login.php');
  require_once(__DIR__ . '/../security.php');
  require_once(__DIR__ . '/../init.php');

  $csrf_token = generate_csrf_token();

  drawHeader();

  if (isset($_GET['message']) && !empty($_GET['message'])) {
      echo '<p id="error-message">' . htmlspecialchars($_GET['message']) . '</p>';
  }

  drawLogin($csrf_token);
  drawFooter();
?>
