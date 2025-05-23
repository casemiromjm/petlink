<?php
declare(strict_types=1);
session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $db = getDatabaseConnection();
    $adId = intval($_POST['ad_id']);
    $clientId = $_SESSION['user_id'];

    // Buscar freelancer_id, price, price_period do anúncio
    $stmt = $db->prepare('SELECT freelancer_id, price, price_period FROM Ads WHERE ad_id = ?');
    $stmt->execute([$adId]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ad) die('Anúncio não encontrado.');

    $freelancerId = $ad['freelancer_id'];
    $price = $ad['price'];
    $pricePeriod = $ad['price_period'];

    $animals = isset($_POST['animals']) ? json_encode($_POST['animals']) : '[]';
    $amount = intval($_POST['amount']);

    // Cria o pedido de serviço
    $stmt = $db->prepare('INSERT INTO ServiceRequests (ad_id, client_id, freelancer_id, animals, amount, price, price_period) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$adId, $clientId, $freelancerId, $animals, $amount, $price, $pricePeriod]);

    header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $freelancerId . '&success=1');
    exit;
}
header('Location: ../pages/messages.php');
exit;