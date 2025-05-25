<?php if (isset($currentChat, $selectedAdId)): ?>
<link rel="stylesheet" href="../stylesheets/style.css">
<?php
  require_once(__DIR__ . '/../database/connection.db.php');
  $db = getDatabaseConnection();
  $userId = $_SESSION['user_id'];
  $stmt = $db->prepare('SELECT COUNT(*) FROM ServiceRequests WHERE ad_id = ? AND client_id = ? AND status IN ("pending", "accepted", "rejected")');
  $stmt->execute([$selectedAdId, $userId]);
  $hasActiveRequest = $stmt->fetchColumn() > 0;

  $stmt = $db->prepare('SELECT price, price_period FROM Ads WHERE ad_id = ?');
  $stmt->execute([$selectedAdId]);
  $adData = $stmt->fetch(PDO::FETCH_ASSOC);
  $adPrice = $adData['price'] ?? 0;
  $adPricePeriod = $adData['price_period'] ?? 'hora';

  // Get allowed species for this ad
  $speciesStmt = $db->prepare('
    SELECT at.animal_id
    FROM Ad_animals aa
    JOIN Animal_types at ON aa.animal_id = at.animal_id
    WHERE aa.ad_id = ?
  ');
  $speciesStmt->execute([$selectedAdId]);
  $allowedSpecies = $speciesStmt->fetchAll(PDO::FETCH_COLUMN);

  // Get user's animals that match allowed species
  $animalCount = 0;
  $animals = [];
  if (count($allowedSpecies) > 0) {
    $stmt = $db->prepare(
      'SELECT ua.animal_id, ua.name
       FROM user_animals ua
       WHERE ua.user_id = ? AND ua.species IN (' . implode(',', array_fill(0, count($allowedSpecies), '?')) . ')'
    );
    $params = array_merge([$_SESSION['user_id']], $allowedSpecies);
    $stmt->execute($params);
    $animals = $stmt->fetchAll();
    $animalCount = count($animals);
  }
?>
<!-- Error bar under navbar -->
<div id="general-error-bar" class="error-bar" style="display:none;">
  Já existe um pedido ativo para este anúncio. Por favor, cancele o pedido atual antes de fazer um novo.
</div>
<div id="buyServiceModal" class="modal-overlay" style="display:none;">
  <div class="modal-content">
    <button id="closeBuyModal" class="modal-close" aria-label="Fechar">&times;</button>
    <form id="buyServiceForm" action="../actions/action_createOrder.php" method="post">
      <input type="hidden" name="ad_id" value="<?= htmlspecialchars((string)$selectedAdId) ?>">
      <h3>Comprar Serviço</h3>
      <label>Selecione o(s) animal(is):</label>
      <div id="animalCheckboxes" style="max-height:140px;overflow-y:auto;border:1px solid #ccc;padding:8px;border-radius:6px;margin-bottom:1em; display:flex; flex-direction:column; align-items:center;">
        <?php if ($animalCount > 0): ?>
          <?php foreach ($animals as $animal): ?>
            <label style="display:block;margin-bottom:4px;text-align:center;width:100%;">
              <input type="checkbox" class="animal-checkbox" name="animals[]" value="<?= htmlspecialchars((string)$animal['animal_id']) ?>">
              <?= htmlspecialchars($animal['name']) ?>
            </label>
          <?php endforeach; ?>
        <?php else: ?>
          <span style="color:#888; text-align:center; display:block;">Não tem animais elegíveis para este serviço.</span>
        <?php endif; ?>
      </div>
      <label for="amount">Quantidade de tempo:</label>
      <input type="number" id="amount" name="amount" min="1" value="1" required style="width:100px;">
      <span id="priceUnit"><?= htmlspecialchars($adPricePeriod) ?></span>
      <div style="margin:1em 0;">
        <strong>Preço final: <span id="finalPrice"><?= htmlspecialchars((string)$adPrice) ?></span>€</strong>
      </div>
      <button type="submit" style="background:#81B29A;color:#fff;border:none;padding:0.5em 1em;border-radius:6px;cursor:pointer;" <?= $animalCount === 0 ? 'disabled' : '' ?>>Confirmar Compra</button>
    </form>
  </div>
</div>
<script>
  // Modal logic
  const hasActiveRequest = <?= json_encode($hasActiveRequest) ?>;
  const buyBtn = document.getElementById('buyServiceBtn');
  const modal = document.getElementById('buyServiceModal');
  const errorMsg = document.getElementById('general-error-bar');

  if (buyBtn) {
    buyBtn.onclick = function(e) {
      if (hasActiveRequest) {
        e.preventDefault();
        errorMsg.style.display = 'block';
        setTimeout(() => { errorMsg.style.display = 'none'; }, 2500);
      } else {
        modal.style.display = 'flex';
        document.getElementById('amount').value = 1;
        updateFinalPrice();
      }
    };
    buyBtn.addEventListener('click', updateFinalPrice);
  }

  document.getElementById('closeBuyModal').onclick = function() {
    modal.style.display = 'none';
  };

  // Price calculation
  const pricePerUnit = <?= json_encode($adPrice ?? 0) ?>;
  function updateFinalPrice() {
    const amount = parseInt(document.getElementById('amount').value) || 1;
    document.getElementById('finalPrice').textContent = (amount * pricePerUnit).toFixed(2);
  }
  document.getElementById('amount').addEventListener('input', updateFinalPrice);

  // Optional: close modal on overlay click
  modal.onclick = function(e) {
    if (e.target === this) this.style.display = 'none';
  };
</script>
<?php endif; ?>