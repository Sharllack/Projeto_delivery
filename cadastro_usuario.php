<?php

include('./conexao/conexao.php');

$usu_error = $cell_error = $email_error = $cpf_error = $senha_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['nome'])
        && isset($_POST['sNome'])
        && isset($_POST['nomeMae'])
        && isset($_POST['dataNascimento'])
        && isset($_POST['cpf'])
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
        && isset($_POST['cSenha'])
        && isset($_POST['email'])
    ) {

        $nome = $_POST['nome'];
        $sNome = $_POST['sNome'];
        $nomeMae = $_POST['nomeMae'];
        $dataNascimento = $_POST['dataNascimento'];
        $cpf = $_POST['cpf'];
        $cell = $_POST['cell'];
        $email = $_POST['email'];
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

        // Validações
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "E-mail inválido.";
        }
        if ($_POST['senha'] !== $_POST['cSenha']) {
            $senha_error = "As senhas não correspondem.";
        }

        // Verificações de duplicidade
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE cell = ? LIMIT 1");
        $stmt->bind_param("s", $cell);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $cell_error = "Celular já cadastrado.";
        }
        $stmt->close();

        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $email_error = "E-mail já cadastrado.";
        }
        $stmt->close();

        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $usu_error = "Usuário já cadastrado.";
        }
        $stmt->close();

        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE cpf = ? LIMIT 1");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $cpf_error = "CPF já cadastrado.";
        }
        $stmt->close();

        // Inserção no banco
        if (empty($usu_error) && empty($cell_error) && empty($email_error) && empty($cpf_error) && empty($senha_error)) {
            $stmt = $mysqli->prepare("INSERT INTO usuarios (pnome, nomeMae, dataNascimento, cpf, sobrenome, cell, email, estado, cidade, bairro, rua, numero, complemento, referencia, cep, usuario, senha) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssssssss", $nome, $nomeMae, $dataNascimento, $cpf, $sNome, $cell, $email, $estado, $cidade, $bairro, $rua, $numero, $complemento, $referencia, $cep, $usuario, $senha);
            $stmt->execute();
            $stmt->close();
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
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Cadastre-se</title>
</head>

<body>

    <div class="loading">
        <div class="load">

        </div>
    </div>

    <div class="voltar">
        <a href="./login_usuario.php">Voltar</a>
    </div>
    <main>
        <section>
            <h1 class="titleForm">Cadastre-se</h1>
            <form action="" method="post" id="principal">
                <div class="inpu" id="in">
                    <input type="text" name="nome" id="pNome" placeholder="Nome" required
                        value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="sNome" id="sNome" placeholder="Sobrenome" required
                        value="<?php echo isset($_POST['sNome']) ? $_POST['sNome'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="nomeMae" id="nomeMae" placeholder="Nome completo da Mãe" required
                        value="<?php echo isset($_POST['nomeMae']) ? $_POST['nomeMae'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="date" name="dataNascimento" id="dataNascimento" required
                        value="<?php echo isset($_POST['dataNascimento']) ? $_POST['dataNascimento'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" onblur="verificarCPF()" required
                        value="<?php echo isset($_POST['cpf']) ? $_POST['cpf'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $cpf_error ?></p>
                </div>
                <div class="inpu" id="im">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" required
                        value="<?php echo isset($_POST['cell']) ? $_POST['cell'] : '+55'; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $cell_error ?></p>
                </div>
                <div class="inpu">
                    <input type="text" name="email" id="email" placeholder="E-mail" required
                        value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $email_error ?></p>
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required
                        value="<?php echo isset($_POST['cep']) ? $_POST['cep'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required
                        value="<?php echo isset($_POST['estado']) ? $_POST['estado'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required
                        value="<?php echo isset($_POST['cidade']) ? $_POST['cidade'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required
                        value="<?php echo isset($_POST['bairro']) ? $_POST['bairro'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required
                        value="<?php echo isset($_POST['rua']) ? $_POST['rua'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required
                        value="<?php echo isset($_POST['numero']) ? $_POST['numero'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)"
                        value="<?php echo isset($_POST['complemento']) ? $_POST['complemento'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required
                        value="<?php echo isset($_POST['referencia']) ? $_POST['referencia'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="user" id="user" placeholder="Usuário" required
                        value="<?php echo isset($_POST['user']) ? $_POST['user'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $usu_error ?></p>
                    <span class="resUser"></span>
                </div>
                <div class="inpu" id="bgSenha">
                    <input type="password" name="senha" id="senha" placeholder="Senha" required>
                    <span class="resPas"></span>
                </div>
                <div class="inpu" id="bgCsenha">
                    <input type="password" name="cSenha" id="cSenha" placeholder="Confirme a Senha" required>
                    <span class="resPass"></span>
                </div>
                <p class="resposta" style="color: white;"></p>
                <div class="btn">
                    <button id="prox">Próximo</button>
                    <button type="reset">Limpar</button>
                </div>
            </form>
        </section>
        <div class="img">
            <img src="./imagens/imagens_cadastro_usuario/comida-brasileira-simples-com-espaco-de-copia_23-2148739186.avif"
                alt="">
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="./js_cadastro_usuario/cep.js"></script>
    <script src="./js_cadastro_usuario/cpf.js"></script>
    <script src="./js_cadastro_usuario/formatacao.js"></script>
    <script src="./js_cadastro_usuario/funcoes.js"></script>
</body>

</html>