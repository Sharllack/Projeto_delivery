function buscaCEP() {
    let cep = document.getElementById('cep').value;
    if (cep != "") {
        let url = "https://brasilapi.com.br/api/cep/v1/" + cep;
        let req = new XMLHttpRequest();
        req.open("GET", url);
        req.send();

        //tratar a resposta da requisição
        req.onload = function() {
            if (req.status === 200) {
                let endereco = JSON.parse(req.response);
                document.getElementById("rua").value = endereco.street;
                document.getElementById("cidade").value = endereco.city;
                document.getElementById('estado').value = endereco.state;
                document.getElementById('bairro').value = endereco.neighborhood;
                let num = document.getElementById('numero');
                num.removeAttribute("readonly", false);
                let comp = document.getElementById('complemento');
                comp.removeAttribute("readonly", false);
                document.getElementById('cep').style.border = "1.5px solid rgb(0, 255, 42)";
                // Retornar true para indicar que o CEP é válido
                return true;
            } else if (req.status === 404) {
                document.getElementById("rua").value = "";
                document.getElementById("cidade").value = "";
                document.getElementById('estado').value = "";
                document.getElementById('bairro').value = "";
                document.getElementById('numero').value = "";
                document.getElementById('complemento').value = "";
                let num = document.getElementById('numero');
                num.setAttribute("readonly", true);
                let comp = document.getElementById('complemento');
                comp.setAttribute("readonly", true);
                document.getElementById('cep').style.border = "1.5px solid red";
                // Retornar false para indicar que o CEP é inválido
                return false;
            } else {
                alert('Erro ao fazer a requisição.');
                // Em caso de erro na requisição, retornar false
                return false;
            }
        }
    } else {
        // Retornar false se o campo CEP estiver vazio
        return false;
    }
}

if (navigator.onLine == true) {
window.onload = function() {
    let idcep = document.getElementById('cep');
    idcep.addEventListener("blur", buscaCEP);
    
    let rua = document.getElementById('rua');
    rua.setAttribute("readonly", true);
    let cdd = document.getElementById('cidade');
    cdd.setAttribute("readonly", true);
    let est = document.getElementById('estado');
    est.setAttribute("readonly", true);
    let bai = document.getElementById('bairro');
    bai.setAttribute("readonly", true);
}
}