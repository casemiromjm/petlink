<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../security.php');
require_once(__DIR__ . '/../init.php');

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for canceling request. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/messages.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['order_id'])) {
    $db = getDatabaseConnection();
    $orderId = intval($_POST['order_id']);
    $userId = $_SESSION['user_id'];

    // Buscar o pedido para garantir que pertence ao utilizador e está pendente ou rejeitado
    $stmt = $db->prepare('SELECT * FROM ServiceRequests WHERE request_id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        $order &&
        $order['client_id'] == $userId &&
        in_array($order['status'], ['pending', 'rejected'])
    ) {
        // Guardar ad_id e freelancer_id para redirecionar de volta ao chat
        $adId = $order['ad_id'];
        $freelancerId = $order['freelancer_id'];

        // Apagar o pedido
        $stmt = $db->prepare('DELETE FROM ServiceRequests WHERE request_id = ?');
        $stmt->execute([$orderId]);

        header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $freelancerId . '&success=cancelled');
        exit;
    }
}

// Se falhar, volta ao chat sem apagar
$adId = isset($_POST['ad']) ? intval($_POST['ad']) : '';
$toId = isset($_POST['to']) ? intval($_POST['to']) : '';
header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $toId . '&error=cancel_failed');
exit;
