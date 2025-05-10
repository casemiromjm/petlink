<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<body class="profile-page">
    <?php drawProfiles(); ?>
</body>

<?php function drawProfiles() { ?>
  <nav class="side-nav">
    <ul>
      <li><a href="profiles.php">Dados Pessoais</a></li>
      <li><a href="animais.php">Animais</a></li>
      <li><a href="conf.php">Configurações</a></li>
    </ul>
  </nav>
  <section class="content">
    <div class="edit-profile-container">
      <div class="edit-header">
        <h2>Editar o perfil</h2>
      </div>
      <div class="profile-picture">
        <div class="profile-image-placeholder">
        </div>
        <a href="#" class="change-photo-link">Alterar foto de perfil</a>
      </div>
      <form class="profile-form">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome">
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
          <label for="telemovel">Telemóvel</label>
          <input type="tel" id="telemovel" name="telemovel">
        </div>
        <div class="form-group">
          <label for="localidade">Localidade</label>
          <input type="text" id="localidade" name="localidade">
        </div>
        <button type="submit" class="save-button">Guardar alterações</button>
      </form>
    </div>
  </section>
<?php } ?>