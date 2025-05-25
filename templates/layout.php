<?php declare(strict_types = 1); ?>
<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../init.php');

// Ver se há mensagens nao vistas
$hasUnreadMessages = false;
$isLoggedIn = false;
$isAdmin = false;

if (isset($_SESSION['user_id'])) {
  $db = getDatabaseConnection();
  $stmt = $db->prepare('SELECT COUNT(*) FROM Messages WHERE to_user_id = ? AND is_read = 0');
  $stmt->execute([$_SESSION['user_id']]);
  $isLoggedIn = true;
  $hasUnreadMessages = $stmt->fetchColumn() > 0;
  $isAdmin = User::isUserAdmin($db, $_SESSION['user_id']);
}
?>
<?php function drawHeader() { global $isLoggedIn, $isAdmin, $hasUnreadMessages; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/resources/logo.png">
  <link rel="stylesheet" href="../stylesheets/style.css">
  <link rel="stylesheet" href="../stylesheets/responsive.css">
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  <title>PetLink</title>
</head>
<body>
  <header>
  <div class="logo">
    <a href="../index.php">
      <img src="../resources/logo.png" alt="logo">
    </a>
    <h1><a href="../index.php">PetLink</a></h1>
  </div>
    <nav class="navbar-container">
      <div class="navbar-flex">
        <div class="nav-center">
          <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a href="../index.php">Contratar Serviço</a></li>
              <li class="nav-divider">|</li>
              <li><a href="../pages/adCreate.php">Anunciar Serviço</a></li>
              <?php if ($isAdmin): ?>
                <li class="nav-divider">|</li>
                <li><a href="/pages/admin.php">Painel de Admin</a></li>
              <?php endif; ?>
            <?php endif; ?>
          </ul>
        </div>
        <div class="nav-right">
          <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
            <li style="position:relative;">
              <a href="../pages/messages.php">Mensagens
                <span class="envelope-icon-wrapper" style="position:relative; display:inline-block;">
                  <i class="fi fi-rr-envelope"></i>
                  <?php if ($hasUnreadMessages): ?>
                    <span class="nav-messages-notification-dot"></span>
                  <?php endif; ?>
                </span>
              </a>
            </li>
            <?php endif; ?>
            <li style="padding:0;">
              <?php if (isset($_SESSION['user_id'])): ?>
                <div class="hamburger-menu">
                  <div class="menu-header">
                    <img src="/resources/profilePics/<?= ($_SESSION['profile_photo'] ?? '0') ?>.png" alt="Foto de perfil" class="user-photo">
                    <span class="username"><?= htmlspecialchars($_SESSION['name'] ?? 'Usuário') ?></span>
                    <i class="fi fi-rr-menu-dots"></i>
                  </div>
                  <ul class="menu-options">
                    <li>
                      <a href="../pages/userprofile.php?username=<?= urlencode($_SESSION['username']) ?>">O meu perfil</a>
                    </li>
                    <li><a href="../pages/profile.php#edit">Editar Perfil</a></li>
                    <li><a href="../actions/action_logout.php">Log out</a></li>
                  </ul>
                </div>
              <?php else: ?>
                <a href="../pages/login.php">Login</a>
                <a href="../pages/signup.php">Sign Up</a>
              <?php endif; ?>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
<?php } ?>

<?php function drawFooter() { ?>
  </main>
  <footer>
    PetLink™ • Projeto LTW • T02G05 • 2025
  </footer>
</body>
</html>
<?php } ?>
