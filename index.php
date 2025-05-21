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
  $location = isset($_GET['location']) ? $_GET['location'] : '';
  $search = isset($_GET['search']) ? $_GET['search'] : '';

  $anuncios = getAnuncios($db, $page, $limit, $location, $search);
  $totalAds = getTotalAdCount($db, $location, $search);
  $totalPages = ceil($totalAds / $limit);

  // AJAX handler: only output ads, nothing else
  if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    drawAds($anuncios, $totalAds, $db);
    exit;
}

  drawHeader();
  drawSearch();

  if ($totalPages > 1) {
    echo '<div class="pagination">';

    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '" class="arrow">&laquo;</a>';
    } else {
        echo '<span class="arrow disabled">&laquo;</span>';
    }

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

    // Show the last page
    if ($page < $totalPages - $range) {
        if ($page < $totalPages - $range - 1) {
            echo '<span>...</span>';
        }
        echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
    }

    if ($page < $totalPages) {
        echo '<a href="?page=' . ($page + 1) . '" class="arrow">&raquo;</a>';
    } else {
        echo '<span class="arrow disabled">&raquo;</span>';
    }

    echo '</div>';
}
  drawFooter();
?>
