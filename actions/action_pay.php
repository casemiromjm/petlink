<?php
declare(strict_types=1);
session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['order_id'])) {
    $db = getDatabaseConnection();
    $orderId = intval($_POST['order_id']);
    $userId = $_SESSION['user_id'];

    // Verifica se o usuário é o cliente do pedido
    $stmt = $db->prepare('SELECT * FROM ServiceRequests WHERE request_id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && $order['client_id'] == $userId) {
        $stmt = $db->prepare('UPDATE ServiceRequests SET is_paid = 1 WHERE request_id = ?');
        $stmt->execute([$orderId]);

        echo json_encode(['success' => true]);
        exit;
    }
}
echo json_encode(['success' => false]);
exit;