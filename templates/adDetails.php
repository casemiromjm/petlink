<?php 

declare(strict_types = 1); 
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/anuncios.class.php');
require_once(__DIR__ . '/../database/reviews.class.php');

function drawAdDetails(): void {
    $db = getDatabaseConnection();
    $adId = isset($_GET['id']) ? intval($_GET['id']) : null;
    $success = isset($_GET['success']) ? intval($_GET['success']) : 0;

    if ($adId === null) {
        die('Anúncio não encontrado.');
    }

    $ad = Ad::getById($db, $adId);

    if (!$ad) {
        die('Anúncio não encontrado.');
    }

    $reviews = Reviews::getByAdId($db, (int)$adId);
    $averageRating = Reviews::getAverageRatingForAd($db, (int)$adId);
    $reviewCount = Reviews::getReviewCountForAd($db, (int)$adId);
?>
<link rel="stylesheet" href="../stylesheets/style.css">
<script src="../javascript/script.js"></script>

<section class="ad-details">
    <div class="ad-user">
        <div class="ad-user-header">
        <?php
        $profilePhotoId = $ad->getPhotoId();

        if (empty($profilePhotoId) || $profilePhotoId === 'default') {
            $src = '/resources/profilePics/0.png';
        }
        // If it's a numeric ID (default images 1.png, 2.png, etc.)
        elseif (is_numeric($profilePhotoId)) {
            $src = '/resources/profilePics/' . $profilePhotoId . '.png';
        }
        // Fallback for invalid cases
        else {
            $src = '/resources/profilePics/0.png';
        }
        ?>
        <img src="<?= htmlspecialchars($src) ?>" alt="Profile Photo" class="ad-user-photo">
        <div class="ad-user-info">
            <strong><?= htmlspecialchars(($ad->getName()) ?? 'Nome não disponível') ?></strong>
            <span class="username"><?= htmlspecialchars($ad->getUsername() ?? 'Usuário não disponível') ?></span>
        </div>
        </div>
        <?php
        if (!empty($ad->getCreatedAt())) {
            $date = new DateTime;
            $monthNum = (int)$date->format('n');
            $year = $date->format('Y');
            $meses = [
                1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
                5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
                9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
            ];
            $membroDesde = ucfirst($meses[$monthNum]) . " de " . $year;
        } else {
            $membroDesde = "Data de registo desconhecida";
        }
        ?>
        <p><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($ad->getDistrict() ?? 'Localização não disponível') ?></p>
        <?php
        if ($reviewCount==0){?>
            <p><i class="fi fi-rr-star"></i> Sem avaliações</p>
        <?php
        }
        else{
        ?>
            <p><i class="fi fi-rr-star"></i> <?= number_format($averageRating, 1) ?>/5 (<?= $reviewCount ?> avaliações)</p>
        <?php } ?>
        <p><i class="fi fi-rr-calendar"></i> Membro desde <?= htmlspecialchars($membroDesde) ?></p>
        <div style="text-align:center; margin-top: 10px;">
            <a href="../pages/userprofile.php?username=<?= urlencode($ad->getUsername()) ?>" class="profile-link">Ver perfil</a>
        </div>
    </div>
    
    <div class="ad-images carousel-container">
        <div class="carousel-track">
        <?php
        $adMediaIds = $ad->getMediaIds() ?? [];

        if (empty($adMediaIds)) {
            $src = '/resources/adPics/8.png';
        ?>
            <img src="<?= htmlspecialchars($src) ?>" alt="Imagem do anúncio" class="carousel-slide active">
        <?php
        } else {
            $placeholders = implode(',', array_fill(0, count($adMediaIds), '?'));
            $stmt = $db->prepare("
                SELECT media_id, file_name 
                FROM Media 
                WHERE media_id IN ($placeholders)
            ");
            $stmt->execute($adMediaIds);
            $mediaFiles = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            $isFirstSlide = true;
            foreach ($adMediaIds as $mediaId) {
                $src = '/resources/adPics/' . htmlspecialchars($mediaFiles[$mediaId]) . '.png';
        ?>
                <img src="<?= htmlspecialchars($src) ?>" alt="Imagem do anúncio" class="carousel-slide <?= $isFirstSlide ? 'active' : '' ?>">
        <?php
                $isFirstSlide = false;
            }
        }
        ?>
        </div>
        <?php if (count($adMediaIds) > 1): ?>
        <button class="carousel-button prev">&#10094;</button>
        <button class="carousel-button next">&#10095;</button>
        <?php endif; ?>
    </div>

    <div class="reviews">
        <?php drawReviews($reviews); ?>
    </div>

    <div class="ad-info">
        <div class="ad-info-header">
            <h1><?= htmlspecialchars($ad->getTitle() ?? 'Título não disponível') ?></h1>
            <span class="ad-price"><?= htmlspecialchars((string)($ad->getPrice() ?? '0.00')) ?>€ / <?= htmlspecialchars($ad->getPricePeriod() ?? 'período não disponível') ?></span>

            <?php 
            $isAdmin = false;
            if (isset($_SESSION['username'])) {
                $stmt = $db->prepare('SELECT is_admin FROM Users WHERE username = ?');
                $stmt->execute([$_SESSION['username']]);
                $isAdmin = (bool)$stmt->fetchColumn();
            }

            if (isset($_SESSION['username']) && ($ad->getUsername() === $_SESSION['username'] || $isAdmin)):
            ?>
            <!-- owner and admin see edit options -->
            <div style="display:inline;">
                <form action="../pages/edit_ad.php" method="get">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)($ad->getId())) ?>">
                    <button type="submit" class="edit-button">Editar Anúncio</button>
                </form>
            </div>
            <?php endif; ?>

            <!-- message button to everyone except you -->
            <?php if (isset($_SESSION['username']) && ($ad->getUsername() !== $_SESSION['username'] || $isAdmin)): ?>
            <form action="../pages/messages.php" method="get" style="display:inline;">
                <input type="hidden" name="ad" value="<?= htmlspecialchars((string)$ad->getId()) ?>">
                <input type="hidden" name="to" value="<?= htmlspecialchars((string)$ad->getUserId()) ?>">
                <button type="submit" class="message-button">Enviar mensagem</button>
            </form>
            <?php endif; ?>
        </div>
        <p class="ad-description"><?= nl2br(htmlspecialchars($ad->getDescription() ?? 'Descrição não disponível')) ?></p>
    </div>
</section>
<script src="/javascript/carrouselButtons.js"></script>
<?php } ?>