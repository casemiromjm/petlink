<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/messages.php');
require_once('../utils/session.php');
require_once('../database/connection.db.php');

Session::start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();

$currentUserId = $_SESSION['user_id'];
$selectedAdId = isset($_GET['ad']) ? intval($_GET['ad']) : null;
$selectedUserId = isset($_GET['to']) ? intval($_GET['to']) : null;

if ($selectedUserId && $selectedUserId === $currentUserId) {
    $selectedAdId = null;
    $selectedUserId = null;
}

$stmt = $db->prepare('
    SELECT DISTINCT
        CASE
            WHEN from_user_id = :user_id THEN to_user_id
            ELSE from_user_id
        END AS user_id,
        u.username,
        u.name,
        u.photo_id,
        a.ad_id,
        a.title AS ad_title,
        a.freelancer_id,
        (SELECT text FROM Messages m2 WHERE (m2.from_user_id = :user_id OR m2.to_user_id = :user_id) AND m2.ad_id = a.ad_id AND (m2.from_user_id = u.user_id OR m2.to_user_id = u.user_id) ORDER BY m2.sent_at DESC LIMIT 1) AS last_message,
        (SELECT sent_at FROM Messages m2 WHERE (m2.from_user_id = :user_id OR m2.to_user_id = :user_id) AND m2.ad_id = a.ad_id AND (m2.from_user_id = u.user_id OR m2.to_user_id = u.user_id) ORDER BY m2.sent_at DESC LIMIT 1) AS last_time
    FROM Messages m
    JOIN Users u ON u.user_id = CASE WHEN m.from_user_id = :user_id THEN m.to_user_id ELSE m.from_user_id END
    JOIN Ads a ON a.ad_id = m.ad_id
    WHERE m.from_user_id = :user_id OR m.to_user_id = :user_id
    ORDER BY last_time DESC
');
$stmt->execute([':user_id' => $currentUserId]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$messages = [];
if ($selectedAdId && $selectedUserId) {

    $stmt = $db->prepare('UPDATE Messages SET is_read = 1 WHERE ad_id = ? AND to_user_id = ? AND from_user_id = ? AND is_read = 0');
    $stmt->execute([$selectedAdId, $currentUserId, $selectedUserId]);
    $stmt = $db->prepare('
        SELECT * FROM Messages
        WHERE ad_id = ? AND ((from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?))
        ORDER BY sent_at ASC
    ');
    $stmt->execute([$selectedAdId, $currentUserId, $selectedUserId, $selectedUserId, $currentUserId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('
        SELECT * FROM ServiceRequests
        WHERE ad_id = ? AND (client_id = ? OR freelancer_id = ?)
        ORDER BY rowid DESC LIMIT 1
    ');
    $stmt->execute([$selectedAdId, $currentUserId, $currentUserId]);
    $latestOrder = $stmt->fetch(PDO::FETCH_ASSOC);
}

drawHeader();
drawMensagens($chats, $messages, $selectedAdId, $selectedUserId, $latestOrder ?? null);
drawFooter();
?>