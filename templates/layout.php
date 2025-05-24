<?php declare(strict_types = 1); ?>
<?php
session_start();


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
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
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../resources/logo.png">
    <title>PetLink</title>
    <link rel="preload" href="/stylesheets/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/stylesheets/style.css"></noscript>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="../resources/logo.png" alt="logo">
        <h1><a href="../index.php">PetLink</a></h1>
      </div>
      <nav style="width:100%;">
        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
          <div class="nav-center" style="flex:1; display: flex; justify-content: center;">
            <ul style="display: flex; align-items: center; gap: 0;">
              <li><a href="../index.php">Contratar Serviço</a></li>
              <li style="pointer-events:none; color:#fff; font-weight:bold; padding: 0 1em;">|</li>
              <li><a href="../pages/adCreate.php">Anunciar Serviço</a></li>
              <?php if ($isAdmin): ?>
              <li style="pointer-events:none; color:#fff; font-weight:bold; padding: 0 1em;">|</li>
                      <li><a href="/pages/admin.php">Painel de Admin</a></li>
                  <?php endif; ?>
            </ul>
          </div>
          <div class="nav-right" style="display: flex; align-items: center; gap: 1.5em;">
            <ul style="display: flex; align-items: center; margin: 0;">
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
                      <img src="<?= htmlspecialchars(str_replace('./', '../', $_SESSION['profile_photo'] ?? '/resources/profilePics/0.png')) ?>" alt="Foto de perfil" class="user-photo">
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
<?php
}
function drawFooter() { ?>
    </main>
    <footer>
      PetLink™ • Projeto LTW • T02G05 • 2025
    </footer>
  </body>
<?php } ?>
