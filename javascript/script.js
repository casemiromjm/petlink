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
