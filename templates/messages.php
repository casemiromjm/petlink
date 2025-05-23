<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawMensagens(array $chats, array $messages, ?int $selectedAdId, ?int $selectedUserId): void { ?>
<div class="chat-container">
  <aside class="chat-list">
    <h2>Conversas</h2>
    <ul>
      <?php foreach ($chats as $chat): ?>
        <li class="<?= $selectedAdId === $chat['ad_id'] && $selectedUserId === $chat['user_id'] ? 'active' : '' ?>">
          <a class="profile-link<?= ($selectedAdId == $chat['ad_id'] && $selectedUserId == $chat['user_id']) ? ' active' : '' ?>"
             href="../pages/messages.php?ad=<?= htmlspecialchars((string)$chat['ad_id']) ?>&to=<?= htmlspecialchars((string)$chat['user_id']) ?>">
            <div style="display: flex; align-items: center; gap: 12px;">
              <img src="<?php
  $profilePhotoId = $chat['photo_id'] ?? 'default'; // <-- change 'profile_photo' to 'photo_id'
  if (
    !$profilePhotoId ||
    $profilePhotoId === 'default' ||
    $profilePhotoId === '../resources/default_profile.png'
  ) {
    $src = '/resources/profilePics/0.png';
  } elseif (is_numeric($profilePhotoId)) {
    $src = "/resources/profilePics/" . $profilePhotoId . ".png";
  } else {
    $src = "/resources/profilePics/" . basename((string)$profilePhotoId);
  }
  echo htmlspecialchars($src);
?>" alt="Foto de perfil" style="width:40px; height:40px; border-radius:50%; object-fit:cover; background:#e0e0e0;">
              <div style="flex:1;">
                <strong><?= htmlspecialchars($chat['ad_title']) ?></strong>
                <div style="font-size:0.97em; color:#333;">
                  <?= htmlspecialchars($chat['name']) ?> <span style="color:#81B29A;"></span>
                </div>
                <div class="chat-last-message"><?= htmlspecialchars($chat['last_message']) ?></div>
                <div class="chat-last-time">
                  <?php
                    if (!empty($chat['last_time'])) {
                      $dt = new DateTime($chat['last_time']);
                      echo htmlspecialchars($dt->format('H:i d/m/Y'));
                    }
                  ?>
                </div>
              </div>
            </div>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>
  <section class="chat-messages">
    <?php if ($selectedAdId && $selectedUserId):
      $currentChat = null;
      foreach ($chats as $chat) {
        if ($chat['ad_id'] == $selectedAdId && $chat['user_id'] == $selectedUserId) {
          $currentChat = $chat;
          break;
        }
      }
    ?>
      <?php if ($currentChat): ?>
        <div class="chat-header-bar" style="display:flex;align-items:center;gap:16px;padding:1.2rem 2rem 1rem 2rem;border-bottom:1px solid #e3e7e5;background:#fff;">
          <a href="../pages/userprofile.php?username=<?= urlencode($currentChat['username']) ?>" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;">
            <img src="<?php
  $profilePhotoId = $currentChat['photo_id'] ?? 'default'; // <-- use 'photo_id' here
  if (
    !$profilePhotoId ||
    $profilePhotoId === 'default' ||
    $profilePhotoId === '../resources/default_profile.png'
  ) {
    $src = '/resources/profilePics/0.png';
  } elseif (is_numeric($profilePhotoId)) {
    $src = "/resources/profilePics/" . $profilePhotoId . ".png";
  } else {
    $src = "/resources/profilePics/" . basename((string)$profilePhotoId);
  }
  echo htmlspecialchars($src);
?>" alt="Foto de perfil" style="width:48px;height:48px;border-radius:50%;object-fit:cover;background:#e0e0e0;">
            <div>
              <div style="font-weight:600;font-size:1.1em;"><?= htmlspecialchars($currentChat['ad_title']) ?></div>
              <div style="font-size:0.97em;color:#2b4d43;"><?= htmlspecialchars($currentChat['name']) ?></div>
            </div>
          </a>
          <?php if ($_SESSION['user_id'] !== $currentChat['user_id']): // Only show for buyer ?>
            <button id="buyServiceBtn" style="margin-left:auto;background:#81B29A;color:#fff;border:none;padding:0.5em 1em;border-radius:6px;cursor:pointer;">Comprar Serviço</button>
          <?php endif; ?>
        </div>
        <?php include(__DIR__ . '/../modals/buyService_modal.php'); ?>
      <?php endif; ?>
      <div class="messages-list" id="messagesContainer">
        <?php
          // Buscar pedidos de serviço relacionados a este chat
          $stmt = $db->prepare('SELECT *, created_at as sent_at, "order" as type FROM ServiceRequests WHERE ad_id = ? AND (client_id = ? OR freelancer_id = ?) ORDER BY created_at ASC');
          $stmt->execute([$selectedAdId, $_SESSION['user_id'], $selectedUserId]);
          $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Marcar mensagens como type = 'message'
          foreach ($messages as &$msg) $msg['type'] = 'message';
          unset($msg);

          // Mesclar e ordenar por data/hora
          $allItems = array_merge($messages, $orders);
          usort($allItems, function($a, $b) {
            return strtotime($a['sent_at']) <=> strtotime($b['sent_at']);
          });

          $lastDate = null;
          foreach ($allItems as $item):
            $msgDate = (new DateTime($item['sent_at']))->format('d/m/Y');
            $msgHour = (new DateTime($item['sent_at']))->format('H:i');
            if ($msgDate !== $lastDate):
        ?>
          <div class="message-date-header"><?= htmlspecialchars($msgDate) ?></div>
        <?php
              $lastDate = $msgDate;
            endif;

            if ($item['type'] === 'message'):
        ?>
          <div class="message <?= $item['from_user_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
            <span><?= htmlspecialchars($item['text']) ?></span>
            <div class="message-time"><?= htmlspecialchars($msgHour) ?></div>
          </div>
        <?php
            elseif ($item['type'] === 'order'):
              $isClient = ($_SESSION['user_id'] == $item['client_id']);
        ?>
          <div class="order-message" style="background:#e3e7e5;padding:1em;border-radius:8px;margin:1em 0;">
            <div class="order-message-header" style="font-weight:bold;color:#568870;">Pedido de Serviço #<?= htmlspecialchars($item['request_id']) ?></div>
            <div class="order-message-body" style="margin:0.5em 0;">
              Animais: <?= htmlspecialchars($item['animals']) ?><br>
              Quantidade: <?= htmlspecialchars($item['amount']) ?> <?= htmlspecialchars($item['price_period']) ?>(s)<br>
              Preço total: <?= htmlspecialchars($item['price'] * $item['amount']) ?>€
            </div>
            <div class="message-time"><?= htmlspecialchars($msgHour) ?></div>
            <?php if ($isClient && $item['status'] === 'pending'): ?>
              <form method="post" action="../actions/action_cancelOrder.php" style="margin-top:0.5em;">
                <input type="hidden" name="request_id" value="<?= htmlspecialchars($item['request_id']) ?>">
                <button type="submit" class="cancel-order-btn" style="background:#E63946;color:#fff;border:none;padding:0.3em 1em;border-radius:6px;cursor:pointer;">Cancelar</button>
              </form>
            <?php endif; ?>
          </div>
        <?php
            endif;
          endforeach;
        ?>
      </div>
      <form class="send-message-form" action="../actions/action_sendMessage.php" method="post">
        <input type="hidden" name="ad" value="<?= $selectedAdId ?>">
        <input type="hidden" name="to" value="<?= $selectedUserId ?>">
        <input type="text" name="message" placeholder="Escreva uma mensagem..." required autocomplete="off">
        <button type="submit">Enviar</button>
      </form>
    <?php else: ?>
      <div class="no-chat-selected">Selecione uma conversa para começar</div>
    <?php endif; ?>
  </section>
</div>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messagesContainer');
    if (container) {
      container.scrollTop = container.scrollHeight;
    }
  });
</script>
<?php } ?>