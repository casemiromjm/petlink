<?php
$modalDisplay = (isset($showPaymentModal) && $showPaymentModal) ? 'flex' : 'none';
?>
<link rel="stylesheet" href="../stylesheets/style.css">
<div id="paymentModal" class="modal-overlay" style="display:<?= $modalDisplay ?>;">
  <div class="modal-content payment-modal-content">
    <button id="closePaymentModal" class="modal-close" aria-label="Fechar">&times;</button>
    <h3 class="payment-modal-title">Pagamento</h3>

    <section class="payment-summary">
      <h4>Resumo da Compra</h4>
      <ul>
        <li><strong>Serviço:</strong> <?= htmlspecialchars($purchaseDetails['service'] ?? 'Serviço') ?></li>
        <li><strong>Animais:</strong> <?= $purchaseDetails['animals'] ?? '-' ?></li>
        <li><strong>Duração:</strong> <?= htmlspecialchars($purchaseDetails['amount'] ?? 1) . ' ' . htmlspecialchars($purchaseDetails['price_period'] ?? '') ?></li>
        <li>
          <strong>Preço total:</strong>
          <?= htmlspecialchars($purchaseDetails['total'] ?? '0.00') ?>€
          <span class="price-unit-label">(<?= htmlspecialchars($purchaseDetails['unit_label'] ?? '') ?>)</span>
        </li>
      </ul>
    </section>

    <form id="fakePaymentForm" action="#" method="post" autocomplete="off" class="payment-form">
      <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? $latestOrder['id'] ?? $latestOrder['rowid'] ?? '')) ?>">

      <label for="cardName">Nome no Cartão</label>
      <input type="text" id="cardName" name="cardName" required maxlength="40" placeholder="Nome completo">

      <label for="cardNumber">Número do Cartão</label>
      <input type="text" id="cardNumber" name="cardNumber" required maxlength="23" pattern="[\d ]{16,23}" placeholder="1234 5678 9012 3456" inputmode="numeric">

      <div class="payment-form-row">
        <div>
          <label for="cardExpiry">Validade</label>
          <input type="text" id="cardExpiry" name="cardExpiry" required maxlength="5" pattern="\d{2}/\d{2}" placeholder="MM/AA">
        </div>
        <div>
          <label for="cardCVC">CVC</label>
          <input type="text" id="cardCVC" name="cardCVC" required maxlength="4" pattern="\d{3,4}" placeholder="123">
        </div>
      </div>

      <button type="submit" class="payment-submit-btn">Pagar</button>
    </form>
    <div id="paymentSuccess" class="payment-success-message">
      Pagamento efetuado com sucesso!
    </div>
  </div>
</div>
<script>
  document.getElementById('closePaymentModal').onclick = function() {
    document.getElementById('paymentModal').style.display = 'none';
  };
  document.getElementById('fakePaymentForm').onsubmit = function(e) {
    e.preventDefault();

    const name = document.getElementById('cardName').value.trim();
    const number = document.getElementById('cardNumber').value.replace(/\s+/g, '');
    const expiry = document.getElementById('cardExpiry').value.trim();
    const cvc = document.getElementById('cardCVC').value.trim();

    if (name.split(' ').length < 2) {
      alert('Por favor, insira o nome completo do titular do cartão.');
      return;
    }
    if (!/^\d{16,19}$/.test(number)) {
      alert('O número do cartão deve ter entre 16 e 19 dígitos.');
      return;
    }
    const match = expiry.match(/^(\d{2})\/(\d{2})$/);
    if (!match) {
      alert('A validade deve estar no formato MM/AA.');
      return;
    }
    const month = parseInt(match[1], 10);
    const year = 2000 + parseInt(match[2], 10);
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1;
    if (month < 1 || month > 12 || year < currentYear || (year === currentYear && month < currentMonth)) {
      alert('A validade do cartão é inválida ou já expirou.');
      return;
    }
    if (!/^\d{3,4}$/.test(cvc)) {
      alert('O CVC deve ter 3 ou 4 dígitos.');
      return;
    }

    const orderIdInput = document.querySelector('input[name="order_id"]');
    if (orderIdInput) {
      fetch('../actions/action_pay.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'order_id=' + encodeURIComponent(orderIdInput.value)
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          alert('Erro ao processar pagamento!');
          return;
        }
        document.getElementById('fakePaymentForm').style.display = 'none';
        document.getElementById('paymentSuccess').style.display = 'block';
        setTimeout(() => {
          document.getElementById('paymentModal').style.display = 'none';
          document.getElementById('fakePaymentForm').style.display = '';
          document.getElementById('paymentSuccess').style.display = 'none';
        }, 1800);
      });
    }
  };
</script>