<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAds(array $anuncios, $totalAds, PDO $db): void { ?>
    <section class="results">
        <div class="result-text">
            <h2>
                <?php
                $num_anuncios = $totalAds;
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
          <?php foreach ($anuncios as $anuncio): ?>
            <a href="pages/adDetails.php?id=<?= htmlspecialchars((string)$anuncio['id']) ?>" class="ad">
                    <div class="ad-image">
                        <img src="<?= htmlspecialchars($anuncio['image_path'] ?? 'https://via.placeholder.com/600') ?>" alt="Imagem do anúncio">
                    </div>
                    <div class="ad-content">
                        <div class="ad-header">
                            <div class="user-photo-container"> <img src="<?= htmlspecialchars($anuncio['profile_photo'] ?? 'https://via.placeholder.com/50/AAAAAA/000000?Text=User') ?>" alt="Foto do utilizador" class="user-photo">
                            </div>
                            <span class="username">
                                <strong><?= htmlspecialchars($anuncio['username'] ?? '') ?></strong>
                            </span>
                          </div>
                          <?php $animals = getAnuncioAnimals($db, $anuncio['id']); ?>
                        <h2 class="ad-title"><?= htmlspecialchars($anuncio['title'] ?? '') ?></h2>
                        <p class="ad-animals"><i class="fi fi-rr-paw"></i> <?= htmlspecialchars(implode(', ', $animals)) ?></p>
                        <p class="ad-location"><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($anuncio['district']) ?></p>
                        <p class="ad-price"><i class="fi fi-rr-euro"></i> <?= htmlspecialchars((string)($anuncio['price'] ?? '')) ?>€ / <?= htmlspecialchars($anuncio['price_period'] ?? '') ?></p>
                        <p class="ad-rating"><i class="fi fi-rr-star"></i> 4.7/5 (32 avaliações)</p>
                    </div>
                  </a>
                    <?php endforeach; ?>
        </div>
    </section>

<?php } ?>
