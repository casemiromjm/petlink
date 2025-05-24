<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../templates/layout.php');
  require_once(__DIR__ . '/../templates/login.php');

  drawHeader();

  if (isset($_GET['message']) && !empty($_GET['message'])) {
      echo '<p id="error-message">' . htmlspecialchars($_GET['message']) . '</p>';
  }

  drawLogin();
  drawFooter();
?>
