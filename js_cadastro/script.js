document.querySelector('#cPass').addEventListener('keyup', function() {
    let pass = document.querySelector('#pass').value;
    let cPass = document.querySelector('#cPass').value;

    if(pass != cPass) {
        document.querySelector('.resPass').textContent = 'As senhas não são iguais!';
        document.querySelector('.resPass').style.color = 'red';
        document.querySelector('.resPass').style.textShadow = '2px 2px 8px rgba(255, 255, 255, 0.342)';
        document.querySelector('.resPass').style.fontWeight = 'bold';
        document.querySelector('.resPass').style.fontSize = '.9em';
    } else {
        document.querySelector('.resPass').textContent = '';
        document.querySelector('.resPass').style.color = '';
    }
})

document.querySelector('#pass').addEventListener('keyup', function() {
    let pass = document.querySelector('#pass').value;

    if(pass.length < 8) {
        document.querySelector('.resPas').textContent = 'A senha deve ter no mínimo 8 caracteres!';
        document.querySelector('.resPas').style.color = 'red';
        document.querySelector('.resPas').style.textShadow = '2px 2px 8px rgba(255, 255, 255, 0.342)';
        document.querySelector('.resPas').style.fontWeight = 'bold';
        document.querySelector('.resPas').style.fontSize = '.8em';
    } else {
        document.querySelector('.resPas').textContent = '';
        document.querySelector('.resPas').style.color = '';
    }
})

document.querySelector('form').addEventListener('submit', function(event) {
    let pass = document.querySelector('#pass').value;
    let cPass = document.querySelector('#cPass').value;
    let sPass = (pass.length <= 8);
    let passwordsMatch = (pass === cPass);

    if(!passwordsMatch || !sPass) {
        event.preventDefault(); // Impede o envio do formulário
    } else {

    }
});
