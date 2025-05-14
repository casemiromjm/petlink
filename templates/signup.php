<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawSignUp(): void { ?>
  <section class="signup">
    <h2>Sign Up</h2>
    <?php if (isset($_GET['error'])): ?>
      <p class="error-message">
        <?php
          if ($_GET['error'] === 'password_mismatch') {
              echo 'Passwords do not match.';
          } elseif ($_GET['error'] === 'duplicate') {
              echo 'Username or email already exists.';
          }
        ?>
      </p>
    <?php endif; ?>
    <form action="../actions/action_signup.php" method="post">
        
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="district">District</label>
      <select id="district" name="district" required>
        <option value="" disabled selected>Select your district</option>
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

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>


      <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Log in here</a>.</p>
  </section>
<?php } ?>