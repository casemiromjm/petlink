<?php
  declare(strict_types = 1);

  require_once(__DIR__.'/../templates/layout.php');
  require_once(__DIR__.'/../templates/adDetails.php');
  require_once(__DIR__.'/../database/connection.db.php');
  require_once(__DIR__.'/../database/anuncios.class.php');
  require_once(__DIR__.'/../database/reviews.class.php');
  require_once(__DIR__.'/../database/users.class.php');
  require_once(__DIR__.'/../templates/reviews.php');

  drawHeader();
  drawAdDetails();
  drawFooter();
?>
