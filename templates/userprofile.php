<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawUserProfile(array $user, array $animals, array $reviews, array $userAds, PDO $db): void { ?>
  <?php
    if (!empty($user['created_at'])) {
      $date = new DateTime($user['created_at']);
      $monthNum = (int)$date->format('n');
      $year = $date->format('Y');
      $meses = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
      ];
      $memberSince = ucfirst($meses[$monthNum]) . " de " . $year;
    } else {
      $memberSince = "Data de registo desconhecida";
    }
  ?>
  <section class="user-profile-container">
    <!-- Cabeçalho do Perfil -->
    <div class="profile-header-row">
      <div class="profile-header-card">
        <div class="profile-header-main">
          <div class="profile-photo-container">
            <?php
              $profilePhoto = $user['photo_id'] ?? null;
              if (
                !$profilePhoto ||
                $profilePhoto === 'default' ||
                $profilePhoto === '../resources/default_profile.png'
              ) {
                $profilePhotoSrc = '../resources/profilePics/0.png';
              } else {
                // Extrai só o nome do ficheiro, independentemente do que está na base de dados
                $profilePhotoSrc = '../resources/profilePics/' . basename($profilePhoto);
              }
            ?>
            <img src="<?= htmlspecialchars($profilePhotoSrc) ?>" alt="Foto de perfil" class="profile-photo">
          </div>
          <div class="profile-info-container">
            <div class="profile-name-container">
              <h1 class="profile-name"><?= htmlspecialchars($user['name'] ?? 'Nome não disponível') ?></h1>
              <span class="profile-username">@<?= htmlspecialchars($user['username'] ?? 'Username não disponível') ?></span>
            </div>
          </div>
        </div>
        <div class="profile-info-container">
          <div class="meta-item">
            <i class="fi fi-rr-marker"></i>
            <span><?= htmlspecialchars($user['district'] ?? 'Localização não disponível') ?></span>
          </div>
          <div class="meta-item">
            <i class="fi fi-rr-calendar"></i>
            <span>Membro desde <?= htmlspecialchars($memberSince) ?></span>
          </div>
          <div class="meta-item">
            <i class="fi fi-rr-star"></i>
            <span>
              <?php
                if (!empty($reviews)) {
                  $sum = 0;
                  foreach ($reviews as $review) $sum += $review['rating'];
                  $media = round($sum / count($reviews), 1);
                  echo $media . "/5 (" . count($reviews) . " avaliações)";
                } else {
                  echo "Sem avaliações";
                }
              ?>
            </span>
          </div>
        </div>
      </div>
      <div class="profile-section-card profile-about-card">
        <div class="section-header">
          <i class="fi fi-rr-user"></i>
          <h2>Sobre</h2>
        </div>
        <div class="section-content">
          <p class="profile-bio"><?= nl2br(htmlspecialchars($user['user_description'] ?? 'Sem descrição.')) ?></p>
        </div>
      </div>
    </div>

    <!-- Secção Animais -->
    <div class="profile-section-card">
      <div class="section-header">
        <i class="fi fi-rr-paw"></i>
        <h2>Animais</h2>
      </div>
      <div class="section-content">
        <?php if (!empty($animals)): ?>
          <div class="animal-grid">
            <?php foreach ($animals as $animal): ?>
              <div class="animal-card">
                <div class="animal-image-container">
                  <img src="<?= htmlspecialchars(str_replace('./', '../', $animal['animal_picture'] ?? '../resources/default_animal.png')) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                </div>
                <div class="animal-info">
                  <h3><?= htmlspecialchars($animal['name']) ?></h3>
                  <p class="animal-type"><?= htmlspecialchars($animal['animal_name']) ?></p>
                  <p class="animal-age"><?= htmlspecialchars((string)$animal['age']) ?> anos</p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="fi fi-rr-search-alt"></i>
            <p>Este utilizador ainda não adicionou animais.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Secção Avaliações -->
    <div class="profile-section-card">
      <div class="section-header">
        <i class="fi fi-rr-star"></i>
        <h2>Avaliações</h2>
      </div>
      <div class="section-content">
        <?php if (!empty($reviews)): ?>
          <div class="reviews-list">
            <?php foreach ($reviews as $review): ?>
              <div class="review-card">
                <div class="review-header">
                  <div class="review-rating">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                      <i class="fi fi-rr-star<?= $i < $review['rating'] ? '' : '-empty' ?>"></i>
                    <?php endfor; ?>
                  </div>
                  <span class="review-date"><?= htmlspecialchars((new DateTime($review['created_at']))->format('d/m/Y')) ?></span>
                </div>
                <p class="review-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                <div class="review-author">
                  <span>— <?= htmlspecialchars($review['client_username']) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="fi fi-rr-comment-alt"></i>
            <p>Este utilizador ainda não tem avaliações.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Secção Anúncios -->
    <div class="profile-section-card">
      <div class="section-header">
        <i class="fi fi-rr-bullhorn"></i>
        <h2>Anúncios</h2>
      </div>
      <div class="section-content">
        <?php if (!empty($userAds)): ?>
          <div class="user-ads-grid">
            <?php foreach ($userAds as $ad): ?>
              <a class="user-ad-card" href="../pages/adDetails.php?id=<?= htmlspecialchars((string)$ad['ad_id']) ?>">
                <div class="user-ad-image">
                  <?php
                    $adImage = $ad['image_path'] ?? null;
                    if (!$adImage || $adImage === 'default' || $adImage === '../resources/default_ad.png') {
                      $adImageSrc = '../resources/adPics/8.png';
                    } else {
                      $adImageSrc = (strpos($adImage, 'adPics/') !== false ? '../resources/' : '../resources/adPics/') . basename($adImage);
                    }
                  ?>
                  <img src="<?= htmlspecialchars($adImageSrc) ?>" alt="Imagem do anúncio">
                </div>
                <div class="user-ad-info">
                  <h3><?= htmlspecialchars($ad['title']) ?></h3>
                  <div class="user-ad-animals">
                    <i class="fi fi-rr-paw"></i>
                    <?php
                      $adAnimals = getAnuncioAnimals($db, $ad['ad_id']);
                      echo htmlspecialchars(implode(', ', $adAnimals));
                    ?>
                  </div>
                  <div class="user-ad-price">
                    <i class="fi fi-rr-euro"></i>
                    <?= htmlspecialchars((string)$ad['price']) ?>€ / <?= htmlspecialchars($ad['price_period']) ?>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="fi fi-rr-bullhorn"></i>
            <p>Este utilizador ainda não publicou anúncios.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php } ?>