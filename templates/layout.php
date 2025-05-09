<?php declare(strict_types = 1); ?>

<?php function drawHeader() { ?>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiteName Here</title>
    <link rel="stylesheet" href="stylesheets/style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  </head>
  <body>

    <header>
      <div class="logo">
        <img src="../resources/logo.png" alt="logo">
        <h1>Nome do site</h1>
      </div>
      <nav>
        <ul>
          <div class="serviço">
            <li><a href="../index.php">Contratar Serviço</a></li>
            <li><a href="../pages/adCreate.php">Anunciar Serviço</a></li>
          </div>
          <div class="messages">
            <li><a href="messages.html">Mensagens
                <i class="fi fi-rr-envelope"></i>
              </a></li>
          </div>
          <div class="profile-icon">
            <li><a href="../pages/profile.php">O meu perfil
                <i class="fi fi-rr-circle-user"></i></a></li>
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
