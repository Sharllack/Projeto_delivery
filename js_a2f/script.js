const pergunta = [
  "Qual o nome da sua mãe?",
  "Qual a data do seu nascimento?",
  "Qual o CEP do seu endereço?",
];

const indiceAleatorio = Math.floor(Math.random() * pergunta.length);

// Selecionar pergunta aleatória
const perguntaAleatoria = pergunta[indiceAleatorio];

// Exibir a pergunta na página
document.getElementById("pergunta").textContent = perguntaAleatoria;

const perguntaSel = document.querySelector("#pergunta").textContent;
const input = document.querySelector("#pass");

if (perguntaSel == "Qual o nome da sua mãe?") {
  input.placeholder = "Sua Resposta:";
} else if (perguntaSel == "Qual a data do seu nascimento?") {
  input.placeholder = "No formato: Ano-Mes-Dia";
} else {
  input.placeholder = "XXXXX-XXX";
}
