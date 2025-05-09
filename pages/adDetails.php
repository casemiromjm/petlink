<?php
  declare(strict_types = 1);

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

  require_once('../templates/layout.php');
  require_once('../templates/adDetails.php');
  require_once('../database/connection.php');
  require_once('../database/db.anuncios.php');

  $db = getDatabaseConnection();
  $adId = isset($_GET['id']) ? intval($_GET['id']) : null;

  if ($adId === null) {
    die('Anúncio não encontrado.');
  }

  $ad = getAdById($db, $adId);

  if (!$ad) {
    die('Anúncio não encontrado.');
  }

  // Desenhar o layout
  drawHeader();
  drawAdDetails($ad);
  drawFooter();
?>