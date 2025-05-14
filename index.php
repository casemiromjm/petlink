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
  drawAds($anuncios, $totalAds, $db);

  if ($totalPages > 1) {
    echo '<div class="pagination">';
    $range = 2; 


    if ($page > $range + 1) {
        echo '<a href="?page=1">1</a>';
        if ($page > $range + 2) {
            echo '<span>...</span>'; 
        }
    }


    for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) {
        $activeClass = ($i === $page) ? 'active' : '';
        echo '<a href="?page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
    }


    if ($page < $totalPages - $range) {
        if ($page < $totalPages - $range - 1) {
            echo '<span>...</span>'; 
        }
        echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
    }
    echo '</div>';
}
  drawFooter();
?>
