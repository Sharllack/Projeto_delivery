document.querySelector('#cSenha').addEventListener('keyup', function() {
    const pass = document.querySelector('#senha').value;
    const cPass = document.querySelector('#cSenha');

    if(pass != cPass.value) {
        cPass.style.border = '1px solid red';
        document.querySelector('.resPass').textContent = 'As senhas não são iguais!';
        document.querySelector('.resPass').style.color = 'red';
        document.querySelector('.resPass').style.textShadow = '2px 2px 8px rgba(255, 255, 255, 0.342)';
        document.querySelector('.resPass').style.fontWeight = 'bold';
        document.querySelector('.resPass').style.fontSize = '.9em';
    } else {
        document.querySelector('.resPass').textContent = '';
        document.querySelector('.resPass').style.color = '';
        cPass.style.border = '';
    }
})

document.querySelector('#senha').addEventListener('keyup', function() {
    let pass = document.querySelector('#senha');

    if(pass.value.length < 8) {
        pass.style.border = '1px solid red';
        document.querySelector('.resPas').textContent = 'A senha deve ter no mínimo 8 caracteres!';
        document.querySelector('.resPas').style.color = 'red';
        document.querySelector('.resPas').style.textShadow = '2px 2px 8px rgba(255, 255, 255, 0.342)';
        document.querySelector('.resPas').style.fontWeight = 'bold';
        document.querySelector('.resPas').style.fontSize = '.8em';
    } else {
        document.querySelector('.resPas').textContent = '';
        document.querySelector('.resPas').style.color = '';
        pass.style.border = '';
    }
})

document.querySelector('form').addEventListener('submit', function(event) {
    let pass = document.querySelector('#senha').value;
    let cPass = document.querySelector('#cSenha').value;
    let res = document.querySelector('.resposta');
    let sPass = (pass.length >= 8);
    let passwordsMatch = (pass === cPass);

    if(!passwordsMatch || !sPass) {
        event.preventDefault(); // Impede o envio do formulário
    } else {
        res.textContent = 'Cadastro Efetuado!';
    }
});
