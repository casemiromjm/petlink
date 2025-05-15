<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawEditProfile(): void { ?>
  <section class="form-container">
    <h2>Editar Perfil</h2>
    <form action="../actions/action_editProfile.php" method="post" enctype="multipart/form-data">
      <div class="profile-picture">
        <div class="profile-image-placeholder">
          <img src="<?= htmlspecialchars($_SESSION['profile_photo'] ?? '../resources/default_profile.png') ?>" alt="Foto de perfil">
        </div>
        <label for="profile-photo" class="change-photo-link">Alterar foto de perfil</label>
        <input type="file" id="profile-photo" name="profile-photo" accept="image/*">
      </div>

      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>

      <label for="telemovel">Telemóvel</label>
      <input type="tel" id="telemovel" name="telemovel" value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>">

      <label for="localidade">Localidade</label>
      <input type="text" id="localidade" name="localidade" value="<?= htmlspecialchars($_SESSION['district'] ?? '') ?>">

      <button type="submit">Guardar Alterações</button>
    </form>
  </section>
<?php } ?>