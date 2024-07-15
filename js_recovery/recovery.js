document.querySelector('#cSen').addEventListener('keyup', function() {
    let pass = document.querySelector('#sen').value;
    let cPass = document.querySelector('#cSen').value;

    if(pass != cPass) {
        document.querySelector('.resCsen').style.display = 'block';
    } else {
        document.querySelector('.resCsen').style.display = 'none';
    }
})

document.querySelector('#sen').addEventListener('keyup', function() {
    let pass = document.querySelector('#sen').value;

    if(pass.length < 8) {
        document.querySelector('.resSen').style.display = 'block';
    } else {
        document.querySelector('.resSen').style.display = 'none';
    }
})

document.querySelector('form').addEventListener('submit', function(event) {
    let pass = document.querySelector('#sen').value;
    let cPass = document.querySelector('#cSen').value;
    let res = document.querySelector('.resposta');
    let sPass = (pass.length >= 8);
    let passwordsMatch = (pass === cPass);

    if(!passwordsMatch || !sPass) {
        event.preventDefault(); // Impede o envio do formulário
    } else {
        res.textContent = 'Senha Atualizada!';
    }
});