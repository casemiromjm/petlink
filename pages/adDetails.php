<?php
  declare(strict_types = 1);

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once(__DIR__.'/../templates/layout.php');
  require_once(__DIR__.'/../templates/adDetails.php');
  require_once(__DIR__.'/../templates/reviews.php');
  require_once(__DIR__.'/../database/connection.db.php');
  require_once(__DIR__.'/../database/anuncios.class.php');
  require_once(__DIR__.'/../database/reviews.class.php');
  require_once(__DIR__.'/../database/users.class.php');

  $db = getDatabaseConnection();
  $adId = isset($_GET['id']) ? intval($_GET['id']) : null;
  $success = isset($_GET['success']) ? intval($_GET['success']) : 0;

  if ($adId === null) {
    die('Anúncio não encontrado.');
  }

  $ad = Ad::getById($db, $adId);

  if (!$ad) {
    die('Anúncio não encontrado.');
  }

  $reviews = Reviews::getByAdId($db, (int)$adId);
  $averageRating = Reviews::getAverageRatingForAd($db, (int)$adId);
  $reviewCount = Reviews::getReviewCountForAd($db, (int)$adId);

  // Desenhar o layout
  drawHeader();
  drawAdDetails($ad, $reviews,$averageRating, $reviewCount, $success);
  drawFooter();
?>
