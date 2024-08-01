document.querySelector('.deletePerfil').addEventListener('click', function(){
    const body = document.querySelector('body');
    const opcoes = document.querySelector('.opcoes');

    // Adiciona a classe para iniciar a animação
    opcoes.style.display = "block";
    opcoes.classList.add('animated');
    body.style.filter = "brightness(10%)";
})