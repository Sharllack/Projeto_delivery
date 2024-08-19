// Guarda os valores originais de total e taxa
let valorTotalOriginal = parseFloat(document.querySelector('.valorTotal').textContent.replace('R$', '').replace(',', '.'));
let valorTaxaOriginal = parseFloat(document.querySelector('.valorTaxa').textContent.replace('R$', '').replace(',', '.'));
const lograOri = document.querySelector('.logra').textContent;
const baiOri = document.querySelector('.bai').textContent;

function updateButtonValue() {
    const total = document.querySelector('.valorTotal').textContent;
    // Remove 'R$', substitui ',' por '.' para obter o valor numérico
    const totalValue = parseFloat(total.replace('R$', '').replace(',', '.'));
    document.querySelector('.button').value = totalValue;
}

// Função para atualizar a mensagem de erro de troco
function validarTroco() {
    const troco = document.querySelector('.troco');
    const trocoValue = parseFloat(troco.value.replace(',', '.')) || 0;
    const total = parseFloat(document.querySelector('.valorTotal').textContent.replace('R$', '').replace(',', '.')) || 0;
    const error = document.querySelector('.error');

    // Verifica se o valor do troco é menor do que o valor total
    if (trocoValue <= total) {
        toggleError(error, true);
        return false;
    } else {
        toggleError(error, false);
        return true;
    }
}

// Função auxiliar para exibir/ocultar mensagens de erro
function toggleError(element, show) {
    element.style.display = show ? 'block' : 'none';
}

// Atualize o evento de submit do formulário para incluir a validação de troco
document.querySelector('form').addEventListener('submit', function(event) {
    var opc = document.querySelector('#opcoes').value;
    var opcEntrega = document.querySelector('#opcEntrega').value;
    var selectError = document.querySelector('.selectError');
    var selectErro = document.querySelector('.selectErro');

    // Verificação da forma de pagamento
    if(opc === '#') {
        toggleError(selectError, true);
        document.querySelector('.loading').style.display = 'none';
        event.preventDefault();
        return;
    } else {
        toggleError(selectError, false);
    }

    // Verificação do tipo de entrega
    if(opcEntrega === '#') {
        toggleError(selectErro, true);
        document.querySelector('.loading').style.display = 'none';
        event.preventDefault();
        return;
    } else {
        toggleError(selectErro, false);
    }

    // Verificação do troco
    if (document.querySelector('input[name="opcTroco"]:checked')?.value === 'sim') {
        if (!validarTroco()) {
            event.preventDefault();
            document.querySelector('.loading').style.display = 'none';
            return;
        }
    }
});


// Evento de mudança da forma de pagamento
document.querySelector('#opcoes').addEventListener('change', function() {
    const opc = this.value;
    const ask = document.querySelector('.contTroco');
    const troco = document.querySelector('.troco');

    if(opc === 'dinheiro') {
        ask.style.display = 'block';
    } else {
        ask.style.display = 'none';
        troco.style.display = 'none';
    }

    // Atualiza o valor do botão após a mudança da forma de pagamento
    updateButtonValue();
});

// Evento de mudança do tipo de troco
document.querySelectorAll('.askTroco').forEach(item => {
    item.addEventListener('change', function() {
        const askTr = document.querySelector('input[name="opcTroco"]:checked').value;
        const troco = document.querySelector('.troco');

        if (askTr === 'sim') {
            troco.style.display = 'block';
        } else {
            troco.style.display = 'none';
        }

        // Atualiza o valor do botão após a mudança do tipo de troco
        updateButtonValue();
    });
});

// Evento de mudança do tipo de entrega
document.querySelector('#opcEntrega').addEventListener('change', function() {
    const opc = this.value;
    const taxa = document.querySelector('.valorTaxa');
    const total = document.querySelector('.valorTotal');
    const endTitle = document.querySelector('.enderecoTitle');
    const logra = document.querySelector('.logra');
    const bai = document.querySelector('.bai');
    const trocar = document.querySelector('.trocar');

    if (opc === 'retirada') {
        const valorTotal = parseFloat(total.textContent.replace('R$', '').replace(',', '.'));
        const valorTaxa = parseFloat(taxa.textContent.replace('R$', '').replace(',', '.'));
        const novoTotal = valorTotal - valorTaxa;

        total.textContent = 'R$ ' + novoTotal.toFixed(2).replace('.', ',');
        taxa.textContent = 'R$ 0,00';
        taxa.style.fontWeight = 'bold';
        endTitle.textContent = 'Endereço Para Retirada';
        logra.textContent = 'Rua João Ribeiro, 40';
        bai.textContent = 'Vila Centenário';
        trocar.style.display = 'none';
    } else {
        // Reverte para os valores originais
        total.textContent = 'R$ ' + valorTotalOriginal.toFixed(2).replace('.', ',');
        taxa.textContent = 'R$ ' + valorTaxaOriginal.toFixed(2).replace('.', ',');
        taxa.style.fontWeight = 'bold';
        endTitle.textContent = 'Endereço Para Entrega';
        logra.textContent = lograOri;
        bai.textContent = baiOri;
        trocar.style.display = 'block';
    }
    // Atualiza o valor do botão conforme o valor total atualizado
    updateButtonValue();
});

// Dark Mode Toggle
const toggle = document.querySelector('#toggle');
const main = document.querySelector('main');
const opcoes = document.querySelector('#opcoes');
const opcEntrega = document.querySelector('#opcEntrega');
const img = document.querySelector('.location');

toggle.addEventListener('change', function() {
    if (this.checked) {
        opcoes.style.backgroundColor = "black";
        opcoes.style.color = "white";
        opcEntrega.style.backgroundColor = "black";
        opcEntrega.style.color = "white";
        main.style.backgroundColor = "#000000e8";
        main.style.color = "white";
        img.src = './imagens/location_on_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png';
        localStorage.setItem('darkMode', 'on');
    } else {
        opcoes.style.backgroundColor = "";
        opcoes.style.color = "";
        opcEntrega.style.backgroundColor = "";
        opcEntrega.style.color = "";
        main.style.backgroundColor = "";
        main.style.color = "black";
        img.src = './imagens/location_on_24dp_00000_FILL0_wght400_GRAD0_opsz24.png';
        localStorage.setItem('darkMode', 'off');
    }
});

// Aplicar preferências de Dark Mode
const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
    opcoes.style.backgroundColor = "black";
    opcoes.style.color = "white";
    opcEntrega.style.backgroundColor = "black";
    opcEntrega.style.color = "white";
    main.style.backgroundColor = "#000000e8";
    main.style.color = "white";
    img.src = './imagens/location_on_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png';
    toggle.checked = true;
}

document.querySelector('.button').addEventListener('click', () =>{
    document.querySelector('.loading').style.display = 'block';
})
