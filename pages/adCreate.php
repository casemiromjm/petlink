<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../init.php');
  require_once(__DIR__ . '/../templates/layout.php');
  require_once(__DIR__ . '/../templates/adCreate.php');
  require_once(__DIR__ . '/../security.php');

  $csrf_token = generate_csrf_token();

  drawHeader();
  drawAdCreate($csrf_token);
  drawFooter();
?>
