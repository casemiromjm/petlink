<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="stylesheets/search.css">

<?php function drawSearch() { ?>
  <section class="pesquisa">
    <img src="../resources/search.png" alt="search" id="iconSearch">
    <input type="text" placeholder="O que procuras?">
    <img src="../resources/marker.png" alt="marker" id="iconMarker">
    <select id="location">
      <option value="">Todo o país</option>
      <option value="açores">Açores</option>
      <option value="aveiro">Aveiro</option>
      <option value="beja">Beja</option>
      <option value="braga">Braga</option>
      <option value="bragança">Bragança</option>
      <option value="castelo branco">Castelo Branco</option>
      <option value="coimbra">Coimbra</option>
      <option value="evora">Évora</option>
      <option value="faro">Faro</option>
      <option value="guarda">Guarda</option>
      <option value="leiria">Leiria</option>
      <option value="lisboa">Lisboa</option>
      <option value="madeira">Madeira</option>
      <option value="portalegre">Portalegre</option>
      <option value="porto">Porto</option>
      <option value="santarem">Santarém</option>
      <option value="setubal">Setúbal</option>
      <option value="viana do castelo">Viana do Castelo</option>
      <option value="vila real">Vila Real</option>
      <option value="viseu">Viseu</option>
    </select>
    <button onclick="pesquisar()">Pesquisar</button>
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
      <option>Curto Prazo</option>
      <option>Médio Prazo</option>
      <option>Longo Prazo</option>
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
      <option>Banho</option>
      <option>Treino</option>
      <option>Tosquia</option>
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