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
          <?php foreach ($ads as $ad):?>
            <a href="pages/adDetails.php?id=<?= htmlspecialchars((string)$ad->getId()) ?>" class="ad">
                    <div class="ad-image">
                    <?php
                        // default src
                        $adPhotoId = 'default';
                        $mediaIds = $ad->getMediaIds();
                        $filename = null;
                        $src = null;

                        if (!empty($mediaIds)) {
                            $stmt = $db->prepare('
                                SELECT file_name 
                                FROM Media 
                                WHERE media_id = ? 
                                LIMIT 1
                            ');
                            $stmt->execute([$mediaIds[0]]);
                            $filename = $stmt->fetchColumn();
                            $adPhotoId = 'not_default';
                        }

                        if ($adPhotoId === 'default') {
                            $src = '/resources/adPics/8.png';
                        } else {
                            $src = '/resources/adPics/' . htmlspecialchars((string)$filename) . '.png';
                        }
                    ?>

                            <img src="<?= $src ?>" alt="Imagem do anúncio" >
                        </div>
                    <div class="ad-content">
                        <div class="ad-header">
                            <div class="user-photo-container">
                            <?php
                                $profilePhotoId = $ad->getPhotoId();

                                if (empty($profilePhotoId)) {
                                    $src = '/resources/profilePics/0.png';
                                } else {
                                    $src = "/resources/profilePics/" . htmlspecialchars((string)$profilePhotoId) . ".png";
                                }
                                ?>

                                <img src="<?= htmlspecialchars($src) ?>" alt="Foto do utilizador" class="user-photo">
                            </div>
                            <span class="username">
                                <strong><?= htmlspecialchars($ad->getUsername()) ?></strong>
                            </span>
                        </div>

                        <h2 class="ad-title"><?= htmlspecialchars($ad->getTitle()) ?></h2>
                        <p class="ad-animals"><i class="fi fi-rr-paw"></i> <?= htmlspecialchars(implode(', ', $ad->getAnimals())) ?></p>
                        <p class="ad-location"><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($ad->getDistrict()) ?></p>
                        <p class="ad-price"><i class="fi fi-rr-euro"></i> <?= htmlspecialchars((string)$ad->getPrice()) ?>€ / <?= htmlspecialchars($ad->getPricePeriod()) ?></p>
                        <p class="ad-rating">
                            <i class="fi fi-rr-star"></i>
                            <?php
                                $averageRating = $ad->getAverageRating();
                                $reviewCount = $ad->getReviewCount();

                                if ($averageRating !== null && $reviewCount !== null) {
                                    echo number_format($averageRating, 1) . ' (' . $reviewCount . ' avaliaç' . ($reviewCount === 1 ? 'ão' : 'ões') . ')';
                                } else {
                                    echo 'Sem avaliações';
                                }
                            ?>
                        </p>
                    </div>
                </a>
                    <?php endforeach; ?>
        </div>
    </section>

<?php } ?>
