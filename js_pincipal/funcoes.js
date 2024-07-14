function atualizarSituacao() {
    const saudacao = document.querySelector('.openOrClosed');
    const sit = document.querySelector('.sit');
    const carrinho = document.querySelector('.carrinho');
    const link = document.querySelectorAll('.linkPratos');
    const log = document.querySelector('.logBtn')
    const hora = new Date().getHours();

    if (hora >= 11 && hora < 15) {
        saudacao.textContent = 'Aberto';
        saudacao.className = 'open';
        sit.textContent = 'Aberto';
        sit.className = 'open';
    } else {
        saudacao.textContent = 'Fechado';
        saudacao.className = 'closed';
        sit.textContent = 'Fechado';
        sit.className = 'closed';

        link.forEach(function(link){
            link.href = "#";
        })
        carrinho.href = '#';
        log.href = '#';
    }
}

atualizarSituacao(); // Chama a função imediatamente ao carregar a página
setInterval(atualizarSituacao, 60000); // Chama a função a cada minuto

setInterval(function() {
    location.reload();
}, 10000);