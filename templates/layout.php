<?php declare(strict_types = 1); ?>
<?php session_start(); // Ensure session is started ?>

<?php function drawHeader() { ?>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiteName Here</title>
    <link rel="preload" href="stylesheets/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="stylesheets/style.css"></noscript>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="../resources/logo.png" alt="logo">
        <h1><a href="../index.php">Nome do site</a></h1>
      </div>
      <nav>
        <ul>
          <div class="serviço">
            <li><a href="../index.php">Contratar Serviço</a></li>
            <li><a href="../pages/adCreate.php">Anunciar Serviço</a></li>
          </div>
          <div class="messages">
            <li><a href="../pages/mensagens.php">Mensagens
                <i class="fi fi-rr-envelope"></i>
              </a></li>
          </div>
          <div class="profile-icon">
            <?php if (isset($_SESSION['user_id'])): ?>
              <div class="hamburger-menu">
                <div class="menu-header">
                  <img src="<?= htmlspecialchars(str_replace('./', '../', $_SESSION['profile_photo'] ?? '../resources/default_profile.png')) ?>" alt="Foto de perfil" class="user-photo">
                  <span class="username"><?= htmlspecialchars($_SESSION['name'] ?? 'Usuário') ?></span>
                  <i class="fi fi-rr-menu-dots"></i>
                </div>
                <ul class="menu-options">
                  <li><a href="../pages/profile.php">O meu perfil</a></li>
                  <li><a href="../pages/profile.php#edit">Editar Perfil</a></li>
                  <li><a href="../actions/action_logout.php">Log out</a></li>
                </ul>
              </div>
            <?php else: ?>
              <li><a href="../pages/login.php">Login</a></li>
              <li><a href="../pages/signup.php">Sign Up</a></li>
            <?php endif; ?>
          </div>
        </ul>
      </nav>
    </header>
    <main>
<?php } ?>

<?php function drawFooter() { ?>
    </main>
    <footer>
      Projeto LTW . Turma 2 Grupo TBA . 2024/2025
    </footer>
  </body>
<?php } ?>
