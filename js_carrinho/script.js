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
document.addEventListener('DOMContentLoaded', function() {
    let inputs = document.querySelectorAll('.quantidade');
    let valorTotalElement = document.querySelector('.valorTotal');
    let total = calcularTotal();

    // Função para calcular o valor total com base nos inputs de quantidade
    function calcularTotal() {
        let total = 0;
        inputs.forEach(function(input) {
            let quantidade = parseInt(input.value);
            let precoUnitario = parseFloat(input.getAttribute('data-preco'));
            total += quantidade * precoUnitario;
        });
        return total;
    }

    // Função para atualizar o valor total exibido na página
    function atualizarValorTotal() {
        valorTotalElement.textContent = 'R$' + total.toFixed(2).replace('.', ',');
    }

    // Adiciona event listener para delegação de eventos nos botões de adicionar e subtrair
    document.addEventListener('click', function(event) {
        if (event.target.matches('.soma button')) {
            let input = event.target.closest('.qtd').querySelector('.quantidade');
            input.value = parseInt(input.value) + 1;
        } else if (event.target.matches('.subtrai button')) {
            let input = event.target.closest('.qtd').querySelector('.quantidade');
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Atualiza o valor total após a modificação da quantidade
        total = calcularTotal();
        atualizarValorTotal();
    });

    // Calcula o valor total inicial ao carregar a página
    atualizarValorTotal();
});


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



