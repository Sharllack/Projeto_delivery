<?php 

include('./conexao/conexao.php');

$usu_error = $cell_error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['nome']) 
    && isset($_POST['cell']) 
    && isset($_POST['cep']) 
    && isset($_POST['estado']) 
    && isset($_POST['cidade']) 
    && isset($_POST['bairro']) 
    && isset($_POST['rua']) 
    && isset($_POST['numero']) 
    && isset($_POST['complemento']) 
    && isset($_POST['referencia']) 
    && isset($_POST['user']) 
    && isset($_POST['senha']) 
    && isset($_POST['cSenha'])) {

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

       $sql_cell = "SELECT * FROM usuarios WHERE cell = '$cell' LIMIT 1";
       $result_cell = $mysqli->query($sql_cell);

       if($result_cell->num_rows > 0) {
        $cell_error = "Celular já cadastrado.";
       }

       $sql_user = "SELECT * FROM usuarios WHERE usuario = '$usuario' LIMIT 1";
       $result_user = $mysqli->query($sql_user);

       if($result_user->num_rows > 0) {
        $usu_error = "Usuario já cadastrado.";
       }

       if(empty($usu_error) && empty($cell_error)){
        $mysqli->query("INSERT INTO usuarios (nomeCliente, cell, estado, cidade, bairro, rua, numero, complemento, referencia, cep, usuario, senha) VALUES('$nome', '$cell', '$estado', '$cidade', '$bairro', '$rua', '$numero', '$complemento', '$referencia', '$cep', '$usuario', '$senha')") or die($mysqli->error);

        sleep(2);

        header("Location: ./login_usuario.php");
        exit;
       }
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
                    <input type="text" name="nome" id="nome" placeholder="nome" required value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" required value="<?php echo isset($_POST['cell']) ? $_POST['cell'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $cell_error?></p>
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required value="<?php echo isset($_POST['cep']) ? $_POST['cep'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required value="<?php echo isset($_POST['estado']) ? $_POST['estado'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required value="<?php echo isset($_POST['cidade']) ? $_POST['cidade'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required value="<?php echo isset($_POST['bairro']) ? $_POST['bairro'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required value="<?php echo isset($_POST['rua']) ? $_POST['rua'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required value="<?php echo isset($_POST['numero']) ? $_POST['numero'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)" value="<?php echo isset($_POST['complemento']) ? $_POST['complemento'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required value="<?php echo isset($_POST['referencia']) ? $_POST['referencia'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="user" id="user" placeholder="Usuário" required value="<?php echo isset($_POST['user']) ? $_POST['user'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $usu_error?></p>
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