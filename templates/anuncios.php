<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAds(array $ads, $totalAds, PDO $db): void { ?>
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
          <?php foreach ($ads as $ad): ?>
            <a href="pages/adDetails.php?id=<?= htmlspecialchars((string)$ad['id']) ?>" class="ad">
                    <div class="ad-image">
                    <?php
                            $adPhotoId = 'default';

                            if (isset($ad['media_ids']) && is_array($ad['media_ids']) && !empty($ad['media_ids'])) {
                                $adPhotoId = $ad['media_ids'][0];
                            }

                            if ($adPhotoId === 'default') {
                                $src = '/resources/adPics/8.png';
                            } else {
                                $src = "/resources/adPics/" . htmlspecialchars((string)$adPhotoId) . ".png";
                            }

                                ?>

                                <img src="<?= htmlspecialchars($src) ?>"  alt="Imagem do anúncio" >
                            </div>
                    <div class="ad-content">
                        <div class="ad-header">
                            <div class="user-photo-container">
                            <?php
                                $profilePhotoId = $ad['photo_id'] ?? 'default';

                                if ($profilePhotoId === 'default') {
                                    $src = '/resources/profilePics/0.png';
                                } else {
                                    $src = "/resources/profilePics/" . $profilePhotoId . ".png";
                                }
                                ?>

                                <img src="<?= htmlspecialchars($src) ?>"  alt="Foto do utilizador" class="user-photo">
                            </div>
                            <span class="username">
                                <strong><?= htmlspecialchars($ad['username'] ?? '') ?></strong>
                            </span>
                          </div>
                          <?php $animals = getAnuncioAnimals($db, $ad['id']); ?>
                        <h2 class="ad-title"><?= htmlspecialchars($ad['title'] ?? '') ?></h2>
                        <p class="ad-animals"><i class="fi fi-rr-paw"></i> <?= htmlspecialchars(implode(', ', $animals)) ?></p>
                        <p class="ad-location"><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($ad['district']) ?></p>
                        <p class="ad-price"><i class="fi fi-rr-euro"></i> <?= htmlspecialchars((string)($ad['price'] ?? '')) ?>€ / <?= htmlspecialchars($ad['price_period'] ?? '') ?></p>
                        <p class="ad-rating"><i class="fi fi-rr-star"></i> 4.7/5 (32 avaliações)</p>
                    </div>
                  </a>
                    <?php endforeach; ?>
        </div>
    </section>

<?php } ?>
