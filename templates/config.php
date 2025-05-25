<?php declare(strict_types = 1); ?>

<?php require_once(__DIR__ . '/../templates/sidebar.php'); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<script src="../javascript/script.js" defer></script>

<?php function drawConfig(string $csrf_token): void { ?>
  <?php if (isset($_GET['success'])): ?>
    <div id="success-message" class="success-message" style="margin-bottom: 0;">Alterações guardadas com sucesso.</div>
  <?php endif; ?>
  <section class="form-container">
    <h2>Configurações da Conta</h2>
    <?php if (isset($_GET['error'])): ?>
      <div class="error-message">
        <?php
          if ($_GET['error'] === 'invalid_password') echo 'Password atual incorreta.';
          elseif ($_GET['error'] === 'password_mismatch') echo 'As passwords não coincidem.';
          elseif ($_GET['error'] === 'duplicate_email') echo 'Este email já está a ser utilizado.';
          else echo 'Ocorreu um erro.';
        ?>
      </div>
    <?php endif; ?>
    <form id="config-form" action="../actions/action_config.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" readonly style="background: #ececec; opacity: 0.7; cursor: not-allowed;">

      <label for="new_password">Nova Password</label>
      <input type="password" id="new_password" name="new_password">

      <label for="confirm_password">Confirmar Nova Password</label>
      <input type="password" id="confirm_password" name="confirm_password">

      <label for="current_password">Password Atual</label>
      <p> Insira a sua password atual para confirmar as alterações.</p>
      <input type="password" id="current_password" name="current_password">

      <button type="submit">Guardar Alterações</button>
    </form>
    <script>
      // Make password fields required only if new_password is filled
      document.getElementById('config-form').addEventListener('submit', function(e) {
        const newPass = document.getElementById('new_password');
        const currPass = document.getElementById('current_password');
        const confPass = document.getElementById('confirm_password');
        if (newPass.value.length > 0) {
          currPass.required = true;
          confPass.required = true;
          if (!currPass.value || !confPass.value) {
            e.preventDefault();
            alert('Preencha a password atual e a confirmação da nova password.');
          }
        }
      });
    </script>
  </section>
<?php } ?>
