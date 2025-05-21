<?php
  declare(strict_types = 1);

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

  require_once('../templates/layout.php');
  require_once('../templates/adDetails.php');
  require_once('../database/connection.db.php');
  require_once('../database/anuncios.class.php');

  $db = getDatabaseConnection();
  $adId = isset($_GET['id']) ? intval($_GET['id']) : null;
  $success = isset($_GET['success']) ? intval($_GET['success']) : 0;

  if ($adId === null) {
    die('Anúncio não encontrado.');
  }

  $ad = getAdById($db, $adId);

  if (!$ad) {
    die('Anúncio não encontrado.');
  }

  // Desenhar o layout
  drawHeader();
  drawAdDetails($ad, $success);
  drawFooter();
?>
