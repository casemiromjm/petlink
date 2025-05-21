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
  // Add more filters if needed
  let params = new URLSearchParams(window.location.search);
  params.set('search', search);
  params.set('location', location);
  window.location.search = params.toString();
}

document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search-input');
  const locationSelect = document.getElementById('location');
  const resultsDiv = document.getElementById('search-results');

  function fetchResults() {
    const search = searchInput.value;
    const location = locationSelect.value;
    fetch(`index.php?search=${encodeURIComponent(search)}&location=${encodeURIComponent(location)}&ajax=1`)
      .then(response => response.text())
      .then(html => {
        resultsDiv.innerHTML = html;
      });
  }

  if (searchInput && resultsDiv) {
    searchInput.addEventListener('input', fetchResults);
    locationSelect.addEventListener('change', fetchResults);
    fetchResults(); // <-- Add this line to trigger filtering on page load
  }
});
