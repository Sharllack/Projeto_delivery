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

setInterval(function() {
    location.reload();
}, 10000);

//------------ DARK MODE -------------
const slidePref = localStorage.getItem('slide');
const main = document.querySelector('main');
const btn1 = document.querySelector('#btn1');
const btn2 = document.querySelector('#btn2');
const btn3 = document.querySelector('#btn3');

const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
    main.style.backgroundColor = "#000000e8";
    main.style.transition = '.5s';
    main.style.color = "white";
    btn1.style.backgroundColor = "white";
    btn2.style.backgroundColor = "white";
    btn3.style.backgroundColor = "white";

    localStorage.setItem('darkMode', 'on');
    
} else {
    main.style.backgroundColor = "";
    main.style.transition = '.5s';
    main.style.color = "";
    btn1.style.backgroundColor = "";
    btn2.style.backgroundColor = "";
    btn3.style.backgroundColor = "";
}