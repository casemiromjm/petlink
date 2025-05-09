// simulação de dados
let numeroDeAnuncios = 1200; // para testar

function atualizarContador() {
    const contador = document.getElementById('resultado-contador');

    if (numeroDeAnuncios > 1000) {
        contador.textContent = "Encontramos mais de 1000 anúncios";
    } else {
        contador.textContent = `Encontramos ${numeroDeAnuncios} anúncio${numeroDeAnuncios === 1 ? '' : 's'}`;
    }
}

function pesquisar() {
    numeroDeAnuncios = Math.floor(Math.random() * 1500);
    atualizarContador();
}

function limparFiltros() {
    const filtros = document.querySelectorAll('.filtros select');
    filtros.forEach(filtro => filtro.selectedIndex = 0);
    atualizarContador();
}

atualizarContador();
