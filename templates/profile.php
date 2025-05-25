<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<script src="../javascript/script.js" defer></script>

<?php function drawEditProfile($csrf_token): void { ?>
  <?php if (isset($_GET['success']) && intval($_GET['success']) === 1): ?>
    <div id="success-message" class="success-message">Dados atualizados com sucesso</div>
  <?php endif; ?>
  <section class="form-container">
    <h2>Editar Perfil</h2>
    <form action="../actions/action_editProfile.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
      <div class="profile-picture">
        <div class="profile-image-placeholder">
          <img src="<?= htmlspecialchars(str_replace('./', '../', $_SESSION['profile_photo'] ?? '../resources/default_profile.png')) ?>" alt="Foto de perfil">
        </div>
        <label for="profile-photo" class="change-photo-link">Mudar foto de perfil</label>
        <input type="file" id="profile-photo" name="profile-photo" accept="image/*" class="hidden-input">
      </div>

      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" required>

      <label for="user_description">Descrição</label>
      <textarea id="user_description" name="user_description" rows="5" required><?= htmlspecialchars($_SESSION['user_description'] ?? '') ?></textarea>

      <label for="telemovel">Telemóvel</label>
      <input type="tel" id="telemovel" name="telemovel" value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>">

      <label for="localidade">Localidade</label>
      <select id="localidade" name="localidade" required>
        <option value="" disabled>Selecione o seu distrito</option>
        <option value="Açores" <?= ($_SESSION['district'] ?? '') === 'Açores' ? 'selected' : '' ?>>Açores</option>
        <option value="Aveiro" <?= ($_SESSION['district'] ?? '') === 'Aveiro' ? 'selected' : '' ?>>Aveiro</option>
        <option value="Beja" <?= ($_SESSION['district'] ?? '') === 'Beja' ? 'selected' : '' ?>>Beja</option>
        <option value="Braga" <?= ($_SESSION['district'] ?? '') === 'Braga' ? 'selected' : '' ?>>Braga</option>
        <option value="Bragança" <?= ($_SESSION['district'] ?? '') === 'Bragança' ? 'selected' : '' ?>>Bragança</option>
        <option value="Castelo Branco" <?= ($_SESSION['district'] ?? '') === 'Castelo Branco' ? 'selected' : '' ?>>Castelo Branco</option>
        <option value="Coimbra" <?= ($_SESSION['district'] ?? '') === 'Coimbra' ? 'selected' : '' ?>>Coimbra</option>
        <option value="Évora" <?= ($_SESSION['district'] ?? '') === 'Évora' ? 'selected' : '' ?>>Évora</option>
        <option value="Faro" <?= ($_SESSION['district'] ?? '') === 'Faro' ? 'selected' : '' ?>>Faro</option>
        <option value="Guarda" <?= ($_SESSION['district'] ?? '') === 'Guarda' ? 'selected' : '' ?>>Guarda</option>
        <option value="Leiria" <?= ($_SESSION['district'] ?? '') === 'Leiria' ? 'selected' : '' ?>>Leiria</option>
        <option value="Lisboa" <?= ($_SESSION['district'] ?? '') === 'Lisboa' ? 'selected' : '' ?>>Lisboa</option>
        <option value="Madeira" <?= ($_SESSION['district'] ?? '') === 'Madeira' ? 'selected' : '' ?>>Madeira</option>
        <option value="Portalegre" <?= ($_SESSION['district'] ?? '') === 'Portalegre' ? 'selected' : '' ?>>Portalegre</option>
        <option value="Porto" <?= ($_SESSION['district'] ?? '') === 'Porto' ? 'selected' : '' ?>>Porto</option>
        <option value="Santarém" <?= ($_SESSION['district'] ?? '') === 'Santarém' ? 'selected' : '' ?>>Santarém</option>
        <option value="Setúbal" <?= ($_SESSION['district'] ?? '') === 'Setúbal' ? 'selected' : '' ?>>Setúbal</option>
        <option value="Viana do Castelo" <?= ($_SESSION['district'] ?? '') === 'Viana do Castelo' ? 'selected' : '' ?>>Viana do Castelo</option>
        <option value="Vila Real" <?= ($_SESSION['district'] ?? '') === 'Vila Real' ? 'selected' : '' ?>>Vila Real</option>
        <option value="Viseu" <?= ($_SESSION['district'] ?? '') === 'Viseu' ? 'selected' : '' ?>>Viseu</option>
      </select>

      <button type="submit">Guardar Alterações</button>
    </form>
  </section>
<?php } ?>
