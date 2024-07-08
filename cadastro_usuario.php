<?php 

include('./conexao/conexao.php');

if($_SERVER["REQUEST_METHOD"] == "POST"); {
    if(isset($_POST['nome']) && isset($_POST['cell']) && isset($_POST['cep']) && isset($_POST['estado']) && isset($_POST['cidade']) && isset($_POST['bairro']) && isset($_POST['rua']) && isset($_POST['numero']) && isset($_POST['complemento']) && isset($_POST['referencia']) && isset($_POST['user']) && isset($_POST['senha']) && isset($_POST['cSenha'])) {

        $nome = $_POST['nome'];
        $cell = $_POST['cell'];
        $cep = $_POST['cep'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $referencia = $_POST['referencia'];
        $usuario = $_POST['user'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO usuarios (nomeCliente, cell, estado, cidade, bairro, rua, numero, complemento, referencia, cep, usuario, senha) VALUES('$nome', '$cell', '$estado', '$cidade', '$bairro', '$rua', '$numero', '$complemento', '$referencia', '$cep', '$usuario', '$senha')") or die($mysqli->error);

        sleep(2);

        header("Location: ./login_usuario.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_cadastro_usuario/style.css">
    <link rel="stylesheet" href="./estilo_cadastro_usuario/media_querie.css">
    <title>Cadastre-se</title>
</head>
<body>
    
    <main>
        <section>
        <h1>Cadastre-se</h1>
            <form action="" method="post">
                <div class="inpu">
                    <input type="text" name="nome" id="nome" placeholder="nome" required>
                </div>
                <div class="inpu">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" required>
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required>
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required>
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required>
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required>
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required>
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required>
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required>
                </div>
                <div class="inpu">
                    <input type="text" name="user" id="user" placeholder="Usuário" required>
                </div>
                <div class="inpu">
                    <input type="password" name="senha" id="senha" placeholder="Senha" required>
                    <span class="resPas"></span>
                </div>
                <div class="inpu">
                    <input type="password" name="cSenha" id="cSenha" placeholder="Confirme a Senha" required>
                    <span class="resPass"></span>
                </div>
                <div class="btn">
                    <button type="submit">Cadastrar</button>
                    <button type="reset">Limpar</button>
                </div>
            </form>
        </section>

        <div class="img">
            <img src="./imagens/imagens_cadastro_usuario/culinaria-mineira-cpt.jpg" alt="imagem de comida">
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="./js_cadastro_usuario/cep.js"></script>
    <script src="./js_cadastro_usuario/formatacao.js"></script>
    <script src="./js_cadastro_usuario/funcoes.js"></script>
</body>
</html>