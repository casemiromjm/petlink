<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAds(array $anuncios): void { ?>
  <section class="results">
    <div class="result-text">
      <h2>
            <?php
            $num_anuncios = count($anuncios);
            if ($num_anuncios == 0) {
                echo "Não encontrámos anúncios.";
            } elseif ($num_anuncios == 1) {
                echo "Encontrámos 1 anúncio";
            } elseif ($num_anuncios < 1000) {
                echo "Encontrámos $num_anuncios anúncios";
            } else {
                echo "Encontrámos mais de 1000 anúncios";
            }
            ?>
        </h2>
    </div>
    <div class="ad-list">
        <?php if (empty($anuncios)): ?>
            <p>Não foram encontrados anúncios.</p>
      <?php else: ?>
        <?php foreach ($anuncios as $anuncio): ?>
          <a href="pages/adDetails.php?id=<?= htmlspecialchars((string)$anuncio['id']) ?>" class="ad">
            <div class="ad-image">
              <img src="<?= htmlspecialchars($anuncio['image_path'] ?? 'https://via.placeholder.com/600') ?>" alt="Imagem do anúncio">
            </div>
            <div class="ad-content">
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
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
<?php } ?>
