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

document.querySelector("#senha").addEventListener("keyup", function () {
  let pass = document.querySelector("#senha");

  if (pass.value.length < 8) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector(".resPas").textContent =
      "A senha deve ter no mínimo 8 caracteres!";
    document.querySelector(".resPas").style.color = "red";
    document.querySelector(".resPas").style.fontWeight = "bold";
    document.querySelector(".resPas").style.fontSize = ".8em";
  } else {
    document.querySelector(".resPas").textContent = "";
    document.querySelector(".resPas").style.color = "";
    pass.style.border = "";
    pass.style.filter = "";
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

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".loading").style.display = "none";
});
