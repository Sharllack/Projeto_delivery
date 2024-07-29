
//------------ DARK MODE -------------
const toggle = document.querySelector('#toggle');
const main = document.querySelector('main');

toggle.addEventListener('change', function() {
  if (this.checked) {
      console.log('Toggle ON');
      main.style.backgroundColor = "#000000e8";
      main.style.transition = '.5s';
      main.style.color = "white";

      localStorage.setItem('darkMode', 'on');
      
  } else {
      console.log('Toggle OFF');
      main.style.backgroundColor = "";
      main.style.transition = '.5s';
      main.style.color = "black";

      localStorage.setItem('darkMode', 'off');

};
});

const darkModePreference = localStorage.getItem('darkMode');
if (darkModePreference === 'on') {
      main.style.backgroundColor = "#000000e8";
      main.style.transition = '.5s';
      main.style.color = "white";

      localStorage.setItem('darkMode', 'on');

    toggle.checked = true;
    
} else {
      main.style.backgroundColor = "";
      main.style.transition = '.5s';
      main.style.color = "black";
}