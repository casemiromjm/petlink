<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ . '/../templates/edit_ad.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../security.php');
require_once(__DIR__ . '/../init.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$csrf_token = generate_csrf_token();
$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];
$adId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$adId) {
    die('Anúncio não especificado.');
}

$stmt = $db->prepare('SELECT * FROM Ads WHERE ad_id = ? AND freelancer_id = ?');
$stmt->execute([$adId, $userId]);
$ad = $stmt->fetch();

if (!$ad) {
    die('Anúncio não encontrado.');
}

$animalStmt = $db->prepare('SELECT animal_id FROM Ad_animals WHERE ad_id = ?');
$animalStmt->execute([$adId]);
$associatedAnimals = $animalStmt->fetchAll(PDO::FETCH_COLUMN);

$success = isset($_GET['success']) ? intval($_GET['success']) : 0;

drawHeader();
drawEditAd($ad, $associatedAnimals, $success, $csrf_token);
drawFooter();

?>
