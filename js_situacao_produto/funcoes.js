setInterval(function () {
  location.reload();
}, 2500);

//------------ DARK MODE -------------
const toggle = document.querySelector("#toggle");
const main = document.querySelector("main");
const btn1 = document.querySelector("#btn1");
const btn2 = document.querySelector("#btn2");
const btn3 = document.querySelector("#btn3");

toggle.addEventListener("change", function () {
  if (this.checked) {
    console.log("Toggle ON");
    main.style.backgroundColor = "#000000e8";
    main.style.transition = ".5s";
    main.style.color = "white";
    btn1.style.backgroundColor = "white";
    btn2.style.backgroundColor = "white";
    btn3.style.backgroundColor = "white";

    localStorage.setItem("darkMode", "on");
  } else {
    console.log("Toggle OFF");
    main.style.backgroundColor = "";
    main.style.transition = ".5s";
    main.style.color = "";
    btn1.style.backgroundColor = "";
    btn2.style.backgroundColor = "";
    btn3.style.backgroundColor = "";

    localStorage.setItem("darkMode", "off");
  }
});

const darkModePreference = localStorage.getItem("darkMode");
if (darkModePreference === "on") {
  main.style.backgroundColor = "#000000e8";
  main.style.transition = ".5s";
  main.style.color = "white";
  btn1.style.backgroundColor = "white";
  btn2.style.backgroundColor = "white";
  btn3.style.backgroundColor = "white";

  localStorage.setItem("darkMode", "on");

  toggle.checked = true;
} else {
  main.style.backgroundColor = "";
  main.style.transition = ".5s";
  main.style.color = "";
  btn1.style.backgroundColor = "";
  btn2.style.backgroundColor = "";
  btn3.style.backgroundColor = "";
}
