<?php

include("./conexao/conexao.php");

if (!isset($_SESSION)) {
    session_start();
}

$cell_error = $email_error = '';
$userId = $_SESSION['idUsuario']; // Obtém o ID do usuário atual

$stmt = $mysqli->prepare("SELECT idCarrinho FROM pedidos WHERE idUsuario = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($idCarrinho);
$stmt->fetch();
$stmt->close();

if (isset($_SESSION['pedido_finalizado'][$idCarrinho]) && $_SESSION['pedido_finalizado'][$idCarrinho] === true) {
    // Redireciona para a página de acompanhamento do pedido
    header('Location: ./situacao_pedido.php');
    exit;
};

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome'])
        && isset($_POST['sNome'])
        && isset($_POST['cell'])
        && isset($_POST['cep'])
        && isset($_POST['estado'])
        && isset($_POST['cidade'])
        && isset($_POST['bairro'])
        && isset($_POST['rua'])
        && isset($_POST['numero'])
        && isset($_POST['complemento'])
        && isset($_POST['referencia'])
        && isset($_POST['email'])) {

        $nome = $_POST['nome'];
        $sNome = $_POST['sNome'];
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

        $_SESSION['cep'] = $cep;
        $_SESSION['rua'] = $rua;
        $_SESSION['numero'] = $numero;
        $_SESSION['bairro'] = $bairro;
        $_SESSION['cidade'] = $cidade;
        $_SESSION['estado'] = $estado;
        $_SESSION['complemento'] = $complemento;
        $_SESSION['referencia'] = $referencia;
        $_SESSION['nome'] = $nome;
        $_SESSION['sobrenome'] = $sNome;
        $_SESSION['contato'] = $cell;
        $_SESSION['email'] = $email;

        // Verifica se o celular já está cadastrado, excluindo o usuário atual
        $sql_cell = "SELECT * FROM usuarios WHERE cell = ? AND idUsuarios != ? LIMIT 1";
        $stmt = $mysqli->prepare($sql_cell);
        $stmt->bind_param("si", $cell, $userId);
        $stmt->execute();
        $result_cell = $stmt->get_result();

        if ($result_cell->num_rows > 0) {
            $cell_error = "Celular já cadastrado.";
        }

        // Verifica se o email já está cadastrado, excluindo o usuário atual
        $sql_email = "SELECT * FROM usuarios WHERE email = ? AND idUsuarios != ? LIMIT 1";
        $stmt = $mysqli->prepare($sql_email);
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $result_email = $stmt->get_result();

        if ($result_email->num_rows > 0) {
            $email_error = "E-mail já cadastrado.";
        }

        if (empty($cell_error) && empty($email_error)) {
            // Atualiza o registro do usuário
            $stmt = $mysqli->prepare("UPDATE usuarios SET pnome = ?, sobrenome = ?, cell = ?, email = ?, estado = ?, cidade = ?, bairro = ?, rua = ?, numero = ?, complemento = ?, referencia = ?, cep = ? WHERE idUsuarios = ?");
            $stmt->bind_param("ssssssssssssi", $nome, $sNome, $cell, $email, $estado, $cidade, $bairro, $rua, $numero, $complemento, $referencia, $cep, $userId);
            $stmt->execute();
            $stmt->close();

            sleep(2);

            header("Location: ./index.php");
            exit;
        }
    }
}

$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE idUsuarios = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_perfil/style.css">
    <link rel="stylesheet" href="./estilo_perfil/media_query.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Perfil</title>
</head>
<body>

    <div class="loading">
        <div class="load">

        </div>
    </div>

    <div class="voltar">
        <a href="./index.php">Voltar</a>
    </div>
    <div class="deletePerfil">
        <p class="openWindow">Excluir Perfil</p>
    </div>
    <div class="over"></div>

    <div class="opcoes">
        <form action="./deletar_perfil.php" method="post" class="excl">
            <div class="close">
                <p class="closeWindow"><img src="./imagens/close_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" alt="Fechar"></p>
            </div>
            <h1>Excluir Perfil</h1>
            <input type="password" name="pass" id="pass" placeholder="Confirme a Sua Senha" required>
            <p style="color: red; font-weight: bold; font-size: .8em;"><?php echo htmlspecialchars($_SESSION['errorPass'] ?? '', ENT_QUOTES, 'UTF-8')?></p>
            <div class="btns">
                <button type="submit">Excluir</button>
                <p class="closeWindow" >Cancelar</p>
            </div>
        </form>
    </div>
    <main>
        <section>
        <h1 class="titleForm">Minhas Informações</h1>
            <form action="" method="post">
                <div class="inpu" id="in">
                    <input type="text" name="nome" id="pNome" placeholder="Nome" required value="<?php echo htmlspecialchars($row['pnome'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="sNome" id="sNome" placeholder="Sobrenome" required value="<?php echo htmlspecialchars($row['sobrenome'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu" id="im">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" required value="<?php echo htmlspecialchars($row['cell'], ENT_QUOTES, 'UTF-8') ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo htmlspecialchars($cell_error, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="inpu">
                    <input type="text" name="email" id="email" placeholder="E-mail" required value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo htmlspecialchars($email_error, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required value="<?php echo htmlspecialchars($row['cep'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required value="<?php echo htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required value="<?php echo htmlspecialchars($row['cidade'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required value="<?php echo htmlspecialchars($row['bairro'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required value="<?php echo htmlspecialchars($row['rua'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required value="<?php echo htmlspecialchars($row['numero'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)" value="<?php echo htmlspecialchars($row['complemento'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required value="<?php echo htmlspecialchars($row['referencia'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <p class="resposta" style="color: white;"></p>
                <div class="btn">
                    <button type="submit" class="edit">Editar</button>
                    <a href="./index.php">Cancelar</a>
                </div>
            </form>
        </section>
        <div class="img">
            <img src="./imagens/imagens_cadastro_usuario/comida-brasileira-simples-com-espaco-de-copia_23-2148739186.avif" alt="">
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="./js_perfil/formatacao.js"></script>
    <script src="./js_perfil/cep.js"></script>
    <script src="./js_perfil/funcoes.js"></script>
</body>
</html>
