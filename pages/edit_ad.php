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
$user = $_SESSION['user_id'];
$adId = isset($_GET['id']) ? intval($_GET['id']) : null;

// ta morrendo aqui 1o
if (!$adId) {
    die('Anúncio não especificado.');
}

$stmt = $db->prepare('SELECT * FROM Ads WHERE ad_id = ? AND freelancer_id = ?');
$stmt->execute([$adId, $user]);
$ad = $stmt->fetch();

if (!$ad) {
    die('Anúncio não encontrado.');
}

drawHeader();
drawEditAd();
drawFooter();

?>
