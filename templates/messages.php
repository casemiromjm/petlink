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
              <img src="<?= htmlspecialchars(str_replace('./', '../', $chat['profile_photo'] ?? '../resources/default_profile.png')) ?>" alt="Foto de perfil" style="width:40px; height:40px; border-radius:50%; object-fit:cover; background:#e0e0e0;">
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
    <?php if ($selectedAdId && $selectedUserId): ?>
      <div class="messages-list" id="messagesContainer">
        <?php
          $lastDate = null;
          $lastSentIndex = null;
          $lastMessageIndex = count($messages) - 1;
          foreach ($messages as $i => $msg) {
            if ($msg['from_user_id'] == $_SESSION['user_id']) {
              $lastSentIndex = $i;
            }
          }
        ?>
        <?php foreach ($messages as $i => $msg):
          $msgDate = (new DateTime($msg['sent_at']))->format('d/m/Y');
          $msgHour = (new DateTime($msg['sent_at']))->format('H:i');
          if ($msgDate !== $lastDate):
        ?>
          <div class="message-date-header"><?= htmlspecialchars($msgDate) ?></div>
        <?php
            $lastDate = $msgDate;
          endif;
        ?>
          <div class="message <?= $msg['from_user_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
            <span><?= htmlspecialchars($msg['text']) ?></span>
            <div class="message-time"><?= htmlspecialchars($msgHour) ?></div>
          </div>
          <?php if ($i === $lastSentIndex && $i === $lastMessageIndex): ?>
            <div class="message-status <?= $msg['is_read'] ? 'seen' : 'sent' ?> message-status-right">
              <?= $msg['is_read'] ? 'seen' : 'sent' ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
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