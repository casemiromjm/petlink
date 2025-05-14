<?php
  declare(strict_types = 1);

  require_once('../templates/layout.php'); 
  require_once('../templates/sidebar.php'); 
  require_once('../templates/addAnimal.php');

  drawHeader();
  drawNavbar();
  drawAddAnimal();
  drawFooter();
?>