<?php
// Inclui o arquivo de conexão
include('./conexao/conexao.php');

$erro = '';

// Define configurações de sessão
ini_set('session.cookie_httponly', 1); // Apenas acessível via HTTP
ini_set('session.cookie_secure', 1);   // Apenas em conexões HTTPS

// Inicia a sessão (se ainda não estiver iniciada)
if (!isset($_SESSION)) {
    session_start();
}

// Inicializa a variável de tentativas na sessão
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}

if (isset($_POST['pass'])) {
    $sen = $mysqli->real_escape_string($_POST['pass']);
    $usu = $_GET['user'];

    $sql_code = "SELECT * FROM usuarios WHERE usuario = '$usu' LIMIT 1";
    $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

    if ($sql_query->num_rows > 0) {
        $usuario = $sql_query->fetch_assoc();
        if ($sen == $usuario['cep'] || $sen == $usuario['nomeMae'] || $sen == $usuario['dataNascimento']) {
            // Resposta correta - Reseta tentativas
            $_SESSION['tentativas'] = 0;

            // Define as variáveis de sessão
            $_SESSION['idUsuario'] = $usuario['idUsuarios'];
            $_SESSION['user'] = $usuario['usuario'];
            $_SESSION['nome'] = $usuario['pnome'];
            $_SESSION['sobrenome'] = $usuario['sobrenome'];
            $_SESSION['contato'] = $usuario['cell'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['estado'] = $usuario['estado'];
            $_SESSION['cidade'] = $usuario['cidade'];
            $_SESSION['bairro'] = $usuario['bairro'];
            $_SESSION['rua'] = $usuario['rua'];
            $_SESSION['numero'] = $usuario['numero'];
            $_SESSION['complemento'] = $usuario['complemento'];
            $_SESSION['referencia'] = $usuario['referencia'];
            $_SESSION['cep'] = $usuario['cep'];

            header("Location: ./index.php"); // Redireciona para a página inicial após o login
            exit();
        } else {
            $_SESSION['tentativas']++;

            if ($_SESSION['tentativas'] >= 3) {
                session_destroy(); // Limpa a sessão
                header("Location: ./login_usuario.php"); // Redireciona para a tela de login
                exit();
            }
            $erro = "Resposta errada! Você tem " . (3 - $_SESSION['tentativas']) . " tentativa(s) restante(s).";
        }
    } elseif ($_POST['pass'] == '') {
        $erro = 'Preencha os dados corretamente!';
    } else {
        $erro = '';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_login_usuario/style.css">
    <link rel="stylesheet" href="./estilo_login_usuario/media_querie.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Login</title>
</head>

<body>
    <div class="voltar">
        <a href="./login_usuario.php">Voltar</a>
    </div>
    <main>
        <form action="" method="post">
            <h1>Autenticação</h1>
            <label for="pass" id="pergunta"
                style="color: white; margin-bottom: -35px; margin-top: 25px; display: block;"></label>
            <input type="text" name="pass" id="pass" placeholder="Sua Resposta:" style="margin-top: 50px;"><br>
            <p id="erro" style="color: red; font-size:.8em; text-align:center;"><?php echo $erro; ?></p>
            <div class="btn">
                <button type="submit">Entrar</button>
            </div>
        </form>
        <div class="img">
            <img src="./imagens/imagens_login/variedade-plana-com-deliciosa-comida-brasileira_23-2148739179.avif"
                alt="">
        </div>
    </main>
    <script src="./js_a2f/script.js"></script>
</body>

</html>