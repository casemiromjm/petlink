document.addEventListener('DOMContentLoaded', () => {

//Mensagem de sucesso quando se cria um anúncio com 2 segundos
x
  const successMessage = document.getElementById('success-message');
  if (successMessage) {
    setTimeout(() => {
      successMessage.style.display = 'none';
    }, 2000);
  }
});
