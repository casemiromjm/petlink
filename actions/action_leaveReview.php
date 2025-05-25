<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $db = getDatabaseConnection();
    $userId = $_SESSION['user_id'];

    $orderId = intval($_POST['order_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    // Buscar ad_id e freelancer_id do pedido
    $stmt = $db->prepare('SELECT ad_id FROM ServiceRequests WHERE request_id = ? AND client_id = ?');
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && $rating >= 1 && $rating <= 5) {
        $adId = $order['ad_id'];

        // Inserir review
        $stmt = $db->prepare('INSERT INTO Reviews (ad_id, client_id, rating, comment) VALUES (?, ?, ?, ?)');
        $stmt->execute([$adId, $userId, $rating, $comment]);

        // Marcar pedido como avaliado
        $stmt = $db->prepare('UPDATE ServiceRequests SET reviewed = 1 WHERE request_id = ?');
        $stmt->execute([$orderId]);

        header('Location: ../pages/messages.php?success=reviewed');
        exit;
    }
}

header('Location: ../pages/messages.php?error=review_failed');
exit;
