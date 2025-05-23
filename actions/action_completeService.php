<?php
declare(strict_types=1);
session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['order_id'])) {
    $db = getDatabaseConnection();
    $orderId = intval($_POST['order_id']);
    $userId = $_SESSION['user_id'];

    // Verifica se o usuário é o freelancer responsável e o status está em progresso
    $stmt = $db->prepare('SELECT * FROM ServiceRequests WHERE request_id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && $order['freelancer_id'] == $userId && !in_array($order['status'], ['completed', 'rejected'])) {
        $stmt = $db->prepare('UPDATE ServiceRequests SET status = "completed" WHERE request_id = ?');
        $stmt->execute([$orderId]);
        header('Location: ../pages/messages.php?ad=' . $order['ad_id'] . '&to=' . $order['client_id'] . '&success=completed');
        exit;
    }
}

// Se falhar, volta ao chat sem completar
$adId = isset($_POST['ad']) ? intval($_POST['ad']) : '';
$toId = isset($_POST['to']) ? intval($_POST['to']) : '';
header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $toId . '&error=complete_failed');
exit;