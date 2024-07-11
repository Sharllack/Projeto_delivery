function atualizarSituacao() {
    const saudacao = document.querySelector('.openOrClosed');
    const sit = document.querySelector('.sit');
    const carrinho = document.querySelector('.carrinho');
    const hora = new Date().getHours();

    if (hora >= 11 && hora < 24) {
        saudacao.textContent = 'Aberto';
        saudacao.className = 'open';
        sit.textContent = 'Aberto';
        sit.className = 'open';
    } else {
        saudacao.textContent = 'Fechado';
        saudacao.className = 'closed';
        sit.textContent = 'Fechado';
        sit.className = 'closed';

        carrinho.href = '#';
    }
}

atualizarSituacao(); // Chama a função imediatamente ao carregar a página
setInterval(atualizarSituacao, 60000); // Chama a função a cada minuto

setInterval(function() {
    location.reload();
}, 10000);