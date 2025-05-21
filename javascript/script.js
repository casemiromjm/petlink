document.addEventListener('DOMContentLoaded', () => {

//Mensagem de sucesso quando se cria um anúncio com 2 segundos

  const successMessage = document.getElementById('success-message');
  if (successMessage) {
    setTimeout(() => {
      successMessage.style.display = 'none';
    }, 2000);
  }
});

// Pesquisa de anúncios (search bar)
function pesquisar() {
  const search = document.querySelector('.input-wrapper input').value;
  const location = document.getElementById('location').value;
  const duracao = document.getElementById('duracao').value;
  const animal = document.getElementById('animal').value;
  const servico = document.getElementById('servico').value;

  let params = new URLSearchParams(window.location.search);
  params.set('search', search);
  params.set('location', location);
  params.set('duracao', duracao);
  params.set('animal', animal);
  params.set('servico', servico);

  window.location.search = params.toString();
}

function fetchResults() {
  const search = document.getElementById('search-input').value;
  const location = document.getElementById('location').value;
  const duracao = document.getElementById('duracao').value;
  const animal = document.getElementById('animal').value;
  const servico = document.getElementById('servico').value;
  const sort = document.getElementById('sort') ? document.getElementById('sort').value : 'recomendados';

  const duracaoParam = (duracao === 'Qualquer') ? '' : duracao;
  const animalParam = (animal === 'Todos') ? '' : animal;
  const servicoParam = (servico === 'Todos') ? '' : servico;

  const params = new URLSearchParams({
    search,
    location,
    duracao: duracaoParam,
    animal: animalParam,
    servico: servicoParam,
    sort,
    ajax: 1
  });

  fetch(`index.php?${params.toString()}`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('search-results').innerHTML = html;
    });
}

document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search-input');
  const locationSelect = document.getElementById('location');
  const duracaoSelect = document.getElementById('duracao');
  const animalSelect = document.getElementById('animal');
  const servicoSelect = document.getElementById('servico');

  if (searchInput) searchInput.addEventListener('input', fetchResults);
  if (locationSelect) locationSelect.addEventListener('change', fetchResults);
  if (duracaoSelect) duracaoSelect.addEventListener('change', fetchResults);
  if (animalSelect) animalSelect.addEventListener('change', fetchResults);
  if (servicoSelect) servicoSelect.addEventListener('change', fetchResults);

  fetchResults();
});

function limparFiltros() {
  document.getElementById('duracao').value = 'Qualquer';
  document.getElementById('animal').value = 'Todos';
  document.getElementById('servico').value = 'Todos';
  fetchResults();
}
