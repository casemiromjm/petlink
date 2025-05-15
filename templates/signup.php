<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawSignUp(): void { ?>
  <section class="form-container signup">
    <h2>Sign Up</h2>
    <?php if (isset($_GET['error'])): ?>
      <p class="error-message">
        <?php
          if ($_GET['error'] === 'password_mismatch') {
              echo 'As passwords que inseriu não coincidem.';
          } elseif ($_GET['error'] === 'duplicate') {
              echo 'Este username ou email já está a ser utilizado.';
          }
        ?>
      </p>
    <?php endif; ?>
    <form action="../actions/action_signup.php" method="post">
      <label for="name">Nome</label>
      <input type="text" id="name" name="name" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="district">Distrito</label>
      <select id="district" name="district" required>
        <option value="" disabled selected>Selecione o seu distrito</option>
        <option value="Açores">Açores</option>
        <option value="Aveiro">Aveiro</option>
        <option value="Beja">Beja</option>
        <option value="Braga">Braga</option>
        <option value="Bragança">Bragança</option>
        <option value="Castelo Branco">Castelo Branco</option>
        <option value="Coimbra">Coimbra</option>
        <option value="Évora">Évora</option>
        <option value="Faro">Faro</option>
        <option value="Guarda">Guarda</option>
        <option value="Leiria">Leiria</option>
        <option value="Lisboa">Lisboa</option>
        <option value="Madeira">Madeira</option>
        <option value="Portalegre">Portalegre</option>
        <option value="Porto">Porto</option>
        <option value="Santarém">Santarém</option>
        <option value="Setúbal">Setúbal</option>
        <option value="Viana do Castelo">Viana do Castelo</option>
        <option value="Vila Real">Vila Real</option>
        <option value="Viseu">Viseu</option>
      </select>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirme a password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <button type="submit">Sign Up</button>
    </form>
    <p>Já tem uma conta? <a href="login.php">Entre aqui</a>.</p>
  </section>
<?php } ?>