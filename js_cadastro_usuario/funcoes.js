document.querySelector("#user").addEventListener("keyup", function () {
  let pass = document.querySelector("#user");
  const maxLength = 6;

  // Limitar o comprimento máximo
  if (pass.value.length > maxLength) {
    pass.value = pass.value.slice(0, maxLength);
  }

  // Verificar se o comprimento mínimo é atingido
  if (pass.value.length < maxLength) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector(".resUser").textContent =
      "O usuário deve ter 6 caracteres!";
    document.querySelector(".resUser").style.color = "red";
    document.querySelector(".resUser").style.fontWeight = "bold";
    document.querySelector(".resUser").style.fontSize = ".8em";
  } else {
    // Resetar estilos quando a senha for válida
    document.querySelector(".resUser").textContent = "";
    pass.style.border = "";
    pass.style.filter = "";
  }
});

document.querySelector("#senha").addEventListener("keyup", function () {
  let pass = document.querySelector("#senha");
  const maxLength = 8;

  // Limitar o comprimento máximo
  if (pass.value.length > maxLength) {
    pass.value = pass.value.slice(0, maxLength);
  }

  // Verificar se o comprimento mínimo é atingido
  if (pass.value.length < maxLength) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector(".resPas").textContent =
      "A senha deve ter 8 caracteres!";
    document.querySelector(".resPas").style.color = "red";
    document.querySelector(".resPas").style.fontWeight = "bold";
    document.querySelector(".resPas").style.fontSize = ".8em";
  } else {
    // Resetar estilos quando a senha for válida
    document.querySelector(".resPas").textContent = "";
    pass.style.border = "";
    pass.style.filter = "";
  }
});

document.querySelector("#cSenha").addEventListener("keyup", function () {
  const pass = document.querySelector("#senha").value;
  const cPass = document.querySelector("#cSenha");

  if (pass != cPass.value) {
    cPass.style.border = "1px solid red";
    cPass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector(".resPass").textContent =
      "As senhas não são iguais!";
    document.querySelector(".resPass").style.color = "red";
    document.querySelector(".resPass").style.fontWeight = "bold";
    document.querySelector(".resPass").style.fontSize = ".9em";
  } else {
    document.querySelector(".resPass").textContent = "";
    document.querySelector(".resPass").style.color = "";
    cPass.style.border = "";
    cPass.style.filter = "";
  }
});

document.querySelector("form").addEventListener("submit", function (event) {
  let pass = document.querySelector("#senha").value;
  let cPass = document.querySelector("#cSenha").value;
  let res = document.querySelector(".resposta");
  let sPass = pass.length >= 8;
  let passwordsMatch = pass === cPass;

  if (!passwordsMatch || !sPass) {
    document.querySelector(".loading").style.display = "none";
    event.preventDefault();
  } else {
    res.textContent = "Cadastro Efetuado!";
    document.querySelector(".loading").style.display = "block";
  }
});

// Função para verificar se apenas caracteres alfabéticos foram inseridos
function apenasAlfabeticos(event) {
  var input = event.target;
  var valor = input.value;

  // Expressão regular para verificar se há apenas caracteres alfabéticos
  var regex = /^[a-zA-Z]+$/;

  // Se o valor não corresponder à expressão regular, limpe o campo
  if (!regex.test(valor)) {
    input.value = valor.replace(/[^a-zA-Z]/g, "");
  }
}

// Adicione event listeners para os campos de login, senha e confirmação de senha
document.getElementById("user").addEventListener("input", apenasAlfabeticos);
document.getElementById("senha").addEventListener("input", apenasAlfabeticos);
document.getElementById("cSenha").addEventListener("input", apenasAlfabeticos);

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".loading").style.display = "none";
});
