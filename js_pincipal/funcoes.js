setInterval(function () {
  location.reload();
}, 60000);

//------------ DARK MODE -------------
const main = document.querySelector("main");
const toggle = document.querySelector("#toggle");
const pratos = document.querySelectorAll(".linkPratos");
const secPratos = document.querySelector(".pratosTitle");
const secBebidas = document.querySelector(".titleBebidas");

toggle.addEventListener("change", function () {
  if (this.checked) {
    console.log("Toggle ON");
    pratos.forEach(function (elemento) {
      elemento.style.backgroundColor = "black";
      elemento.style.boxShadow = "2px 2px 10px rgba(70, 70, 70)";
      elemento.style.border = "rgba(65, 65, 65)";
      elemento.style.transition = ".5s";
      elemento.style.color = "white";
    });
    main.style.backgroundColor = "#000000e8";
    main.style.transition = ".5s";
    main.style.color = "white";
    secBebidas.style.color = "white";
    secPratos.style.color = "white";

    localStorage.setItem("darkMode", "on");
  } else {
    console.log("Toggle OFF");
    pratos.forEach(function (elemento) {
      elemento.style.backgroundColor = "";
      elemento.style.boxShadow = "2px 2px 10px rgba(0, 0, 0, 0.199)";
      elemento.style.border = "silver";
      elemento.style.transition = ".5s";
      elemento.style.color = "black";
    });
    main.style.backgroundColor = "";
    main.style.transition = ".5s";
    main.style.color = "black";
    secBebidas.style.color = "darkslategray";
    secPratos.style.color = "darkslategray";

    localStorage.setItem("darkMode", "off");
  }
});

const slidePref = localStorage.getItem("slide");

const darkModePreference = localStorage.getItem("darkMode");
if (darkModePreference === "on") {
  pratos.forEach(function (elemento) {
    elemento.style.backgroundColor = "black";
    elemento.style.boxShadow = "2px 2px 10px rgba(70, 70, 70)";
    elemento.style.border = "rgba(65, 65, 65)";
    elemento.style.transition = ".5s";
    elemento.style.color = "white";
  });
  main.style.backgroundColor = "#000000e8";
  main.style.transition = ".5s";
  main.style.color = "white";
  secBebidas.style.color = "white";
  secPratos.style.color = "white";

  localStorage.setItem("darkMode", "on");

  toggle.checked = true;
} else {
  pratos.forEach(function (elemento) {
    elemento.style.backgroundColor = "";
    elemento.style.boxShadow = "2px 2px 10px rgba(0, 0, 0, 0.199)";
    elemento.style.border = "silver";
    elemento.style.transition = ".5s";
    elemento.style.color = "black";
  });
  main.style.backgroundColor = "";
  main.style.transition = ".5s";
  main.style.color = "black";
  secBebidas.style.color = "darkslategray";
  secPratos.style.color = "darkslategray";
}

function atualizarSituacao() {
  const sit = document.querySelector(".sit");
  const carrinho = document.querySelector(".carrinho");
  const link = document.querySelectorAll(".linkPratos");
  const perfil = document.querySelector(".perfil");
  const log = document.querySelector(".logBtn");
  const situ = document.querySelector(".situ").innerHTML;
  const hora = new Date().getHours();

  if (hora >= 11 || hora < 15) {
    if (situ == "Aberto") {
      sit.textContent = "Aberto";
      sit.className = "open";
    } else {
      sit.textContent = "Fechado";
      sit.className = "closed";

      link.forEach(function (link) {
        link.href = "#";
      });
      carrinho.href = "#";
      perfil.href = "#";
      log.href = "#";
    }
  } else {
    sit.textContent = "Fechado";
    sit.className = "closed";

    link.forEach(function (link) {
      link.href = "#";
    });
    carrinho.href = "#";
    perfil.href = "#";
    log.href = "#";
  }
}

atualizarSituacao(); // Chama a função imediatamente ao carregar a página
setInterval(atualizarSituacao, 60000); // Chama a função a cada minuto

function atualizarTaxa() {
  const taxa = document.querySelector("#taxa").innerHTML;
  const carrinho = document.querySelector(".carrinho");
  const link = document.querySelectorAll(".linkPratos");
  const log = document.querySelector(".logBtn");

  if (taxa === "Endereço não atendido!") {
    link.forEach(function (link) {
      link.href = "#";
    });
    carrinho.href = "#";
    log.href = "#";
  }
}

atualizarTaxa(); // Chama a função imediatamente ao carregar a página

document.querySelector(".linkPratos").addEventListener("click", () => {
  document.querySelector(".loading").style.display = "block";
});

$(document);
