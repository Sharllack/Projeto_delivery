<?php 

include('./conexao/conexao.php');

if(isset($_GET['usuario'])) {
    $user = $mysqli->real_escape_string($_GET['usuario']);

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $senha = password_hash($_POST['sen'], PASSWORD_DEFAULT);
    
        $stmt = $mysqli->prepare("UPDATE usuarios SET senha = ? WHERE usuario = ?");
        $stmt->bind_param("ss", $senha, $user);
        $stmt->execute();
        $stmt->close();
    
        sleep(2);
    
        header("Location: ./login_usuario.php");
    
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_recovery/style.css">
    <link rel="stylesheet" href="./estilo_recovery/media_query.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Nova Senha</title>
</head>
<body>
    <div class="voltar">
        <a href="./login_usuario.php">Voltar</a>
    </div>
    <form action="" method="post">
        <h1>Nova Senha</h1>
        <div class="input">
            <input type="password" name="sen" id="sen" placeholder="Nova Senha">
            <p class="resSen" style="color: red; font-size: .9em;">Mínimo de 8 caracteres!</p>
            <input type="password" name="cSen" id="cSen" placeholder="Confirme a Nova Senha" style="margin-top: 10px;">
            <p class="resCsen" style="color: red; font-size: .9em;">As senhas não correspondem!</p>
        </div>
        <p class="resposta" style="color: white; font-weight: bold;"></p>
        <div class="btn">
            <button type="submit">Cadastrar</button>
            <button type="reset">Limpar</button>
        </div>
    </form>

    <script src="./js_recovery/recovery.js"></script>
</body>
</html>