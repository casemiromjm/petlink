<?php declare(strict_types = 1);
function drawReviews(array $reviews): void {
    ?>
    <link rel="stylesheet" href="../stylesheets/style.css">
    <div class="ad-reviews">
        <h3>Avaliações recentes</h3>
        <?php if (empty($reviews)): ?>
            <p>Ainda não há avaliações para este anúncio.</p>
        <?php else: ?>
            <?php foreach ($reviews as $review):
                $client = $review->getUserObject();
                $clientName = $client ? htmlspecialchars($client->getUsername()) : 'Utilizador Desconhecido';
                $clientUsername = $client ? htmlspecialchars($client->getName()) : 'desconhecido';
                $clientPhotoId = $client ? $client->getPhotoId() : 0;

                $profilePhotoSrc = ($clientPhotoId === 0) ? '/resources/profilePics/0.png' : "/resources/profilePics/" . htmlspecialchars((string)$clientPhotoId) . ".png";

                $reviewDate = "Data desconhecida";
                if ($review->getCreatedAt() !== null && $review->getCreatedAt() !== '') {
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $review->getCreatedAt());
                    if ($date !== false) {
                        $reviewDate = $date->format('d/m/Y');
                    }
                }
            ?>
                <div class="review-item">
                    <div class="review-header">
                        <img src="<?= htmlspecialchars($profilePhotoSrc) ?>" alt="Profile Photo" class="review-user-photo">
                        <div class="review-user-info">
                            <strong><?= $clientName ?></strong>
                            <span class="username">@<?= $clientUsername ?></span>
                            <span class="review-date"><?= htmlspecialchars($reviewDate) ?></span>
                        </div>
                        <div class="review-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fi fi-rr-star <?= ($i <= $review->rating) ? 'filled' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="review-comment"><?= nl2br(htmlspecialchars($review->comment ?? '')) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php } ?>
