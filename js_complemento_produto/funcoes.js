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


//------------ DARK MODE -------------
const slidePref = localStorage.getItem('slide');
const main = document.querySelector('main');

const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
      main.style.backgroundColor = "#000000e8";
      main.style.transition = '.5s';
      main.style.color = "white";

      localStorage.setItem('darkMode', 'on');

    toggle.checked = true;
    
} else {
      main.style.backgroundColor = "";
      main.style.transition = '.5s';
      main.style.color = "black";
}