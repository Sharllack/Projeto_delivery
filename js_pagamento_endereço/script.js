function atualizarSituacao() {
    const saudacao = document.querySelector('.openOrClosed');
    const hora = new Date().getHours();

    if (hora >= 11 && hora < 15) {
        saudacao.textContent = 'Aberto';
        saudacao.className = 'open';
    } else {
        saudacao.textContent = 'Fechado';
        saudacao.className = 'closed';
    }
}

atualizarSituacao(); // Chama a função imediatamente ao carregar a página
setInterval(atualizarSituacao, 60000); // Chama a função a cada minuto

document.querySelector('form').addEventListener('submit', function(event) {
    var opc = document.querySelector('#opcoes').value;
    var res = document.querySelector('.selectError');

    if(opc === '#') {
        res.style.display = 'block'
        event.preventDefault();
    }
})

document.querySelector('#opcoes').addEventListener('change', function(){
    const opc = document.querySelector('#opcoes').value;
    const ask = document.querySelector('.contTroco');

    if(opc === 'dinheiro'){
        ask.style.display = 'block';
    } else {
        ask.style.display = 'none';
    }
})

document.querySelectorAll('.askTroco').forEach(item => {
    item.addEventListener('change', function() {
        const askTr = document.querySelector('input[name="opcTroco"]:checked').value;
        const troco = document.querySelector('.troco');

        if (askTr === 'sim') {
            troco.style.display = 'block';
        } else {
            troco.style.display = 'none';
        }
    });
});