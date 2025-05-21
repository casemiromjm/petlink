<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<script src="../javascript/script.js"></script>

<?php function drawAdDetails(array $ad, int $success): void { ?>
  <?php if ($success === 1): ?>
    <div id="success-message" class="success-message">Anúncio criado com sucesso</div>
  <?php endif; ?>
  <section class="ad-details">
    <div class="ad-user">
      <div class="ad-user-header">
        <?php
          $profilePhotoId = $ad['photo_id'] ?? 'default';

          if (
              !$profilePhotoId ||
              $profilePhotoId === 'default' ||
              $profilePhotoId === '../resources/default_profile.png'
          ) {
              $src = '/resources/profilePics/0.png';
          } elseif (is_numeric($profilePhotoId)) {
              // Old style: just an id (int or numeric string)
              $src = "/resources/profilePics/" . $profilePhotoId . ".png";
          } else {
              // New style: filename
              $basename = basename((string)$profilePhotoId);
              $src = "/resources/profilePics/" . $basename;
          }
          ?>

          <img src="<?= htmlspecialchars($src) ?>" alt="Profile Photo" class="ad-user-photo">

        <div class="ad-user-info">
          <strong><?= htmlspecialchars($ad['name'] ?? 'Nome não disponível') ?></strong>
          <span class="username"><?= htmlspecialchars($ad['username'] ?? 'Usuário não disponível') ?></span>
        </div>
      </div>
      <?php
        if (!empty($ad['created_at'])) {
          $date = new DateTime($ad['created_at']);
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
      <p><i class="fi fi-rr-marker"></i> <?= htmlspecialchars($ad['district'] ?? 'Localização não disponível') ?></p>
      <p><i class="fi fi-rr-star"></i> 4.7/5 (32 avaliações)</p>
      <p><i class="fi fi-rr-calendar"></i> Membro desde <?= htmlspecialchars($membroDesde) ?></p>
      <div style="text-align:center; margin-top: 10px;">
        <a href="../pages/userprofile.php?username=<?= urlencode($ad['username']) ?>" class="profile-link">Ver perfil</a>
      </div>
    </div>
    <div class="ad-images"><div class="ad-images carousel-container">
    <div class="carousel-track">
        <?php
          $adMediaIds = $ad['mediaIds'] ?? [];

        if (empty($adMediaIds)) {
            $src = '/resources/adPics/8.png';
            ?>
            <img src="<?= htmlspecialchars($src) ?>" alt="Imagem do anúncio" class="carousel-slide active">
            <?php
        } else {

          $isFirstSlide = true;
            foreach ($adMediaIds as $mediaId) {
                $src = '/resources/adPics/' . htmlspecialchars((string)$mediaId) . '.png';
                ?>
                <img src="<?= htmlspecialchars($src) ?>" alt="Imagem do anúncio" class="carousel-slide <?= $isFirstSlide ? 'active' : '' ?>">
                <?php
                $isFirstSlide = false;
            }
        }
        ?>
    </div>
    <?php if (count($adMediaIds) > 1):  ?>
    <button class="carousel-button prev">&#10094;</button> <button class="carousel-button next">&#10095;</button> <?php endif; ?>

    </div></div>

    <div class="ad-reviews">
      <h3>Avaliações recentes</h3>
      <p>Espaço reservado para avaliações futuras.</p>
    </div>

    <div class="ad-info">
      <div class="ad-info-header">
        <h1><?= htmlspecialchars($ad['title'] ?? 'Título não disponível') ?></h1>
        <span class="ad-price"><?= htmlspecialchars((string)($ad['price'] ?? '0.00')) ?>€ / <?= htmlspecialchars($ad['price_period'] ?? 'período não disponível') ?></span>
        <?php if (isset($_SESSION['user_id']) && isset($ad['user_id']) && $_SESSION['user_id'] == $ad['user_id']): ?>
          <form action="#" method="get" style="display:inline;">
            <button type="button" class="edit-ad-button" onclick="window.location.href='../pages/editAd.php?id=<?= htmlspecialchars((string)$ad['id']) ?>'">Editar anúncio</button>
          </form>
        <?php else: ?>
          <form action="../pages/messages.php" method="get" style="display:inline;">
            <input type="hidden" name="ad" value="<?= htmlspecialchars((string)$ad['id']) ?>">
            <input type="hidden" name="to" value="<?= htmlspecialchars((string)$ad['user_id']) ?>">
            <button type="submit" class="message-button">Enviar mensagem</button>
          </form>
        <?php endif; ?>
      </div>
      <p class="ad-description"><?= nl2br(htmlspecialchars($ad['description'] ?? 'Descrição não disponível')) ?></p>
    </div>
  </section>
  <script src="/javascript/carrouselButtons.js"></script> </body>
<?php } ?>
