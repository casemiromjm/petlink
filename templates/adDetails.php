<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<script src="../javascript/script.js"></script>

<?php function drawAdDetails(array $ad, int $success): void { ?>
  <?php if ($success === 1): ?>
    <div id="success-message" class="success-message">Anúncio criado com sucesso</div>
  <?php endif; ?>
  <section class="ad-details">
    <div class="ad-user">
      <h2>
        <strong><?= htmlspecialchars($ad['username']) ?></strong>
        <span class="username"><?= htmlspecialchars($ad['username']) ?></span>
      </h2>
      <p><?= htmlspecialchars($ad['user_description'] ?? 'Descrição não disponível.') ?></p>
      <p><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($ad['district'])?></p>
      <p><i class="fi fi-rr-star"></i> 4.7/5 (32 avaliações)</p>
    </div>
    <div class="ad-images">
      <img src="<?= htmlspecialchars($ad['image_path'] ?? 'https://via.placeholder.com/600') ?>" alt="Imagem do anúncio">
    </div>

    <div class="ad-reviews">
      <h3>Avaliações recentes</h3>
      <p>Espaço reservado para avaliações futuras.</p>
    </div>

    <div class="ad-info">
      <div class="ad-info-header">
        <h1><?= htmlspecialchars($ad['title']) ?></h1>
        <span class="ad-price"><?= htmlspecialchars((string)$ad['price']) ?>€ / <?= htmlspecialchars($ad['price_period']) ?></span>
        <button>Enviar mensagem</button>
      </div>
      <p class="ad-description"><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
    </div>
  </section>
<?php } ?>
