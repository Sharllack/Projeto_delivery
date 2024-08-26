<?php 
    require_once './conexao.php';

    $erro = '';

    if(isset($_POST['user']) || isset($_POST['pass'])) { //Verificar se existe a variável.

        $usu = $mysqli->real_escape_string($_POST['user']);
        $sen = $mysqli->real_escape_string($_POST['pass']);

        $usu = $_POST['user'];
        $sen = $_POST['pass'];

        $sql_code = "SELECT * FROM host WHERE user = '$usu' LIMIT 1";
        $sql_query = $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
    
    if($sql_query -> num_rows > 0) {
        $usuario = $sql_query->fetch_assoc();
        if(password_verify($sen, $usuario['pass'])) {
            if(!isset($_SESSION)) {
                session_name('admin_session');
                session_start();
            }

            $_SESSION['user'] = $usuario['user']; // session é uma variável que continua válida em mais de uma tela.
            $_SESSION['idUsuario'] = $usuario['idAdmin'];
            $_SESSION['situacao'] = $usuario['situacao'];

            header("Location: adicionar_produto.php"); //para redirecionar a pág.

        } else {
            $erro = 'Usuário ou senha incorretos!';
        }
    } else {
        $erro = 'Usuário não encontrado!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_login/style.css">
    <link rel="stylesheet" href="../estilo_login/media_querie.css">
    <title>Login</title>
</head>

<body>

    <main>
        <form action="" method="post">
            <h1>Login</h1>
            <input type="text" name="user" id="user" placeholder="Usuário">
            <input type="password" name="pass" id="pass" placeholder="Senha"><br>
            <p id="erro" style="color: red; font-size:.8em; text-align:center;"><?php echo $erro; ?></p>
            <div class="btn">
                <button type="submit">Entrar</button>
            </div>
        </form>
        <div class="img">
            <img src="../imagens/imagens_login/variedade-plana-com-deliciosa-comida-brasileira_23-2148739179.avif"
                alt="">
        </div>
    </main>
</body>

</html>