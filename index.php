<?php
  declare(strict_types = 1);

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once(__DIR__.'/templates/layout.php');
  require_once(__DIR__.'/templates/search.php');
  require_once(__DIR__.'/templates/anuncios.php');
  require_once(__DIR__.'/templates/reviews.php');
  require_once(__DIR__.'/database/connection.db.php');
  require_once(__DIR__.'/database/anuncios.class.php');

  $db = getDatabaseConnection();

  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = 16;
  $location = isset($_GET['location']) ? $_GET['location'] : '';
  $search = isset($_GET['search']) ? $_GET['search'] : '';

  $duracao = ($_GET['duracao'] ?? '') !== '' && $_GET['duracao'] !== 'Qualquer' ? $_GET['duracao'] : '';
$animal = ($_GET['animal'] ?? '') !== '' && $_GET['animal'] !== 'Todos' ? $_GET['animal'] : '';
$servico = ($_GET['servico'] ?? '') !== '' && $_GET['servico'] !== 'Todos' ? $_GET['servico'] : '';

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$sort = $_GET['sort'] ?? 'recentes';

$filters = [
    'search' => $search,
    'location' => $location,
    'duracao' => $duracao,
    'animal' => $animal,
    'servico' => $servico,
    'user_id' => $userId,
    'sort' => $sort
];

$ads = Ad::search($db, $filters, $page, $limit);
$totalAds = Ad::countSearch($db, $filters); // Use the new method!
$totalPages = ceil($totalAds / $limit);

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    drawAds($ads, $totalAds, $db);
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
