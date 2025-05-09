<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAds(array $anuncios): void { ?>
  <section class="results">
    <div class="result-text">
      <h2>Encontrámos mais de <?= count($anuncios) ?> anúncios</h2>
    </div>
    <div class="ad-list">
      <?php if (empty($anuncios)): ?>
        <p>No ads found.</p>
      <?php else: ?>
        <?php foreach ($anuncios as $anuncio): ?>
          <a href="pages/adDetails.php?id=<?= htmlspecialchars((string)$anuncio['id']) ?>" class="ad">
            <div class="ad-header">
              <img src="https://via.placeholder.com/50" alt="Foto do utilizador" class="user-photo">
              <span class="username">
                <strong><?= htmlspecialchars($anuncio['name']) ?></strong>
              </span>
            </div>
            <h2 class="ad-title"><?= htmlspecialchars($anuncio['title']) ?></h2>
            <p class="ad-location"><i class="fi fi-rr-marker"></i> Porto, Campanhã</p>
            <p class="ad-price"><i class="fi fi-rr-euro"></i> <?= htmlspecialchars((string)$anuncio['price']) ?>€ / <?= htmlspecialchars($anuncio['price_period']) ?></p>
            <p class="ad-rating"><i class="fi fi-rr-star"></i> 4.7/5 (32 avaliações)</p>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
<?php } ?>