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
      <input type="text" id="search-input" placeholder="O que procuras?" value="<?= htmlspecialchars($search) ?>">
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

  <section class="filtros" style="display: flex; align-items: flex-end; justify-content: space-between;">
    <div class="filtros-left" style="display: flex; gap: 2em;">
      <div>
        <h3>Duração</h3>
        <select id="duracao" name="duracao">
          <option value="">Qualquer</option>
          <option value="hora">Hora</option>
          <option value="dia">Dia</option>
          <option value="semana">Semana</option>
          <option value="mês">Mês</option>
        </select>
      </div>
      <div>
        <h3>Animais</h3>
        <select id="animal" name="animal">
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
      </div>
      <div>
        <h3>Serviço</h3>
        <select id="servico" name="servico">
          <option>Todos</option>
          <option>Passeio</option>
          <option>Pet Sitting</option>
          <option>Tosquia</option>
          <option>Treino</option>
          <option>Alojamento</option>
          <option>Veterinário</option>
        </select>
      </div>
      <a href="#" onclick="limparFiltros()">Limpar Filtros</a>
    </div>
    <div class="sort">
      <h3 style="display:inline; margin-right: 0.5em;">Ordenar por:</h3>
      <select id="sort" onchange="fetchResults()">
        <option value="recentes">Mais recentes</option>
        <option value="avaliacoes">Avaliações</option>
        <option value="preco_asc">Preço: baixo para alto</option>
        <option value="preco_desc">Preço: alto para baixo</option>
      </select>
    </div>
  </section>

  <section class="results">
    <h3 id="resultado-contador"></h3>
    <div id="search-results"></div>
  </section>
<?php } ?>
