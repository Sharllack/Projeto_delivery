document.querySelector('form').addEventListener('submit', function(event) {
    var opc = document.querySelector('#opcoes').value;
    var res = document.querySelector('.selectError');

    if(opc === '#') {
        res.style.display = 'block'
        event.preventDefault();
    }
})

document.querySelector('form').addEventListener('submit', function(event) {
    var opc = document.querySelector('#opcEntrega').value;
    var res = document.querySelector('.selectErro');

    if(opc === '#') {
        res.style.display = 'block'
        event.preventDefault();
    }
})

document.querySelector('#opcoes').addEventListener('change', function(){
    const opc = document.querySelector('#opcoes').value;
    const ask = document.querySelector('.contTroco');
    const troco = document.querySelector('.troco');

    if(opc === 'dinheiro'){
        ask.style.display = 'block';
    } else {
        ask.style.display = 'none';
        troco.style.display = 'none';
    }
})

document.querySelectorAll('.askTroco').forEach(item => {
    item.addEventListener('change', function() {
        const askTr = document.querySelector('input[name="opcTroco"]:checked').value;
        const troco = document.querySelector('.troco');

        if (askTr === 'sim') {
            troco.style.display = 'block';
            document.querySelector('form').addEventListener('submit', function(event) {
                const troco = parseFloat(document.querySelector('.troco').value) || 0;
                const total = parseFloat(document.querySelector(".valorTotal").textContent.replace('R$', '').replace(',', '.')) || 0;
                const error = document.querySelector(".error");
            
                if (troco <= total) {
                    error.style.display = "block";
                    event.preventDefault();
            
                } else {
            
                }
            });
        } else {
            troco.style.display = 'none';
        }
    });
});

// Guarda os valores originais de total e taxa
let valorTotalOriginal = document.querySelector('.valorTotal').textContent;
let valorTaxaOriginal = document.querySelector('.valorTaxa').textContent;
const lograOri = document.querySelector('.logra').textContent;
const baiOri = document.querySelector('.bai').textContent;

document.querySelector('#opcEntrega').addEventListener('change', function() {
    const opc = document.querySelector('#opcEntrega').value;
    const taxa = document.querySelector('.valorTaxa');
    const total = document.querySelector('.valorTotal');
    const endTitle = document.querySelector('.enderecoTitle');
    const logra = document.querySelector('.logra');
    const bai = document.querySelector('.bai');
    const trocar = document.querySelector('.trocar');

    if (opc === 'retirada') {
        // Remove o cifrão e converte para número
        let valorTotal = parseFloat(total.textContent.replace('R$', '').replace(',', '.'));
        let valorTaxa = parseFloat(taxa.textContent.replace('R$', '').replace(',', '.'));

        let novoTotal = valorTotal - valorTaxa;

        total.textContent = 'R$' + novoTotal.toFixed(2).replace('.', ','); // Formata o novo total
        taxa.textContent = 'R$0,00';
        taxa.style.fontWeight = 'bold';
        endTitle.textContent = 'Endereço Para Retirada';
        logra.textContent = 'Rua João Ribeiro, 40';
        logra.style.fontWeight = 'bold';
        bai.textContent = 'Vila Centenário';
        trocar.style.display = 'none';
    } else {
        // Reverte para os valores originais
        total.textContent = valorTotalOriginal;
        taxa.textContent = valorTaxaOriginal;
        taxa.style.fontWeight = 'bold';
        endTitle.textContent = 'Endereço Para Entrega';
        logra.textContent = lograOri;
        logra.style.fontWeight = 'bold';
        bai.textContent = baiOri;
        trocar.style.display = 'block';
    }
});

//------------ DARK MODE -------------
const toggle = document.querySelector('#toggle');
const main = document.querySelector('main');
const opcoes = document.querySelector('#opcoes');
const opcEntrega = document.querySelector('#opcEntrega');
const img = document.querySelector('.location');

toggle.addEventListener('change', function() {
  if (this.checked) {
      console.log('Toggle ON');
      opcoes.style.backgroundColor = "black";
      opcoes.style.color = "white";
      opcEntrega.style.backgroundColor = "black";
      opcEntrega.style.color = "white";
      main.style.backgroundColor = "#000000e8";
      main.style.transition = '.5s';
      main.style.color = "white";
      img.src = './imagens/location_on_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png';

      localStorage.setItem('darkMode', 'on');
      
  } else {
      console.log('Toggle OFF');
      opcoes.style.backgroundColor = "";
      opcoes.style.color = "";
      opcEntrega.style.backgroundColor = "";
      opcEntrega.style.color = "";
      main.style.backgroundColor = "";
      main.style.transition = '.5s';
      main.style.color = "black";
      img.src = './imagens/location_on_24dp_00000_FILL0_wght400_GRAD0_opsz24.png';   

      localStorage.setItem('darkMode', 'off');

};
});

const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
    opcoes.style.backgroundColor = "black";
    opcoes.style.color = "white";
    opcEntrega.style.backgroundColor = "black";
    opcEntrega.style.color = "white";
    main.style.backgroundColor = "#000000e8";
    main.style.transition = '.5s';
    main.style.color = "white";
    img.src = './imagens/location_on_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png';

    localStorage.setItem('darkMode', 'on');

    toggle.checked = true;
    
} else {
    opcoes.style.backgroundColor = "";
    opcoes.style.color = "";
    opcEntrega.style.backgroundColor = "";
    opcEntrega.style.color = "";
    main.style.backgroundColor = "";
    main.style.transition = '.5s';
    main.style.color = "black";
    img.src = './imagens/location_on_24dp_00000_FILL0_wght400_GRAD0_opsz24.png';
}