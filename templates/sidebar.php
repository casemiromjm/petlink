<?php declare(strict_types = 1); ?>

<?php function drawNavbar() { 
  $currentPage = basename($_SERVER['PHP_SELF']);
?>
  <nav class="side-nav">
    <ul>
      <li class="<?= $currentPage === 'profile.php' ? 'active' : '' ?>">
        <a href="../pages/profile.php">Dados Pessoais</a>
      </li>
      <li class="<?= $currentPage === 'animals.php' ? 'active' : '' ?>">
        <a href="../pages/animals.php">Animais</a>
      </li>
      <li class="<?= $currentPage === 'config.php' ? 'active' : '' ?>">
        <a href="../pages/config.php">Configurações</a>
      </li>
    </ul>
  </nav>
<?php } ?>