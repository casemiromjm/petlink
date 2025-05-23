<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php');
  require_once('../templates/login.php');

  drawHeader();

    if (isset($_GET['message']) && !empty($_GET['message'])) {
        echo '<p id="error-message">' . htmlspecialchars($_GET['message']) . '</p>';
    }

  drawLogin();
  drawFooter();
?>
