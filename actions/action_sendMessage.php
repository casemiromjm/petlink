<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/security.php');
require_once(__DIR__ . '/../init.php');


if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for sending message. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/messages.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);


if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ad'], $_POST['to'], $_POST['message'])) {
    $db = getDatabaseConnection();

    $adId = intval($_POST['ad']);
    $toUserId = intval($_POST['to']);
    $fromUserId = $_SESSION['user_id'];
    $text = trim($_POST['message']);

    if ($fromUserId === $toUserId) {
        header('Location: ../pages/messages.php');
        exit;
    }

    $stmt = $db->prepare('INSERT INTO Messages (ad_id, from_user_id, to_user_id, text, sent_at) VALUES (?, ?, ?, ?, datetime("now"))');
    $stmt->execute([$adId, $fromUserId, $toUserId, $text]);

    header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $toUserId);
    exit;
} else {
    // Invalid request, redirect to messages page
    header('Location: ../pages/messages.php');
    exit;
}

$db = getDatabaseConnection();

// Fetch chats for the current user
$stmt = $db->prepare('
    SELECT DISTINCT
        CASE
            WHEN from_user_id = :user_id THEN to_user_id
            ELSE from_user_id
        END AS user_id,
        u.username,
        u.name,
        u.profile_photo,
        a.ad_id,
        a.title AS ad_title,
        (SELECT text FROM Messages m2 WHERE (m2.from_user_id = :user_id OR m2.to_user_id = :user_id) AND m2.ad_id = a.ad_id AND (m2.from_user_id = u.user_id OR m2.to_user_id = u.user_id) ORDER BY m2.sent_at DESC LIMIT 1) AS last_message,
        (SELECT sent_at FROM Messages m2 WHERE (m2.from_user_id = :user_id OR m2.to_user_id = :user_id) AND m2.ad_id = a.ad_id AND (m2.from_user_id = u.user_id OR m2.to_user_id = u.user_id) ORDER BY m2.sent_at DESC LIMIT 1) AS last_time
    FROM Messages m
    JOIN Users u ON u.user_id = CASE WHEN m.from_user_id = :user_id THEN m.to_user_id ELSE m.from_user_id END
    JOIN Ads a ON a.ad_id = m.ad_id
    WHERE m.from_user_id = :user_id OR m.to_user_id = :user_id
    ORDER BY last_time DESC
');
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch messages for the selected chat
$messages = [];
if (isset($_GET['ad']) && isset($_GET['to'])) {
    $selectedAdId = intval($_GET['ad']);
    $selectedUserId = intval($_GET['to']);

    $stmt = $db->prepare('
        SELECT * FROM Messages
        WHERE ad_id = ? AND ((from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?))
        ORDER BY sent_at ASC
    ');
    $stmt->execute([$selectedAdId, $_SESSION['user_id'], $selectedUserId, $selectedUserId, $_SESSION['user_id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $selectedAdId = null;
    $selectedUserId = null;
}

drawHeader();
drawMensagens($chats, $messages, $selectedAdId, $selectedUserId);
drawFooter();
?>
