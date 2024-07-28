function atualizarSituacao() {
    const saudacao = document.querySelector('.openOrClosed');
    const sit = document.querySelector('.sit');
    const carrinho = document.querySelector('.carrinho');
    const link = document.querySelectorAll('.linkPratos');
    const log = document.querySelector('.logBtn');
    const situ = document.querySelector('.situ').innerHTML;
    const hora = new Date().getHours();

    if (hora >= 11 && hora < 15 || situ === "Aberto") {
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

document.querySelector('#wpp').addEventListener('mouseenter', function(){
    const wppBallon = document.querySelector('.wppBallon');

    wppBallon.style.display = 'block';
});

document.querySelector('#wpp').addEventListener('mouseout', function(){
    const wppBallon = document.querySelector('.wppBallon');

    wppBallon.style.display = 'none';
});

atualizarSituacao(); // Chama a função imediatamente ao carregar a página
setInterval(atualizarSituacao, 60000); // Chama a função a cada minuto

setInterval(function() {
    location.reload();
}, 10000);