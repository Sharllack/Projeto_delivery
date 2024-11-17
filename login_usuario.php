<?php
// Inclui o arquivo de conexão
include('./conexao/conexao.php');

$erro = '';

// Define configurações de session
ini_set('session.cookie_httponly', 1); // Apenas acessível via HTTP
ini_set('session.cookie_secure', 1);   // Apenas em conexões HTTPS

// Inicia a sessão (se ainda não estiver iniciada)


if (isset($_POST['user']) && isset($_POST['pass'])) {
    $usu = $mysqli->real_escape_string($_POST['user']);
    $sen = $mysqli->real_escape_string($_POST['pass']);

    $sql_code = "SELECT * FROM usuarios WHERE usuario = '$usu' AND ativo = 'sim' LIMIT 1";
    $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

    if ($sql_query->num_rows > 0) {
        $usuario = $sql_query->fetch_assoc();
        if (password_verify($sen, $usuario['senha'])) {
            if (!isset($_SESSION)) {
                session_start();
            }

            header("Location: ./autenticacao.php?user=$usu");
            exit();
        } else {
            $erro = 'Usuário ou senha incorretos!';
        }
    } elseif ($_POST['user'] == '' || $_POST['pass'] == '') {
        $erro = 'Preencha os dados corretamente!';
    } else {
        $erro = 'Usuário não encontrado.';
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
        <a href="./index.php">Voltar</a>
    </div>
    <main>
        <form action="" method="post">
            <h1>Login</h1>
            <input type="text" name="user" id="user" placeholder="Usuário"
                value="<?php echo isset($_POST['user']) ? $_POST['user'] : ''; ?>">
            <input type="password" name="pass" id="pass" placeholder="Senha"><br>
            <p id="erro" style="color: red; font-size:.8em; text-align:center;"><?php echo $erro; ?></p>
            <a href="./pass_reco.php">Esqueci a senha</a>
            <div class="btn">
                <button type="submit">Entrar</button>
                <a href="./cadastro_usuario.php">Cadastre-se</a>
            </div>
        </form>
        <div class="img">
            <img src="./imagens/imagens_login/variedade-plana-com-deliciosa-comida-brasileira_23-2148739179.avif"
                alt="">
        </div>
    </main>
</body>

</html>