<?php 

require_once './conexao.php';

if($_SERVER["REQUEST_METHOD"] == "POST"); {
    if(isset($_POST['user']) && isset($_POST['pass'])) {

        $usu = $mysqli->real_escape_string($_POST['user']);
        $sen = $mysqli->real_escape_string($_POST['pass']);

        $user = $_POST['user'];
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO host (user, pass) VALUES('$user', '$pass')") or die($mysqli->error);

        sleep(2);

        header("Location: login_adm.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_cadastro/style.css">
    <link rel="stylesheet" href="../estilo_cadastro/media_querie.css">
    <title>Cadastre-se</title>
</head>

<body>

    <main>
        <form action="" method="post">
            <h1>Cadastre-se</h1>
            <input type="text" name="user" id="user" placeholder="Usuário">
            <input type="password" name="pass" id="pass" placeholder="Senha">
            <span class="resPas"></span>
            <input type="password" name="cPass" id="cPass" placeholder="Confirme a Senha">
            <span class="resPass"></span>
            <br>
            <div class="btn">
                <button type="submit">Cadastrar</button>
                <button type="reset">Limpar</button>
            </div>
        </form>
        <div class="img">
            <img src="../imagens/imagens_login/variedade-plana-com-deliciosa-comida-brasileira_23-2148739179.avif"
                alt="">
        </div>
    </main>
    <script src="../js_cadastro/script.js"></script>
</body>

</html>