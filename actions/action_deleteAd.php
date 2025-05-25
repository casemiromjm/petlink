<?php
declare(strict_types=1);

require_once('../database/connection.db.php');
require_once('../utils/session.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    die('Invalid request method');
}

$session = new Session();
$session->start();
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    die('You must be logged in to delete ads');
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('HTTP/1.1 403 Forbidden');
    die('Invalid CSRF token');
}

$adId = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : null;
if (!$adId) {
    header('HTTP/1.1 400 Bad Request');
    die('Invalid ad ID');
}

$db = getDatabaseConnection();

try {
    $db->beginTransaction();

    $stmt = $db->prepare('SELECT is_admin FROM users WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $isAdmin = (bool)$stmt->fetchColumn();

    $stmt = $db->prepare('SELECT freelancer_id FROM Ads WHERE ad_id = ?');
    $stmt->execute([$adId]);
    $adOwner = $stmt->fetchColumn();
    
    if ($adOwner !== $_SESSION['user_id'] && !$isAdmin) {
        header('HTTP/1.1 403 Forbidden');
        die('You can only delete your own ads');
    }

    $db->prepare('DELETE FROM Ad_animals WHERE ad_id = ?')->execute([$adId]);
    
    $stmt = $db->prepare('DELETE FROM Ads WHERE ad_id = ?');
    $stmt->execute([$adId]);
    
    $db->commit();
    
    $_SESSION['success_message'] = 'Anúncio eliminado com sucesso';
    
    header('Location: ../pages/userprofile.php?username=' . urlencode($_SESSION['username']));
    exit();
    
} catch (PDOException $e) {
    $db->rollBack();
    error_log('Delete ad error: ' . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    die('Error deleting ad');
}

?>
