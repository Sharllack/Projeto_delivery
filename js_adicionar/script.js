document.querySelector('#imagem').addEventListener('change', function(){ 
    document.querySelector('.span1').textContent = this.files[0].name;
});