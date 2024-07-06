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
    let novoValor = parseInt(soma.value);

    novoValor = novoValor + 1;

    soma.value = novoValor;
}

function subtrair() {
    let soma = document.querySelector("#qtd");
    let novoValor = parseInt(soma.value);

    novoValor = novoValor - 1;

    soma.value = novoValor;
}