<?php
  declare(strict_types = 1);

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once('templates/layout.php');
  require_once('templates/search.php');
  require_once('templates/anuncios.php');
  require_once('database/connection.db.php');
  require_once('database/db.anuncios.php');

  $db = getDatabaseConnection();

  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = 16;
  $anuncios = getAnuncios($db, $page, $limit);
  $totalAds = getTotalAdCount($db);
  $totalPages = ceil($totalAds / $limit);

  drawHeader();
  drawSearch();
  drawAds($anuncios, $totalAds);

  if ($totalPages > 1) {
    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = ($i === $page) ? 'active' : '';
        echo '<a href="?page=' . $i . '" class="' . $activeClass . '">' . $i . '</a> ';
    }
    echo '</div>';
}
  drawFooter();
?>
