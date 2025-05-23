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

    // Mensagem especial para o chat
    $requestId = $db->lastInsertId();
    $animalList = isset($_POST['animals']) ? implode(', ', $_POST['animals']) : 'Nenhum';
    $specialMsg = 
        "<div class='order-message-header'><strong>Pedido de Serviço</strong></div>" .
        "<div class='order-message-body'>" .
        "Pedido #$requestId<br>" .
        "Animais: $animalList<br>" .
        "Quantidade: $amount $pricePeriod(s)<br>" .
        "Preço total: " . ($price * $amount) . "€<br>" .
        "<em>Aguarda aprovação do prestador.</em>" .
        "</div>";

    $stmt = $db->prepare('INSERT INTO Messages (ad_id, from_user_id, to_user_id, text, sent_at) VALUES (?, ?, ?, ?, datetime("now"))');
    $stmt->execute([$adId, $clientId, $freelancerId, $specialMsg]);

    header('Location: ../pages/messages.php?ad=' . $adId . '&to=' . $freelancerId . '&success=1');
    exit;
}
header('Location: ../pages/messages.php');
exit;