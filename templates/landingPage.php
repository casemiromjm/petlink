<?php 
declare(strict_types = 1);
?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawLandingPage() { ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.body.classList.add('landing-page');
    });
  </script>
  <section class="landing-hero">
    <img src="../resources/logo.png" alt="PetLink Logo" class="landing-logo">
    <h1>Bem-vindo ao PetLink</h1>
    <p class="landing-subtitle">
      A plataforma onde clientes e freelancers se conectam para cuidar dos seus animais de estimação.
    </p>
    <a href="signup.php" class="profile-link landing-btn">Criar Conta</a>
    <a href="login.php" class="profile-link landing-btn">Entrar</a>
  </section>
  <section class="landing-features">
    <div class="landing-feature-card">
      <h2>Para Clientes</h2>
      <ul>
        <li>Encontre serviços para os seus animais</li>
        <li>Filtre por localização, preço e avaliações</li>
        <li>Contrate freelancers de confiança</li>
      </ul>
    </div>
    <div class="landing-feature-card">
      <h2>Para Freelancers</h2>
      <ul>
        <li>Anuncie os seus serviços</li>
        <li>Receba avaliações</li>
        <li>Gestão fácil dos seus anúncios</li>
      </ul>
    </div>
  </section>
<?php } ?>