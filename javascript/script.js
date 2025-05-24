document.addEventListener('DOMContentLoaded', () => {

  const successMessage = document.getElementById('success-message');
  if (successMessage) {
    setTimeout(() => {
      successMessage.style.display = 'none';
    }, 2000);
  }

  function fetchResults(page = 1) {
    const search = document.getElementById('search-input') ? document.getElementById('search-input').value : '';
    const location = document.getElementById('location') ? document.getElementById('location').value : '';
    const duracao = document.getElementById('duracao') ? document.getElementById('duracao').value : '';
    const animal = document.getElementById('animal') ? document.getElementById('animal').value : '';
    const servico = document.getElementById('servico') ? document.getElementById('servico').value : '';
    const sort = document.getElementById('sort') ? document.getElementById('sort').value : 'recentes';

    const duracaoParam = (duracao === '' || duracao === 'Qualquer') ? '' : duracao; // 'Qualquer' might still be a value from a static select
    const animalParam = (animal === '') ? '' : animal; // 'Todos' is no longer a value, empty string means no filter
    const servicoParam = (servico === '') ? '' : servico; // 'Todos' is no longer a value, empty string means no filter

    const params = new URLSearchParams({
      search: search,
      location: location,
      duracao: duracaoParam,
      animal: animalParam,
      servico: servicoParam,
      sort: sort,
      page: page,
      ajax: 1
    });

    const currentUrlParams = new URLSearchParams(window.location.search);
    currentUrlParams.set('page', String(page));
    currentUrlParams.set('search', search);
    currentUrlParams.set('location', location);
    currentUrlParams.set('duracao', duracaoParam);
    currentUrlParams.set('animal', animalParam);
    currentUrlParams.set('servico', servicoParam);
    currentUrlParams.set('sort', sort);

    for (let pair of currentUrlParams.entries()) {
      if (pair[1] === '' || (pair[0] === 'page' && pair[1] === '1')) {
        currentUrlParams.delete(pair[0]);
      }
    }
    history.pushState(null, '', `?${currentUrlParams.toString()}`);


    fetch(`index.php?${params.toString()}`)
      .then(response => response.text())
      .then(html => {
        document.getElementById('search-results').innerHTML = html;
      })
      .catch(error => console.error('Erro ao buscar resultados:', error));
  }

  const searchInput = document.getElementById('search-input');
  const locationSelect = document.getElementById('location');
  const duracaoSelect = document.getElementById('duracao');
  const animalSelect = document.getElementById('animal');
  const servicoSelect = document.getElementById('servico');
  const sortSelect = document.getElementById('sort');

  if (searchInput) searchInput.addEventListener('input', () => fetchResults(1));
  if (locationSelect) locationSelect.addEventListener('change', () => fetchResults(1));
  if (duracaoSelect) duracaoSelect.addEventListener('change', () => fetchResults(1));
  if (animalSelect) animalSelect.addEventListener('change', () => fetchResults(1));
  if (servicoSelect) servicoSelect.addEventListener('change', () => fetchResults(1));
  if (sortSelect) sortSelect.addEventListener('change', () => fetchResults(1));

  document.getElementById('search-results').addEventListener('click', function (event) {
    const clickedLink = event.target.closest('.pagination a');

    if (clickedLink) {
      event.preventDefault();

      const href = clickedLink.getAttribute('href');
      const urlParams = new URLSearchParams(href.split('?')[1]);
      const newPage = urlParams.get('page');

      if (newPage) {
        fetchResults(parseInt(newPage));
      }
    }
  });

  const initialPage = parseInt(new URLSearchParams(window.location.search).get('page') || '1');
  fetchResults(initialPage);

  function limparFiltros() {
    if (document.getElementById('duracao')) document.getElementById('duracao').value = 'Qualquer';
    if (document.getElementById('animal')) document.getElementById('animal').value = '';
    if (document.getElementById('servico')) document.getElementById('servico').value = '';
    if (document.getElementById('search-input')) document.getElementById('search-input').value = '';
    if (document.getElementById('location')) document.getElementById('location').value = '';
    if (document.getElementById('sort')) document.getElementById('sort').value = 'recentes';

    fetchResults(1);
  }

  const clearFiltersButton = document.getElementById('clear-filters-button');
  if (clearFiltersButton) {
    clearFiltersButton.addEventListener('click', limparFiltros);
  }

});
