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

// Funções adicionar e subtrair (mantenha o código existente)
function adicionar(button) {
    let parentDiv = button.closest('.qtd');
    let input = parentDiv.querySelector('input[type="number"]');
    let novoValor = parseInt(input.value) + 1;
    input.value = novoValor;
    toggleBtnRemoveVisibility(input); // Chama a função para verificar visibilidade do botão
}

function subtrair(button) {
    let parentDiv = button.closest('.qtd');
    let input = parentDiv.querySelector('input[type="number"]');
    let novoValor = parseInt(input.value) - 1;
    if (novoValor >= 0) {
        input.value = novoValor;
        toggleBtnRemoveVisibility(input); // Chama a função para verificar visibilidade do botão
    }
}

// Função para verificar e mostrar/ocultar botão btnRemove
function toggleBtnRemoveVisibility(input) {
    let parentDiv = input.closest('.qtd');
    let btnRemove = parentDiv.querySelector('.btnRemove');
    if (parseInt(input.value) === 0) {
        btnRemove.style.display = 'inline-block'; // Mostra o botão se o valor for 0
    } else {
        btnRemove.style.display = 'none'; // Oculta o botão caso contrário
    }
}

// Chama a função para verificar visibilidade do botão inicialmente ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    let inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        toggleBtnRemoveVisibility(input);
    });
});


document.querySelector('form').addEventListener('submit', function(event) {
    let input = document.querySelector(".qtd");

    if(input.value < 1) {
        event.preventDefault();
    }
})

