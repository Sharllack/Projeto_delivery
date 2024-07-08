<?php 
    include('./conexao/conexao.php');

    $erro = '';

    if(isset($_POST['user']) || isset($_POST['pass'])) { //Verificar se existe a variável.

         $usu = $mysqli->real_escape_string($_POST['user']);
         $sen = $mysqli->real_escape_string($_POST['pass']);

        $usu = $_POST['user'];
        $sen = $_POST['pass'];

        $sql_code = "SELECT * FROM usuarios WHERE usuario = '$usu' LIMIT 1";
        $sql_query = $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
    
        $usuario = $sql_query->fetch_assoc();
        if(password_verify($sen, $usuario['senha'])) {
            if(!isset($_SESSION)) {
                session_name('user_session');
                session_start();
            }

            $_SESSION['idUsuario'] = $usuario['idUsuarios']; // session é uma variável que continua válida em mais de uma tela.
            $_SESSION['user'] = $usuario['usuario'];
            $_SESSION['nome'] = $usuario['nomeCliente'];
            $_SESSION['contato'] = $usuario['cell'];
            $_SESSION['estado'] = $usuario['estado'];
            $_SESSION['cidade'] = $usuario['cidade'];
            $_SESSION['bairro'] = $usuario['bairro'];
            $_SESSION['rua'] = $usuario['rua'];
            $_SESSION['numero'] = $usuario['numero'];
            $_SESSION['complemento'] = $usuario['complemento'];
            $_SESSION['referencia'] = $usuario['referencia'];
            $_SESSION['cep'] = $usuario['cep'];

            header("Location: ./index.php"); //para redirecionar a pág.

        } else {
            $erro = 'Usuário ou senha incorretos!';
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
    <title>Login</title>
</head>
<body>
    
    <main>
        <form action="" method="post">
            <h1>Login</h1>
            <input type="text" name="user" id="user" placeholder="Usuário">
            <input type="password" name="pass" id="pass" placeholder="Senha"><br>
            <p id="erro" style="color: red; font-size:.8em; text-align:center;"><?php echo $erro; ?></p>
            <a href="#">Esqueci a senha</a>
            <div class="btn">
                <button type="submit">Entrar</button>
                <button type="button"><a href="./cadastro_usuario.php">Cadastre-se</a></button>
            </div>
        </form>
        <div class="img">
            <img src="./imagens/imagens_login/variedade-plana-com-deliciosa-comida-brasileira_23-2148739179.avif" alt="">
        </div>
    </main>
</body>
</html>