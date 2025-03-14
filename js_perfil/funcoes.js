document.querySelector('.deletePerfil').addEventListener('click', function() {
    const overlay = document.querySelector('.over');
    const opcoes = document.querySelector('.opcoes');

    // Mostra o overlay e a janela de opções
    overlay.style.display = 'block';
    opcoes.style.display = 'block';

    opcoes.classList.add('animated');
});

document.querySelectorAll('.closeWindow').forEach(closeElement => {
    closeElement.addEventListener('click', function() {
        document.querySelector('.over').style.display = 'none';
        document.querySelector('.opcoes').style.display = 'none';
    });
});

document.querySelector('.over').addEventListener('click', function(){
    document.querySelector('.over').style.display = 'none';
    document.querySelector('.opcoes').style.display = 'none';
})

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.loading').style.display = 'none';
})

document.querySelector('.edit').addEventListener('click', function() {
    document.querySelector('.loading').style.display = 'block';
})
