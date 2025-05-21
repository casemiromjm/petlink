<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/edit_ad.php');
require_once('../database/connection.db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];
$adId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$adId) {
    die('Anúncio não especificado.');
}

// as queries estão mal feitas. so tomei como base o q tinha em outro ficheiro

$stmt = $db->prepare('SELECT rowid, * FROM Ads WHERE user_id = ? AND rowid = ?');
$stmt->execute([$userId, $animalId]);
$ad = $stmt->fetch();

if (!$ad) {
    die('Anúncio não encontrado.');
}

$stmt = $db->query('SELECT animal_id, animal_name FROM Animal_types');
$speciesList = $stmt->fetchAll();

drawHeader();
drawEditAd($animal, $speciesList);
drawFooter();

?>
