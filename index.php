<?php
  declare(strict_types = 1);

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once('templates/layout.php');
  require_once('templates/search.php');
  require_once('templates/anuncios.php');
  require_once('database/connection.php');
  require_once('database/db.anuncios.php');

  $db = getDatabaseConnection();
  $anuncios = getAnuncios($db, 16);

  drawHeader();
  drawSearch();
  drawAds($anuncios);
  drawFooter();
?>