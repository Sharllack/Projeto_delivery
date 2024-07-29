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

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.quantidade');
    const valorTotalElement = document.querySelector('.valorTotal');
    let total = calcularTotal();

    // Função para calcular o valor total com base nos inputs de quantidade
    function calcularTotal() {
        let total = 0;
        inputs.forEach(function(input) {
            let quantidade = parseInt(input.value);
            let precoUnitario = parseFloat(input.getAttribute('data-preco'));
            let valorProduto = quantidade * precoUnitario;

            // Atualiza o texto do valor do produto
            let valorProdutoElement = input.closest('.qtd').querySelector('.valorProduto');
            valorProdutoElement.textContent = 'R$' + valorProduto.toFixed(2).replace('.', ',');

            total += valorProduto;
        });
        return total;
    }

    // Função para atualizar o valor total exibido na página
    function atualizarValorTotal() {
        valorTotalElement.textContent = 'R$' + total.toFixed(2).replace('.', ',');
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

        // Atualiza o valor total após a modificação da quantidade
        total = calcularTotal();
        atualizarValorTotal();
    }

    // Event listener para adicionar e subtrair quantidades
    document.addEventListener('click', function(event) {
        if (event.target.matches('.soma button')) {
            let input = event.target.closest('.qtd').querySelector('.quantidade');
            input.value = parseInt(input.value) + 1;

            // Atualiza o valor total após a modificação da quantidade
            toggleBtnRemoveVisibility(input);
        } else if (event.target.matches('.subtrai button')) {
            let input = event.target.closest('.qtd').querySelector('.quantidade');
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;

                // Atualiza o valor total após a modificação da quantidade
                toggleBtnRemoveVisibility(input);
            }
        }
    });

    // Chama a função para verificar visibilidade do botão inicialmente ao carregar a página
    inputs.forEach(input => {
        toggleBtnRemoveVisibility(input);
    });
});

//------------ DARK MODE -------------
const slidePref = localStorage.getItem('slide');
const main = document.querySelector('main');
const pratos = document.querySelectorAll('.pratos');

const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
    pratos.forEach(function(elemento) {
        elemento.style.backgroundColor = "black";
        elemento.style.boxShadow = "2px 2px 10px rgba(70, 70, 70)";
        elemento.style.border = "rgba(65, 65, 65)";
        elemento.style.transition = ".5s";
        elemento.style.color = "white";
      });
    main.style.backgroundColor = "#000000e8";
    main.style.transition = '.5s';
    main.style.color = "white";

    localStorage.setItem('darkMode', 'on');
    
} else {
    pratos.forEach(function(elemento) {
        elemento.style.backgroundColor = "";
        elemento.style.boxShadow = "2px 2px 10px rgba(0, 0, 0, 0.199)";
        elemento.style.border = "silver";
        elemento.style.transition = ".5s";
        elemento.style.color = "black";
      });
    main.style.backgroundColor = "";
    main.style.transition = '.5s';
    main.style.color = "black";
}





