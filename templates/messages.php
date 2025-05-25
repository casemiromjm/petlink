<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawMensagens(array $chats, array $messages, ?int $selectedAdId, ?int $selectedUserId, $latestOrder = null, $csrf_token): void { ?>
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
  $profilePhotoId = $chat['photo_id'] ?? 'default';
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
  $profilePhotoId = $currentChat['photo_id'] ?? 'default';
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
          <?php if (
  isset($_SESSION['user_id'], $currentChat['freelancer_id']) &&
  $_SESSION['user_id'] !== $currentChat['freelancer_id']
): ?>
            <button id="buyServiceBtn" style="margin-left:auto;background:#81B29A;color:#fff;border:none;padding:0.5em 1em;border-radius:6px;cursor:pointer;">Contratar Serviço</button>
          <?php endif; ?>
        </div>
        <?php
        include_once('../modals/buyService_modal.php');
        ?>
      <?php endif; ?>
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
      <?php if ($latestOrder): ?>
        <div class="order-request-box order-box" style="position:relative;">
          <div class="order-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>Pedido de Serviço</span>
            <?php
              $status = $latestOrder['status'] ?? 'pending';
              $isPaid = !empty($latestOrder['is_paid']);
              // New: If paid and not completed, show "Serviço em progresso"
              if ($isPaid && !in_array($status, ['completed', 'rejected'])) {
                $status = 'in_progress';
                $statusText = 'Serviço em progresso';
              } else {
                $statusText = [
                  'pending' => 'A aguardar confirmação',
                  'accepted' => 'Aceite',
                  'accepted_awaiting_payment' => 'Aceite... a aguardar pagamento',
                  'rejected' => 'Rejeitado',
                  'completed' => 'Concluído'
                ][$status] ?? ucfirst($status);
              }
              $statusClass = 'order-status';
              if ($status === 'pending') $statusClass .= ' order-status-pending';
              if ($status === 'accepted_awaiting_payment' || $status === 'accepted') $statusClass .= ' order-status-accepted';
              if ($status === 'rejected') $statusClass .= ' order-status-rejected';
              if ($status === 'in_progress') $statusClass .= ' order-status-in-progress';
            ?>
            <span class="<?= $statusClass ?>">
              <?= htmlspecialchars($statusText) ?>
            </span>
          </div>
          <div><strong>Animais:</strong>
            <?php
              $animals = json_decode($latestOrder['animals'] ?? '[]', true);
              if ($animals && count($animals)) {
                require_once(__DIR__ .'/../database/connection.db.php');
                $db = getDatabaseConnection();
                function translateAnimalType(string $animalType): string {
                  $translations = [
                    'Cães' => 'Cão',
                    'Gatos' => 'Gato',
                    'Roedores' => 'Roedor',
                    'Pássaros' => 'Pássaro',
                    'Répteis' => 'Réptil',
                    'Peixes' => 'Peixe',
                    'Furões' => 'Furão',
                    'Coelhos' => 'Coelho'
                  ];
                  return $translations[$animalType] ?? $animalType;
                }
                $placeholders = implode(',', array_fill(0, count($animals), '?'));
                $stmt = $db->prepare("SELECT name, (SELECT animal_name FROM Animal_types WHERE animal_id = ua.species) AS species FROM user_animals ua WHERE ua.animal_id IN ($placeholders)");
                $stmt->execute($animals);
                $animalList = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $singular = translateAnimalType($row['species']);
                  $animalList[] = htmlspecialchars($row['name']) . ' <span class="animal-species-label">(' . htmlspecialchars($singular) . ')</span>';
                }
                echo implode(', ', $animalList);
              } else {
                echo 'N/A';
              }
            ?>
          </div>
          <div><strong>Duração:</strong>
            <?php
              $amount = (int)($latestOrder['amount'] ?? 1);
              $period = $latestOrder['price_period'] ?? '';
              $periodMap = [
                'hora' => 'horas',
                'dia' => 'dias',
                'semana' => 'semanas',
                'mês' => 'meses'
              ];
              $periodLabel = $amount === 1 ? $period : ($periodMap[$period] ?? $period);
              echo $amount . ' ' . $periodLabel;
            ?>
          </div>
          <div>
            <strong>Preço total:</strong>
            <?php
              $total = (float)($latestOrder['price'] ?? 0) * $amount;
              $unit = (float)($latestOrder['price'] ?? 0);
              echo number_format($total, 2, ',', '.') . '€';
              echo ' <span class="price-unit-label">(' . number_format($unit, 2, ',', '.') . '€/ ' . htmlspecialchars($period) . ')</span>';
            ?>
          </div>
          <div><strong>Data do pedido:</strong>
            <?php
              if (!empty($latestOrder['created_at'])) {
                $dt = new DateTime($latestOrder['created_at']);
                echo htmlspecialchars($dt->format('d/m/Y H:i'));
              }
            ?>
          </div>
          <?php if ($status === 'pending' && $_SESSION['user_id'] == $latestOrder['freelancer_id']): ?>
    <form class="cancel-order-form" action="" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
      <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? $latestOrder['id'] ?? $latestOrder['rowid'] ?? '')) ?>">
      <button type="submit" name="accept_order" class="accept-order-btn" formaction="../actions/action_acceptRequest.php">Aceitar</button>
      <button type="submit" name="reject_order" class="reject-order-btn" formaction="../actions/action_rejectRequest.php">Rejeitar</button>
    </form>
  <?php elseif ($status === 'accepted_awaiting_payment' && $_SESSION['user_id'] == $latestOrder['client_id']): ?>
    <form action="#" method="post" class="cancel-order-form" id="payOrderForm">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
      <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? $latestOrder['id'] ?? $latestOrder['rowid'] ?? '')) ?>">
      <button type="submit" class="accept-order-btn" id="openPaymentModalBtn">Efetuar pagamento</button>
    </form>
    <?php
      $showPaymentModal = false;
      // Detalhes dos animais igual à visualização do pedido
      $animalList = [];
      $animals = json_decode($latestOrder['animals'] ?? '[]', true);
      if ($animals && count($animals)) {
        require_once(__DIR__ . '/../database/connection.db.php');
        $db = getDatabaseConnection();
        // Função só se ainda não existir
        if (!function_exists('translateAnimalType')) {
          function translateAnimalType(string $animalType): string {
            $translations = [
              'Cães' => 'Cão',
              'Gatos' => 'Gato',
              'Roedores' => 'Roedor',
              'Pássaros' => 'Pássaro',
              'Répteis' => 'Réptil',
              'Peixes' => 'Peixe',
              'Furões' => 'Furão',
              'Coelhos' => 'Coelho'
            ];
            return $translations[$animalType] ?? $animalType;
          }
        }
        $placeholders = implode(',', array_fill(0, count($animals), '?'));
        $stmt = $db->prepare("SELECT name, (SELECT animal_name FROM Animal_types WHERE animal_id = ua.species) AS species FROM user_animals ua WHERE ua.animal_id IN ($placeholders)");
        $stmt->execute($animals);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $singular = translateAnimalType($row['species']);
          $animalList[] = htmlspecialchars($row['name']) . ' <span class="animal-species-label">(' . htmlspecialchars($singular) . ')</span>';
        }
      }
      $amount = (int)($latestOrder['amount'] ?? 1);
      $period = $latestOrder['price_period'] ?? '';
      $periodMap = [
        'hora' => 'horas',
        'dia' => 'dias',
        'semana' => 'semanas',
        'mês' => 'meses'
      ];
      $periodLabel = $amount === 1 ? $period : ($periodMap[$period] ?? $period);
      $total = (float)($latestOrder['price'] ?? 0) * $amount;
      $unit = (float)($latestOrder['price'] ?? 0);

      $purchaseDetails = [
        'service' => $currentChat['ad_title'] ?? '',
        'animals' => implode(', ', $animalList),
        'amount' => $amount,
        'price_period' => $periodLabel,
        'price' => number_format($unit, 2, ',', '.'),
        'total' => number_format($total, 2, ',', '.'),
        'unit_label' => number_format($unit, 2, ',', '.') . '€/ ' . htmlspecialchars($period),
      ];
      include_once('../modals/payment_modal.php');
    ?>
  <?php elseif (
  $_SESSION['user_id'] == $latestOrder['client_id'] &&
  in_array($status, ['pending', 'rejected'])
): ?>
    <form action="../actions/action_cancelRequest.php" method="post" class="cancel-order-form">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? $latestOrder['id'] ?? $latestOrder['rowid'] ?? '')) ?>">
      <button type="submit" class="cancel-order-btn">Cancelar pedido</button>
    </form>
  <?php elseif ($status === 'in_progress' && $_SESSION['user_id'] == $latestOrder['freelancer_id']): ?>
  <form action="../actions/action_completeService.php" method="post" class="cancel-order-form" style="margin-top:1em;">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
  <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? $latestOrder['id'] ?? $latestOrder['rowid'] ?? '')) ?>">
    <button type="submit" class="accept-order-btn" style="background:#81B29A;">Completar serviço</button>
  </form>
  <?php elseif (
  $status === 'completed' &&
  $_SESSION['user_id'] == $latestOrder['client_id'] &&
  (empty($latestOrder['reviewed']) || $latestOrder['reviewed'] == 0)
): ?>
  <button type="button" class="accept-order-btn" id="openLeaveReviewModalBtn" style="background:#81B29A;margin-top:1em;float:right;">Deixar avaliação</button>
  <?php include_once('../modals/leaveReview_modal.php'); ?>
<?php endif; ?>
        </div>
      <?php endif; ?>
      <form class="send-message-form" action="../actions/action_sendMessage.php" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
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

    // Payment modal logic
    const payBtn = document.getElementById('openPaymentModalBtn');
    const payForm = document.getElementById('payOrderForm');
    const paymentModal = document.getElementById('paymentModal');
    if (payBtn && payForm && paymentModal) {
      payForm.addEventListener('submit', function(e) {
        e.preventDefault();
        paymentModal.style.display = 'flex';
      });
    }
  });
</script>
<div class="modal-content review-modal-content">
  <!-- conteúdo do modal -->
</div>
<?php } ?>
