<?php
  declare(strict_types = 1);

  require_once(__DIR__ .'/../templates/layout.php');
  require_once(__DIR__ .'/../templates/sidebar.php');
  require_once(__DIR__ .'/../templates/addAnimal.php');
  require_once(__DIR__ . '/../security.php');
  require_once(__DIR__ . '/../init.php');

  $csrf_token = generate_csrf_token();

  drawHeader();
  drawAddAnimal($csrf_token);
  drawFooter();
?>
