<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="stylesheets/search.css">
<script src="javascript/script.js"></script>

<?php function drawSearch() { 
  $search = $_GET['search'] ?? '';
  $location = $_GET['location'] ?? '';
?>
  <section class="pesquisa">
    <div class="input-wrapper">
      <i class="fi fi-rr-search"></i>
      <input type="text" placeholder="O que procuras?" value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="select-wrapper">
      <i class="fi fi-rr-marker"></i>
      <select id="location">
        <option value="" <?= $location === '' ? 'selected' : '' ?>>Todo o país</option>
        <option value="açores" <?= $location === 'açores' ? 'selected' : '' ?>>Açores</option>
        <option value="aveiro" <?= $location === 'aveiro' ? 'selected' : '' ?>>Aveiro</option>
        <option value="beja" <?= $location === 'beja' ? 'selected' : '' ?>>Beja</option>
        <option value="braga" <?= $location === 'braga' ? 'selected' : '' ?>>Braga</option>
        <option value="bragança" <?= $location === 'bragança' ? 'selected' : '' ?>>Bragança</option>
        <option value="castelo branco" <?= $location === 'castelo branco' ? 'selected' : '' ?>>Castelo Branco</option>
        <option value="coimbra" <?= $location === 'coimbra' ? 'selected' : '' ?>>Coimbra</option>
        <option value="evora" <?= $location === 'evora' ? 'selected' : '' ?>>Évora</option>
        <option value="faro" <?= $location === 'faro' ? 'selected' : '' ?>>Faro</option>
        <option value="guarda" <?= $location === 'guarda' ? 'selected' : '' ?>>Guarda</option>
        <option value="leiria" <?= $location === 'leiria' ? 'selected' : '' ?>>Leiria</option>
        <option value="lisboa" <?= $location === 'lisboa' ? 'selected' : '' ?>>Lisboa</option>
        <option value="madeira" <?= $location === 'madeira' ? 'selected' : '' ?>>Madeira</option>
        <option value="portalegre" <?= $location === 'portalegre' ? 'selected' : '' ?>>Portalegre</option>
        <option value="porto" <?= $location === 'porto' ? 'selected' : '' ?>>Porto</option>
        <option value="santarem" <?= $location === 'santarem' ? 'selected' : '' ?>>Santarém</option>
        <option value="setubal" <?= $location === 'setubal' ? 'selected' : '' ?>>Setúbal</option>
        <option value="viana do castelo" <?= $location === 'viana do castelo' ? 'selected' : '' ?>>Viana do Castelo</option>
        <option value="vila real" <?= $location === 'vila real' ? 'selected' : '' ?>>Vila Real</option>
        <option value="viseu" <?= $location === 'viseu' ? 'selected' : '' ?>>Viseu</option>
      </select>
    </div>

    <button type="button" onclick="pesquisar()">Pesquisar</button>
  </section>

  <section class="filtros">
    <h2>Filtros</h2>
    <div id="titles">
      <h3>Duração</h3>
      <h3>Animais</h3>
      <h3>Serviço</h3>
    </div>
    <label for="duracao"></label>
    <select>
      <option>Qualquer</option>
      <option>Hora</option>
      <option>Dia</option>
      <option>Semana</option>
      <option>Mês</option>
    </select>
    <label for="animais"></label>
    <select>
      <option>Todos</option>
      <option>Cães</option>
      <option>Gatos</option>
      <option>Pássaros</option>
      <option>Furões</option>
      <option>Coelhos</option>
      <option>Peixes</option>
      <option>Roedores</option>
      <option>Répteis</option>
    </select>
    <label for="serviço"></label>
    <select>
      <option>Todos</option>
      <option>Passeio</option>
      <option>Pet Sitting</option>
      <option>Tosquia</option>
      <option>Treino</option>
      <option>Alojamento</option>
      <option>Veterinário</option>
    </select>
    <a href="#" onclick="limparFiltros()">Limpar Filtros</a>
  </section>

  <section class="sort">
    <h3>Sort by:</h3>
    <label for="sort"></label>
    <select>
      <option>Recomendados</option>
      <option>Avaliações</option>
      <option>Preço: baixo para alto</option>
      <option>Preço: alto para baixo</option>
      <option>Mais recentes</option>
    </select>
  </section>

  <section class="results">
    <h3 id="resultado-contador"></h3>
  </section>
<?php } ?>
