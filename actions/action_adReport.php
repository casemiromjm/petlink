<?php
// actions/generate_report.php
declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/users.class.php');
require_once(__DIR__.'/../database/anuncios.class.php');
require_once(__DIR__.'/../database/animal.class.php');
require_once(__DIR__.'/../database/service.class.php');

$db = getDatabaseConnection();

$filename = 'relatorioDeAnuncios_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, [ 'ID', 'Freelancer_id', 'Título', 'Preço']);


$ads = Ad::getAllAdsReport($db);
foreach ($ads as $ad) {
    fputcsv($output, [ $ad['ad_id'], $ad['freelancer_id'], $ad['title'], 'Preco: ' . $ad['price'] . ' / ' . $ad['price_period']]);
}

fclose($output);
exit;
?>
