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

  $anuncios = getAnuncios($db, $page, $limit, $location);
  $totalAds = getTotalAdCount($db, $location);
  $totalPages = ceil($totalAds / $limit);

  drawHeader();
  drawSearch();
  drawAds($anuncios, $totalAds, $db);

  if ($totalPages > 1) {
    echo '<div class="pagination">';

    // Left arrow (disabled if on the first page)
    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '" class="arrow">&laquo;</a>';
    } else {
        echo '<span class="arrow disabled">&laquo;</span>';
    }

    $range = 2; // Number of pages to show before and after the current page

    if ($page > $range + 1) {
        echo '<a href="?page=1">1</a>';
        if ($page > $range + 2) {
            echo '<span>...</span>'; // Ellipsis
        }
    }

    // Show pages around the current page
    for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) {
        $activeClass = ($i === $page) ? 'active' : '';
        echo '<a href="?page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
    }

    // Show the last page
    if ($page < $totalPages - $range) {
        if ($page < $totalPages - $range - 1) {
            echo '<span>...</span>'; // Ellipsis
        }
        echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
    }

    // Right arrow (disabled if on the last page)
    if ($page < $totalPages) {
        echo '<a href="?page=' . ($page + 1) . '" class="arrow">&raquo;</a>';
    } else {
        echo '<span class="arrow disabled">&raquo;</span>';
    }

    echo '</div>';
}
  drawFooter();
?>
