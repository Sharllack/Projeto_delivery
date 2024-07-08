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

function adicionar() {
    let soma = document.querySelector("#qtd");
    let span = document.querySelector(".span");
    let novoValor = parseInt(soma.value);

    novoValor = novoValor + 1;

    soma.value = novoValor;
    span.textContent = novoValor;
}

function subtrair() {
    let soma = document.querySelector("#qtd");
    let span = document.querySelector(".span");
    let novoValor = parseInt(soma.value);

    if(soma.value > 0) {
        novoValor = novoValor - 1;
    }

    soma.value = novoValor;
    span.textContent = novoValor;
}

document.querySelector('form').addEventListener('submit', function(event) {
    let soma = document.querySelector("#qtd");

    if(soma.value < 1) {
        event.preventDefault();
    }
})